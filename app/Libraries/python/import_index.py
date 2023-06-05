import bs4 as bs
import pickle
import requests
import mysql.connector
from mysql.connector import errorcode
import yfinance as yf

# html = requests.get('http://en.wikipedia.org/wiki/List_of_S%26P_500_companies')
html = requests.get('https://en.wikipedia.org/wiki/Nasdaq-100')
soup = bs.BeautifulSoup(html.text, 'lxml')
table = soup.find('table', {'id': 'constituents'})



tickers = []

sql_hostname = '68.235.35.131'
sql_username = 'diegoczul_stocks'
sql_password = 'Cool**88'
sql_main_database = 'diegoczul_stocks'
sql_port = 3306


for row in table.findAll('tr')[1:]:
        ticker = row.findAll('td')[0].text
        name = row.findAll('td')[1].text
        ticker = ticker[:-1]
        logo = ""
        # print(name+" "+ticker)

        conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

        cursor = conn.cursor(dictionary=True,buffered=True)

        conn = mysql.connector.connect(host=sql_hostname,user=sql_username,passwd=sql_password,db=sql_main_database)

        cursor = conn.cursor(dictionary=True,buffered=True)

        cursor.execute("select * from instrument_groups where name = %s",(ticker,))

        conn.commit()

        myresult = cursor.fetchall()

        tick = yf.Ticker(ticker)

        info_stock = tick.info

        print(info_stock)

        if "bid" in info_stock and info_stock["logo_url"] != None:
            logo = info_stock["logo_url"]
        else:
            logo = ""



        if(len(myresult) == 0):

            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("insert into instrument_groups (name,context_name) values (%s, %s)",(ticker,name))
            conn.commit()

            cursor.close()
        else:
            cursor = conn.cursor(dictionary=True,buffered=True)

            cursor.execute("update instrument_groups set logo = %s where name = %s",(logo,ticker))

            conn.commit()

            cursor.close()

        conn.close()


print(tickers)

with open("sp500tickers.pickle", "wb") as f:
        pickle.dump(tickers, f)
