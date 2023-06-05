#!/usr/bin/env python3

import os
import glob
import urllib.request
import logging
import json
import yfinance as yf
import mysql.connector
from mysql.connector import errorcode
import paramiko
import pandas as pd
import math
from paramiko import SSHClient
from sshtunnel import SSHTunnelForwarder
from os.path import expanduser
from datetime import datetime, timedelta
import sys
import traceback

logging.basicConfig(filename='error.log',level=logging.DEBUG)

LIVE = True

years = {}
years_1 = {}
years["2021"] = "2021-01-02"
years_1["2021"] = "2021-01-05"
years["2022"] = "2022-01-02"
years_1["2022"] = "2022-01-03"


#########################################################################
# SSH TUNNEL INIT
#########################################################################
# DB_USER = "root"
# DB_PASSWORD = ""
# DB_HOST = "127.0.0.1"
# DB_DB = "fsinsight_core_db"

DB_USER = "diegoczul.com"
DB_PASSWORD = "Cool**88"
DB_HOST = "diegoczul.com"
DB_DB = "diegoczul_stocks"

if LIVE:
    home = expanduser('~')
    pkeyfilepath = ""
    mypkey = None

else:
    home = expanduser('~')
    pkeyfilepath = "/.ssh/id_rsa"
    mypkey = paramiko.RSAKey.from_private_key_file(home + pkeyfilepath)
# if you want to use ssh password use - ssh_password='your ssh password', bellow

#########################################################################
# MYSQL INIT
#########################################################################
# sql_hostname = 'localhost'
# sql_username = 'root'
# sql_password = ''
# sql_main_database = 'fsinsight_core_db'
# sql_port = 3306

# sql_hostname = 'diegoczul.com'
sql_hostname = 'localhost'
sql_username = 'diegoczul_stocks'
sql_password = 'Cool**88'
sql_main_database = 'diegoczul_stocks'
sql_port = 3306


ssh_host = '142.93.68.23'
ssh_user = 'root'
ssh_port = 22


#########################################################################
# ATTRIBUTES
#########################################################################

tickers = []


def update_historical_year_open():

    tickers_start_year = []

    tickers_start_year = get_tickers_to_update_year_start()

    for stock in tickers_start_year:

        stock_object = yf.Ticker(stock)

        his = stock_object.history(start=years["2021"],end=years_1["2021"])

        for i, j in his.iterrows():
            for ii, jj in j.iteritems():
                if(ii == "Close"):
                    update_ticker_historical(stock,jj)


def update_date_start_tickers():

    tickers_empty_start = []

    tickers_start = get_tickers_to_update_start()

    # print("Diego")
    # print(tickers_start)

    for stock, date_added in tickers_start.items():

        if date_added != None:

            stock_object = yf.Ticker(stock)

            # date_end = datetime.strptime(date_added, "%y/%m/%d")

            # print("Date added "+date_end)

            date_end = date_added + timedelta(days=7)

            # date_end_string = date_end.strftime("%y/%m/%d")

            # print("Date added "+date_end)

            # print(date_added)
            # print(date_end)

            his = stock_object.history(start=date_added,end=date_end,auto_adjust=False,actions=False)

            price_stock = 0

            # print(his)

            for i, j in his.iterrows():
                for ii, jj in j.iteritems():
                    if(ii == "Close" and price_stock == 0):
                        if(not math.isnan(jj)):
                            price_stock = jj

            # print("stock price "+stock)
            # print(price_stock)

            stock_object = yf.Ticker("^SPX")

            his = stock_object.history(start=date_added,end=date_end)


            spy = 0

            for i, j in his.iterrows():
                for ii, jj in j.iteritems():
                    if(ii == "Close" and spy == 0):
                        if(not math.isnan(jj)):
                            spy = jj

            if price_stock != 0 and spy != 0 and (not math.isnan(price_stock)) and (not math.isnan(spy)):

                update_ticker_start(stock,price_stock,spy)
            else:
                if(math.isnan(price_stock)):
                    stock_object = yf.Ticker(stock)
                    date_end = date_added + timedelta(days=7)
                    print("STOCK HAVING TROUBLE: "+stock)
                    print(date_added)
                    print(date_end)
                    his = stock_object.history(start=date_added,end=date_end)

                    price_stock = 0

                    for i, j in his.iterrows():
                        print(j)
                        for ii, jj in j.iteritems():
                            if(ii == "Close" and price_stock == 0):
                                price_stock = jj


                if(math.isnan(spy)):
                    stock_object = yf.Ticker("^SPX")
                    date_end = date_added + timedelta(days=7)
                    print("STOCK HAVING TROUBLE: "+stock)
                    print(date_added)
                    print(date_end)
                    his = stock_object.history(start=date_added,end=date_end)

                    price_stock = 0

                    for i, j in his.iterrows():
                        print(j)
                        for ii, jj in j.iteritems():
                            if(ii == "Close"):
                                price_stock = jj




