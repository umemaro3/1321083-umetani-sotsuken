# ics.mediaをスクレイピング
import os
from urllib.request import urlopen
from bs4 import BeautifulSoup

URL = "https://news.yahoo.co.jp/"
with urlopen(URL) as res:
    html = res.read().decode("utf-8")

soup = BeautifulSoup(html, "html.parser")

# 新着記事の画像（とタイトル）を取得
topics = soup.select(".topicsContainer")[0].nextSibling
topics_urls = topics.select(".thumb img")
topics_ttls = topics.select(".entryTitle a")
img_urls = [e["src"] for e in topics_urls]
img_ttls = [e.string for e in topics_ttls]

"""
# 相対パスの場合、絶対パスに変換
# リスト内包表記、三項演算子
img_urls = [u if u.find("http") == 0 else URL + u for u in img_urls]
"""

# 保存
img_dir = "images"
if not os.path.exists(img_dir):
    os.mkdir(img_dir)

for i,url in enumerate(img_urls):
    print("記事"+str(1+i), img_ttls[i])
    print(url)
    with urlopen(url) as res:
        img = res.read()
        with open(img_dir + "/entry_image%d.png" % (i+1), "wb") as f:
            f.write(img)