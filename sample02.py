#
from urllib.request import urlopen
from bs4 import BeautifulSoup
from pprint import pprint
import csv
import re
　
tenki = []
URL = "http://weather.livedoor.com/forecast/rss/area/130010.xml"

with urlopen(URL) as res: 
  html = res.read().decode("utf-8")

soup = BeautifulSoup(html, "html.parser")
for item in soup.find_all("item"):
    title = item.find("title").string
    if title.find("[ PR ]") == -1: # ゴミ削除
        text = re.sub(r'\[?.+\]\s', '', title) # []内を削除
        result = text.split(' - ')
        tenki.append(result)
pprint(tenki)

with open('weahter.csv','w',newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['city','status','max','date'])
    writer.writerows(tenki)