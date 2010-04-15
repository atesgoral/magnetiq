import os
import re

template_dir = "../.templates"
template_file = "page.html"
content_dir = "../.content"
output_dir = "../pages"

f = open(os.path.join(template_dir, template_file), "r")
template = f.read();
f.close()

print "Loaded template " + template_file

for page in os.listdir(content_dir):
	content_file = os.path.join(content_dir, page)
	
	if os.path.isfile(content_file):
		# Load content
		f = open(content_file, "r")
		content_html = f.read()
		f.close()

		print "\tCompiling " + page

		# Extract info
		info = re.match("<h2>(?P<title>.+)</h2>", content_html)

		if info:
			print "\t\tExtracted info:"
			print "\t\t\tTitle: " + info.group("title")

		# Replace tokens
		print "\t\tReplacing tokens"
		page_html = template.format(
			Title = info.group("title") if info else "",
			Content = content_html)

		# Add permalink
		print "\t\tAdding permalink"
		page_html = re.sub("(<h2>.+</h2>)",
			r'<a href="#" rel="bookmark" title="Permanent Link">\1</a>',
			page_html)
			
		# Break out <pre>s
		# print "\t\tBreaking out <pre>s"
		# page_html = re.sub("(?m)(<pre[^<]+</pre>)",
		#	r'</div><div class="clear"></div><div class="three fluid column">\1</div><div class="two fluid column post_text">',
		#	page_html)

		print "\tWriting " + page
            
		f = open(os.path.join(output_dir, page), "w")
		f.write(page_html)
		f.close

print "Done."
