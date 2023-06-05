import csv
import requests
import mysql.connector
from mysql.connector import errorcode
import yfinance as yf
import sys

FILE = "tickers.csv"

TICKER_COLUMN = 1
NAME_COLUMN = 2
SECTOR_COLUMN = 3
SUB_COLUMN = 4

sql_hostname = '68.235.35.131'
sql_username = 'diegoczul_stocks'
sql_password = 'Cool**88'
sql_main_database = 'diegoczul_stocks'
sql_port = 3306


with open(FILE, newline='') as csvfile:

    spamreader = csv.reader(csvfile, delimiter=',', quotechar='|')

    for row in spamreader:

        print(row[TICKER_COLUMN])

        ticker = row[TICKER_COLUMN]
        name = row[NAME_COLUMN]
        sector = row[SECTOR_COLUMN]
        subindustry = row[SUB_COLUMN]

        conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

        cursor = conn.cursor(dictionary=True,buffered=True)

        conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

        cursor = conn.cursor(dictionary=True,buffered=True)

        cursor.execute("select * from instrument_groups where name = %s",(ticker,))

        conn.commit()

        myresult = cursor.fetchall()





        if(len(myresult) == 0):

            tick = yf.Ticker(ticker)

            info_stock = tick.info

            # print(info_stock)

            if "bid" in info_stock and info_stock["logo_url"] != None:
                logo = info_stock["logo_url"]
            else:
                logo = ""

            params = {}

            stock_previous_close = 0
            if("regularMarketPreviousClose" in info_stock):
                stock_previous_close = info_stock["regularMarketPreviousClose"]
                if(stock_previous_close == None or stock_previous_close == 0):
                    if("previousClose" in info_stock):
                        stock_previous_close = info_stock["previousClose"]

            change = 0

            params["last"] = 0

            if "bid" in info_stock and info_stock["bid"] != None:
                    params["last"] = info_stock["bid"]

            if(params["last"] == None or params["last"] == 0):
                params["last"] = info_stock["regularMarketPrice"]

            if stock_previous_close != None and params["last"] != None and stock_previous_close != 0:
                change = (params["last"] - stock_previous_close) / stock_previous_close
            else:
                change = 0

            params["change"] = float(change)
            params["logo"] = logo
            params["ticker"] = ticker
            params["name"] = name
            params["sector"] = sector
            params["subindustry"] = subindustry

            print(params)

            if(params["last"] == None):
                params["last"] = 0

            if(params["change"] == None):
                params["change"] = 0



            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("insert into instrument_groups (name,context_name,sector,subindustry,logo,last_price,`change` ) values (%s, %s, %s, %s, %s, %s, %s)",(str(params["ticker"]),str(params["name"]),str(params["sector"]),str(params["subindustry"]),str(params["logo"]),float(params["last"]),float(params["change"])))


            conn.commit()



            cursor.close()


        # else:
        #     cursor = conn.cursor(dictionary=True,buffered=True)

        #     cursor.execute("update instrument_groups set logo = %s,sector= %s,subindustry= %s where name = %s",(logo,sector,subindustry,ticker))

        #     conn.commit()

        #     cursor.close()

    conn.close()