def update_ticker_start(ticker,close,spy):


    if LIVE:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("update stocks set `price_when_added`=%s, `index_when_added`=%s where ticker =%s",(float(close),float(spy),str(ticker)))
            conn.commit()

            cursor.close()

            conn.close()
    else:

        with SSHTunnelForwarder(
                (ssh_host, ssh_port),
                ssh_username=ssh_user,
                ssh_pkey=mypkey,
                remote_bind_address=(sql_hostname, sql_port)) as tunnel:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database,port=tunnel.local_bind_port)

            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("update stocks set `price_when_added`=%s, `index_when_added`=%s where ticker =%s",(float(close),float(spy),str(ticker)))
            conn.commit()

            cursor.close()

            conn.close()


def update_ticker_historical(ticker,close):


    if LIVE:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

            cursor = conn.cursor(dictionary=True,buffered=True)


            cursor.execute("update stocks set `year_start`=%s where ticker =%s",(float(close),str(ticker)))
            conn.commit()

            cursor.close()

            conn.close()

    else:

        with SSHTunnelForwarder(
                (ssh_host, ssh_port),
                ssh_username=ssh_user,
                ssh_pkey=mypkey,
                remote_bind_address=(sql_hostname, sql_port)) as tunnel:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database,port=tunnel.local_bind_port)

            cursor = conn.cursor(dictionary=True,buffered=True)


            cursor.execute("update stocks set `year_start`=%s where ticker =%s",(float(close),str(ticker)))
            conn.commit()

            cursor.close()

            conn.close()


def get_tickers_to_update_year_start():

    tickers_to_update_year = []

    if LIVE:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("select * from stocks where year_start is null")
            conn.commit()

            myresult = cursor.fetchall()


            for stock in myresult:

                tickers_to_update_year.append(stock["ticker"])

            cursor.close()

            conn.close()

            return  tickers_to_update_year
    else:

        with SSHTunnelForwarder(
            (ssh_host, ssh_port),
            ssh_username=ssh_user,
            ssh_pkey=mypkey,
            remote_bind_address=(sql_hostname, sql_port)) as tunnel:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database,port=tunnel.local_bind_port)

            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("select * from stocks where year_start is null")
            conn.commit()

            myresult = cursor.fetchall()


            for stock in myresult:

                tickers_to_update_year.append(stock["ticker"])

            cursor.close()

            conn.close()

            return  tickers_to_update_year


def get_tickers_to_update_start():

    tickers_to_update = {}

    if LIVE:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("select * from stocks where price_when_added is null")
            conn.commit()

            myresult = cursor.fetchall()


            for stock in myresult:

                tickers_to_update[stock["ticker"]] = stock["date_added"]

            cursor.close()

            conn.close()

            return  tickers_to_update
    else:

        with SSHTunnelForwarder(
            (ssh_host, ssh_port),
            ssh_username=ssh_user,
            ssh_pkey=mypkey,
            remote_bind_address=(sql_hostname, sql_port)) as tunnel:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database,port=tunnel.local_bind_port)

            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("select * from stocks where price_when_added is null")
            conn.commit()

            myresult = cursor.fetchall()


            for stock in myresult:

                tickers_to_update[stock["ticker"]] = stock["date_added"]

            cursor.close()

            conn.close()

            return  tickers_to_update

