from bs4 import BeautifulSoup
import requests
import re
import time
import json
import collections
import threading
from datetime import datetime
from collections import Counter

urlfilterRegex = re.compile('.*members')
sitenameRegex = re.compile("(.*) Members")
memberRegex = re.compile(".* of (\d+) registered")
userurlfilterRegex = re.compile('\/users\/.*')

now_date = datetime.now().strftime('%Y-%m-%dT%H:%M:%S.%f')

def crawluser(skillslock, skills, prof ):
    ah = prof.find('a', href=userurlfilterRegex)
    profileUrl = "http://globalgamejam.org" + ah['href']
    profilepage = s.get(profileUrl)
    profilesoup = BeautifulSoup(profilepage.text, "html.parser")
    myskills = profilesoup.find('ul', class_='textformatter-list')
    if myskills:
        slist = list(o.text for o in myskills.contents if hasattr(o, 'text'))
        with skillslock:
            skills += Counter(slist)

def crawl(sitelock,sites, idx, link):
     siteurl = "http://globalgamejam.org" + link['href']
     # print(siteurl)
     sitepage = s.get(siteurl)
     sitesoup = BeautifulSoup(sitepage.text, "html.parser")
     sitename = sitenameRegex.match(sitesoup.find('h1').text).group(1)
     gg = sitesoup.find('div', class_='view-header').text
     ff = memberRegex.match(gg, re.DOTALL)
     membercount = ff.group(1)
     print(str(idx) + '/' + str(len(sitelinks)) + ',\t' + sitename + " ,\t " + membercount)


     skills = Counter()

     skilslock = threading.Lock()
     profiles = sitesoup.find_all('div', class_='user-profile-fields')
     threads = [threading.Thread(target=crawluser, args=(skilslock,skills, link)) for link in profiles]
     for t in threads:
         t.start()
     for t in threads:
         t.join()

     site = dict()
     site['skills'] = dict(skills)
     site['jamsite'] = sitename
     site['url'] = siteurl[:-8]
     site['jammers'] = [{'date':now_date,'count':int(membercount)}]
     with sitelock:
          sites.append(site)
     #print(str(idx) + '/' + str(len(sitelinks)) + ',\t' + sitename + " done")

start = time.time()

s = requests.Session()
url="https://globalgamejam.org/2019/jam-sites?title=&country=GB"
page = s.get(url).text
soup = BeautifulSoup(page, "html.parser")

sites = []
sitelinks = soup.find_all('a', href=urlfilterRegex)

siteslock = threading.Lock()
threads = [threading.Thread(target=crawl, args=(siteslock,sites, idx, link)) for idx, link in enumerate(sitelinks)]
#threads = [threading.Thread(target=crawl, args=(siteslock,sites, 0, sitelinks[34]))]

print("idx/total, \tsitename, \tmembercount")

for t in threads:
     t.start()

for t in threads:
     t.join()

#print(sites)
#print(json.dumps(sites))
newdata = json.dumps(sites)
print(newdata)
headers = {'Content-type': 'application/x-www-form-urlencoded', 'Accept': 'text/plain'}
#response = requests.post('http://games.soc.napier.ac.uk/ggj/ggjrank.php', data={'newdata':newdata}, headers=headers)
print("Upload Responce code:")
print(response)
print("Upload Responce text:")
print(response.text)
end = time.time()    
print("Time Taken:",  round(end - start))

