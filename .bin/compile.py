import os
import re

template_dir = "../.templates"
pages_dir = "../pages"

template_filename = "page.html"
content_filename = ".index.html"
page_filename = "index.html"

template_path = os.path.join(template_dir, template_filename)

f = open(template_path, "r")
template = f.read();
f.close()

print "Loaded template " + template_path

for dir in os.listdir(pages_dir):
	page_dir = os.path.join(pages_dir, dir)

	if not os.path.isdir(page_dir) or dir.startswith("."):
		continue

	print "\tCompiling " + page_dir

	content_path = os.path.join(page_dir, content_filename)

	# Load content
	f = open(content_path, "r")
	content_html = f.read()
	f.close()

	print "Loaded content " + content_path

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

	page_path = os.path.join(page_dir, page_filename)
	
	print "\tWriting " + page_path
		
	f = open(page_path, "w")
	f.write(page_html)
	f.close

print "Done."