def update_ticker(tickers_param):
    today = datetime.today()
    datestamp = today.strftime('%Y-%m-%d %H:%M:%S')

    print(datestamp)


    if LIVE:



        conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

        cursor = conn.cursor(dictionary=True,buffered=True)

        for params in tickers_param:

            print(params["ticker"])
            print(params["change"])


            cursor.execute("update stocks set `marketcap`=%s, `name`=%s, `change`=%s, `last`=%s, `open`=%s, `close`=%s, `high`=%s, `low`=%s, `pe`=%s , `FiftyTwoWeekChange`=%s, `updated_at`=%s where `ticker` = %s",(float(params["marketcap"]),str(params["name"]),float(params["change"]),float(params["last"]),float(params["open"]),float(params["close"]),float(params["high"]),float(params["low"]),float(params["pe"]),float(params["FiftyTwoWeekChange"]),str(datestamp),str(params["ticker"])))

            # print(cursor.statement)

            conn.commit()



        cursor.close()

        conn.close()

    else:
        with SSHTunnelForwarder(
            (ssh_host, ssh_port),
            ssh_username=ssh_user,
            ssh_pkey=mypkey,
            remote_bind_address=(sql_hostname, sql_port)) as tunnel:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database,port=tunnel.local_bind_port)

            cursor = conn.cursor(dictionary=True,buffered=True)

            for params in tickers_param:

                print(params["ticker"])
                print(params["change"])


                cursor.execute("update stocks set `name`=%s, `change`=%s, `last`=%s, `open`=%s, `close`=%s, `high`=%s, `low`=%s, `pe`=%s , `FiftyTwoWeekChange`=%s, `updated_at`=%s where `ticker` = %s",(str(params["name"]),float(params["change"]),float(params["last"]),float(params["open"]),float(params["close"]),float(params["high"]),float(params["low"]),float(params["pe"]),float(params["FiftyTwoWeekChange"]),str(datestamp),str(params["ticker"])))
                conn.commit()

                # print(cursor._last_executed)

            cursor.close()

            conn.close()

def get_tickers():

    if LIVE:

        conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

        cursor = conn.cursor(dictionary=True)
        # cursor.execute("Select * from stocks where active = 1 and ticker = 'SOFI'")
        cursor.execute("Select * from stocks where active = 1")

        rows = cursor.fetchall()

        for row in rows:
            print(row["ticker"])
            tickers.append(row["ticker"])

        conn.close()

    else:

        with SSHTunnelForwarder(
            (ssh_host, ssh_port),
            ssh_username=ssh_user,
            ssh_pkey=mypkey,
            remote_bind_address=(sql_hostname, sql_port)) as tunnel:

            conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database,port=tunnel.local_bind_port)

            cursor = conn.cursor(dictionary=True)
            # cursor.execute("Select * from stocks where active = 1 and ticker = 'SOFI'")
            cursor.execute("Select * from stocks where active = 1")

            rows = cursor.fetchall()

            for row in rows:
                # print(row["ticker"])
                tickers.append(row["ticker"])
            conn.close()


get_tickers()

DEBUG = True


spy = yf.Ticker("^SPX")

spy_object = spy.info


