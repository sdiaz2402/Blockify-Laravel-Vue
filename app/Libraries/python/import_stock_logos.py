import csv
import requests
import mysql.connector
from mysql.connector import errorcode
import yfinance as yf

FILE = "tickers.csv"

TICKER_COLUMN = 3
NAME_COLUMN = 4
SECTOR_COLUMN = 5
SUB_COLUMN = 6

sql_hostname = '68.235.35.131'
sql_username = 'diegoczul_stocks'
sql_password = 'Cool**88'
sql_main_database = 'diegoczul_stocks'
sql_port = 3306




conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

cursor = conn.cursor(dictionary=True,buffered=True)

conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

cursor = conn.cursor(dictionary=True,buffered=True)

cursor.execute("select * from instrument_groups where logo is null or logo = ''")

conn.commit()

myresult = cursor.fetchall()

for stock_object_ in myresult:

    print("processing: "+stock_object_["name"])

    tick = yf.Ticker(stock_object_["name"])

    info_stock = tick.info

    print(info_stock)

    if "bid" in info_stock and info_stock["logo_url"] != None:
        logo = info_stock["logo_url"]
    else:
        logo = ""

    if(logo != ""):

        cursor = conn.cursor(dictionary=True,buffered=True)

        cursor.execute("update instrument_groups set logo = %s where name = %s",(logo,stock_object_["name"]))

        conn.commit()

        cursor.close()

conn.close()
