from bs4 import BeautifulSoup
import requests
import re
import time
import json
import collections

start = time.time()


s = requests.Session()
url="http://globalgamejam.org/2017/jam-sites?title=&country=GB&locality="
page = s.get(url).text
soup = BeautifulSoup(page, "html.parser")

urlfilterRegex = re.compile('.*members')
sitenameRegex = re.compile("(.*) Members")
memberRegex = re.compile(".* of (\d+) registered")
userurlfilterRegex = re.compile('\/users\/.*')

sites = []
sitelinks = soup.find_all('a', href=urlfilterRegex)
idx = 1;
for link in sitelinks:
     siteurl = "http://globalgamejam.org" + link['href']
     #print(siteurl)
     sitepage =  s.get(siteurl)
     sitesoup = BeautifulSoup(sitepage.text, "html.parser")
     sitename = sitenameRegex.match(sitesoup.find('h1').text).group(1)
     gg = sitesoup.find('div', class_='view-header').text
     ff = memberRegex.match(gg,re.DOTALL)
     membercount = ff.group(1)
     print(str (idx)+'/'+str (len(sitelinks)) + ',\t'+sitename + " ,\t " + membercount)
     idx += 1

     s_2d_art = 0
     s_3d_art = 0
     s_animation = 0
     s_audio = 0
     s_game_design = 0
     s_game_development = 0
     s_hardware = 0
     s_marketing = 0
     s_music = 0
     s_programming = 0
     s_project_management = 0
     s_quality_assurance = 0
     s_story_and_narrative = 0
     s_web_design = 0
     s_writing =0
     
     for prof in sitesoup.find_all('div', class_='user-profile-fields'):
          ah = prof.find('a', href=userurlfilterRegex)
          profileUrl = "http://globalgamejam.org" + ah['href']
          #print(profileUrl)
          profilepage =  s.get(profileUrl)
          profilesoup = BeautifulSoup(profilepage.text, "html.parser")
          myskills = profilesoup.find('span', class_='textformatter-list')
          if myskills:
               slist = myskills.contents[0].split(', ')
               if '2d art' in slist:
                    s_2d_art+= 1
               if '3d art' in slist:
                    s_3d_art+= 1
               if 'animation' in slist:
                    s_animation+= 1
               if 'audio' in slist:
                    s_audio+= 1
               if 'game design' in slist:
                    s_game_design+= 1
               if 'game development' in slist:
                    s_game_development+= 1
               if 'hardware' in slist:
                    s_hardware+= 1
               if 'marketing' in slist:
                    s_marketing+= 1
               if 'music' in slist:
                    s_music+= 1
               if 'programming' in slist:
                    s_programming+= 1
               if 'project management' in slist:
                    s_project_management+= 1
               if 'quality assurance' in slist:
                    s_quality_assurance+= 1
               if 'story and narrative' in slist:
                    s_story_and_narrative+= 1
               if 'web design' in slist:
                    s_web_design+= 1
               if 'writing' in slist:
                    s_writing+= 1
                    
     sites.append({'jamsite' : sitename, 'url' : siteurl[:-8], 'jammers' : int(membercount),
                   's_2d_art': s_2d_art, 's_3d_art':s_3d_art, 's_animation':s_animation, 's_audio':s_audio,
                   's_game_design':s_game_design, 's_game_development':s_game_development, 's_hardware':s_hardware,
                   's_marketing':s_marketing,'s_music':s_music,'s_programming':s_programming,
                   's_project_management':s_project_management,'s_quality_assurance':s_quality_assurance,
                   's_story_and_narrative':s_story_and_narrative,'s_web_design':s_web_design,'s_writing':s_writing})

print(sites)
print(json.dumps(sites))
newdata = {'newdata':json.dumps(sites)}
print(newdata)
headers = {'Content-type': 'application/x-www-form-urlencoded', 'Accept': 'text/plain'}
response = requests.post('http://games.soc.napier.ac.uk/ggjrank.php', data=newdata, headers=headers)
end = time.time()    
print(end - start)

