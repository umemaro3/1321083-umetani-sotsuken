from urllib.request import urlopen
from bs4 import BeautifulSoup
from pprint import pprint

URL = 'file:///C:/sotsuken/EkispertWebService-GUI-LightEdition-f263e02/index.html' 
with urlopen(URL) as res:
  html = res.read().decode("utf-8")

soup = BeautifulSoup(html, 'html.parser')

titles = soup.select('.ttl a') # domを取得
titles = [t.contents[0] for t in titles] # テキストを取得
pprint(titles)