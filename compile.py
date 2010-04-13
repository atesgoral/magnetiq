import os
import re

template_dir = ".templates"
content_dir = ".content"
output_dir = "pages"

f = open(os.path.join(template_dir, "page.html"), "r")
template = f.read();
f.close()

for page in os.listdir(content_dir):
	content_file = os.path.join(content_dir, page)
	
	if os.path.isfile(content_file):
		# Load content
		f = open(content_file, "r")
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
		
		f = open(os.path.join(output_dir, page), "w")
		f.write(page_html)
		f.close
