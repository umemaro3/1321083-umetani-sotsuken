# -*- coding: utf-8 -*-
import requests

url = 'http://search.yahoo.co.jp/search'
params = {'p':'python',
          'search.x':'1',
          'fr':'top_gal_sa',
          'tid':'top_gal_sa',
          'ei':'UTF-8',
          'ap':'',
          'op':'',
          'afs':'',}

def send_request():
    response = requests.get(url, params)
    print(response.text)

if __name__ == '__main__':
    send_request