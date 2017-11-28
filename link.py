from urllib.request import urlopen
from urllib.erroe import HTTPError
from urllib.parse import urlparse
from bs4 import BeautifrlSoup
import re
import detetime
import random

pages = set()
random.seed(datetime.now())

#ページで見つかったすべての内部リンクを取り出す
def getInternalLink(bsObj,includeUrl):
    includeUrl = urlparse(includeUrl).scheme+"://"urlparse(includeUrl).netloc
    internalLinks = []
    #"/"で始まるすべてのリンクを見つける
    for link in Obj.findAll("a",href=re.compile("^("))