def poll_and_update_tickers():

    tickers_to_update = []

    for stock_object_ in tickers:

        stock_object = yf.Ticker(stock_object_)

        try:
            if stock_object != None:
                stock = stock_object.info

                print("---------------------------------------------------------")
                print(stock_object.info["symbol"])
                print("---------------------------------------------------------")
                print(stock)


                params = {}

                stock_previous_close = 0
                if("regularMarketPreviousClose" in stock):
                    stock_previous_close = stock["regularMarketPreviousClose"]
                    if(stock_previous_close == None or stock_previous_close == 0):
                        if("previousClose" in stock):
                            stock_previous_close = stock["previousClose"]

                change = 0

                params["last"] = 0

                if "bid" in stock and stock["bid"] != None:
                        params["last"] = stock["bid"]

                if(params["last"] == None or params["last"] == 0):
                    params["last"] = stock["regularMarketPrice"]






                params["previousClose"] = stock_previous_close
                params["ticker"] = stock["symbol"]
                params["open"] = stock["open"]
                params["marketcap"] = stock["marketCap"]
                if(params["marketcap"] == None):
                    params["marketcap"] = 0
                if(params["open"] == None):
                    params["open"] = stock_previous_close
                params["close"] = stock["previousClose"]
                if(params["close"] == None):
                    params["close"] = stock["regularMarketPreviousClose"]
                params["high"] = stock["dayHigh"]
                if(params["high"] == None):
                    params["high"] = stock["regularMarketDayHigh"]
                params["low"] = stock["dayLow"]
                if(params["high"] == None):
                    params["high"] = stock["regularMarketDayLow"]
                params["index_price"] = spy_object["bid"]
                if(stock["forwardPE"] == None):
                    params["pe"] = 0
                else:
                    params["pe"] = stock["forwardPE"]
                params["name"] = stock["shortName"]

                if stock_previous_close != 0:
                    change = (params["last"] - stock_previous_close) / stock_previous_close
                else:
                    change = 0

                params["change"] = "{:4.4f}".format(change)

                if("52WeekChange" in stock and stock["52WeekChange"] != None and stock["52WeekChange"] != 0):
                    params["FiftyTwoWeekChange"] = stock["52WeekChange"]
                else:
                    if("FiftyTwoWeekChange" in stock and stock["FiftyTwoWeekChange"] != None):
                        params["FiftyTwoWeekChange"] = stock["FiftyTwoWeekChange"]
                    else:
                        params["FiftyTwoWeekChange"] = 0


                print(params)

                tickers_to_update.append(params)


        except:
            print("An exception occurred")
            traceback.print_exc()
            print(stock_object)
            print(stock_object)
            print("Skipped ")

    update_ticker(tickers_to_update)



# update_ticker(tickers_to_update)
# update_historical_year_open()
# update_date_start_tickers()
poll_and_update_tickers()





# msft = yf.Ticker("msft")
# print(msft.info)






