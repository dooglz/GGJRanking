from bs4 import BeautifulSoup
import requests
import re
import time
import json
import collections

start = time.time()

class Site:
     def __init__(self, name, url, count):
         self.name = name
         self.url = url
         self.count = count
         
s = requests.Session()
url="http://globalgamejam.org/2017/jam-sites?title=&country=GB&locality="
page = s.get(url).text
soup = BeautifulSoup(page, "html.parser")

urlfilterRegex = re.compile('.*members')
sitenameRegex = re.compile("(.*) Members")
memberRegex = re.compile(".* of (\d+) registered")

sites = []
for link in soup.find_all('a', href=urlfilterRegex):
    siteurl = "http://globalgamejam.org" + link['href']
    #print(siteurl)
    sitepage =  s.get(siteurl)
    sitesoup = BeautifulSoup(sitepage.text, "html.parser")
    sitename = sitenameRegex.match(sitesoup.find('h1').text).group(1)
    gg = sitesoup.find('div', class_='view-header').text
    ff = memberRegex.match(gg,re.DOTALL)
    membercount = ff.group(1)
    #print(sitename + " ,\t " + membercount)
    sites.append({'jamsite' : sitename, 'url' : siteurl[:-8], 'jammers' : int(membercount)})

print(sites)
print(json.dumps(sites))
newdata = {'newdata':json.dumps(sites)}
print(newdata)
headers = {'Content-type': 'application/x-www-form-urlencoded', 'Accept': 'text/plain'}
response = requests.post('http://games.soc.napier.ac.uk/ggjrank.php', data=newdata, headers=headers)
end = time.time()    
print(end - start)

