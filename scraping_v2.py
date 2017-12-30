#!python3
import codecs
import sys,json
from bs4 import BeautifulSoup
from bs4 import NavigableString
from urllib.request import urlopen

#好きな入力を選んでね(必要に応じてコメントアウトして無効にしたり有効にしてね)
#↓コマンドプロンプトにURL直書き
#url = sys.argv[1]
#↓ここにべた書き
#url = "http://localhost/train.html"
#↓プログラムと同じ場所にurl.txtを作ってそのファイル内にurlを書く
f = open('url.txt','r')
url = f.read()


#タグ内にある文章のみを抽出するプログラム
#そのタグ内にあるタグは抹消する．(不必要な改行は正規表現で消去)
def printText(tags):
    for tag in tags:
        if tag.__class__ == NavigableString:
            tag = tag.replace('\n','')
            tag = tag.replace(' ','')
            return tag

#入力されたものがURLであるかを確認する
#違う場合はそのまま終了
try:
    html = urlopen(url)
    bsObj = BeautifulSoup(html,"html.parser")
except:
    print('URL is not correct!')
    sys.exit()

t_list = []

try: 

    for i,child in enumerate(bsObj.findAll("table",{"class":"route_list_table"})):
        print("route",i)

        #出発と到着の時刻と運賃が詰まったものの抽出(きれいにしta)
        depert_time = child.findAll("td",{"class":"icon_txt01"})
        for d_time in depert_time:
            d_time = "".join(d_time.strings)
            d_time = str(d_time).replace('\n','')
            d_time = d_time.replace(' ','')
            print(d_time)
        
        print("---")

        #出発到着駅名の抽出(がんばった)
        station_name = child.findAll("td",{"class":"route_start_txt"})
        for name in station_name:
            name2 = "".join(name.strings)
            name2 = str(name2).replace('\n','')
            name2 = name2.replace(' ','')
            print(name2)

        print("---")

        #乗り換え駅がある場合その駅名を抽出(きれいにしたよ)　表示具体例：品川
        for transit_name in child.findAll("td",{"class":"place_name"}):
            transit_name = printText(transit_name)
            print(transit_name)

        print("---")

        #乗っている間の時間と何駅先か抽出(きれいにしたよ)　表示具体例：16分/2駅
        for route_time in child.findAll("td",{"class":"riding_tite_txt"}):
            train_time = printText(route_time)
            print(train_time)

        print("---")

        #何線か抽出(きれいにしたよ)　表示具体例：ＪＲ東海道本線(上野東京ライン)・高崎行
        for route_line in child.findAll("td",{"class":"useline_name"}):
            line_name = printText(route_line)
            print(line_name)
        
except:
    print('scraping miss!')
    sys.exit()