# {'zip': '98052-6399', 'sector': 'Technology', 'fullTimeEmployees': 163000, 'longBusinessSummary': 'Microsoft Corporation develops, licenses, and supports software, services, devices, and solutions worldwide. Its Productivity and Business Processes segment offers Office, Exchange, SharePoint, Microsoft Teams, Office 365 Security and Compliance, and Skype for Business, as well as related Client Access Licenses (CAL); Skype, Outlook.com, OneDrive, and LinkedIn; and Dynamics 365, a set of cloud-based and on-premises business solutions for small and medium businesses, large organizations, and divisions of enterprises. Its Intelligent Cloud segment licenses SQL and Windows Servers, Visual Studio, System Center, and related CALs; GitHub that provides a collaboration platform and code hosting service for developers; and Azure, a cloud platform. It also offers support services and Microsoft consulting services to assist customers in developing, deploying, and managing Microsoft server and desktop solutions; and training and certification to developers and IT professionals on various Microsoft products. Its More Personal Computing segment provides Windows original equipment manufacturer (OEM) licensing and other non-volume licensing of the Windows operating system; Windows Commercial, such as volume licensing of the Windows operating system, Windows cloud services, and other Windows commercial offerings; patent licensing; Windows Internet of Things; and MSN advertising. It also offers Surface, PC accessories, PCs, tablets, gaming and entertainment consoles, and other devices; Gaming, including Xbox hardware, and Xbox content and services; video games and third-party video game royalties; and Search, including Bing and Microsoft advertising. It sells its products through OEMs, distributors, and resellers; and directly through digital marketplaces, online stores, and retail stores. It has a strategic collaboration with DXC Technology and Dynatrace, Inc. The company was founded in 1975 and is headquartered in Redmond, Washington.', 'city': 'Redmond', 'phone': '425-882-8080', 'state': 'WA', 'country': 'United States', 'companyOfficers': [], 'website': 'http://www.microsoft.com', 'maxAge': 1, 'address1': 'One Microsoft Way', 'industry': 'Software—Infrastructure', 'previousClose': 261.97, 'regularMarketOpen': 256.078, 'twoHundredDayAverage': 228.32088, 'trailingAnnualDividendYield': 0.007787151, 'payoutRatio': 0.31149998, 'volume24Hr': None, 'regularMarketDayHigh': 256.5399, 'navPrice': None, 'averageDailyVolume10Day': 23481314, 'totalAssets': None, 'regularMarketPreviousClose': 261.97, 'fiftyDayAverage': 245.66714, 'trailingAnnualDividendRate': 2.04, 'open': 256.078, 'toCurrency': None, 'averageVolume10days': 23481314, 'expireDate': None, 'yield': None, 'algorithm': None, 'dividendRate': 2.24, 'exDividendDate': 1621382400, 'beta': None, 'circulatingSupply': None, 'startDate': None, 'regularMarketDayLow': 252.95, 'priceHint': 2, 'currency': 'USD', 'trailingPE': 34.75334, 'regularMarketVolume': 39831941, 'lastMarket': None, 'maxSupply': None, 'openInterest': None, 'marketCap': 1923416981504, 'volumeAllCurrencies': None, 'strikePrice': None, 'averageVolume': 28327958, 'priceToSalesTrailing12Months': 12.023685, 'dayLow': 252.95, 'ask': 255.33, 'ytdReturn': None, 'askSize': 1400, 'volume': 39831941, 'fiftyTwoWeekHigh': 263.19, 'forwardPE': 31.48395, 'fromCurrency': None, 'fiveYearAvgDividendYield': 1.66, 'fiftyTwoWeekLow': 171.88, 'bid': 255.41, 'tradeable': False, 'dividendYield': 0.0086, 'bidSize': 1100, 'dayHigh': 256.5399, 'exchange': 'NMS', 'shortName': 'Microsoft Corporation', 'longName': 'Microsoft Corporation', 'exchangeTimezoneName': 'America/New_York', 'exchangeTimezoneShortName': 'EDT', 'isEsgPopulated': False, 'gmtOffSetMilliseconds': '-14400000', 'quoteType': 'EQUITY', 'symbol': 'MSFT', 'messageBoardId': 'finmb_21835', 'market': 'us_market', 'annualHoldingsTurnover': None, 'enterpriseToRevenue': 11.971, 'beta3Year': None, 'profitMargins': 0.35016, 'enterpriseToEbitda': 25.338, '52WeekChange': 0.47646964, 'morningStarRiskRating': None, 'forwardEps': 8.1, 'revenueQuarterlyGrowth': None, 'sharesOutstanding': 7542219776, 'fundInceptionDate': None, 'annualReportExpenseRatio': None, 'bookValue': 17.853, 'sharesShort': 61156879, 'sharesPercentSharesOut': 0.0081, 'fundFamily': None, 'lastFiscalYearEnd': 1593475200, 'heldPercentInstitutions': 0.72226995, 'netIncomeToCommon': 56014999552, 'trailingEps': 7.338, 'lastDividendValue': 0.56, 'SandP52WeekChange': 0.42429185, 'priceToBook': 14.284433, 'heldPercentInsiders': 0.00071999995, 'nextFiscalYearEnd': 1656547200, 'mostRecentQuarter': 1617148800, 'shortRatio': 2.11, 'sharesShortPreviousMonthDate': 1615766400, 'floatShares': 7420634458, 'enterpriseValue': 1914969522176, 'threeYearAverageReturn': None, 'lastSplitDate': 1045526400, 'lastSplitFactor': '2:1', 'legalType': None, 'lastDividendDate': 1613520000, 'morningStarOverallRating': None, 'earningsQuarterlyGrowth': 0.438, 'dateShortInterest': 1618444800, 'pegRatio': 1.87, 'lastCapGain': None, 'shortPercentOfFloat': 0.0081, 'sharesShortPriorMonth': 49574419, 'impliedSharesOutstanding': None, 'category': None, 'fiveYearAverageReturn': None, 'regularMarketPrice': 256.078, 'logo_url': 'https://logo.clearbit.com/microsoft.com'}
# Diego-Crypto:python diego$
