import os
import re

f = open("page.html", "r")
template = f.read();
f.close()

for page in os.listdir(".content"):
	# Load content
	f = open(".content/" + page, "r")
	content_html = f.read()
	f.close()
	
	# Extract info
	info = re.match("<h2>(?P<title>.+)</h2>", content_html)
	
	# Add permalink
	content_html = re.sub("(<h2>.+</h2>)",
		r'<a href="#" title="Permanent Link">\1</a>', content_html)
	
	# Insert info
	page_html = template.format(
		Title = info.group("title"),
		Content = content_html)
	
	f = open("pages/" + page, "w")
	f.write(page_html)
	f.close
