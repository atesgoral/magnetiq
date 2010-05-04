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
	
	error_page = dir == "404"

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

	if not error_page:
		# Add permalink
		print "\t\tAdding permalink"
		content_html = re.sub("<h2>.+</h2>",
			r'<h2><a href="/pages/' + dir
				+ '" rel="bookmark" title="Permanent Link: '
				+ info.group("title") + '">' + info.group("title")
				+ '</a></h2>',
			content_html)
		
	# Replace tokens
	print "\t\tReplacing tokens"
	page_html = template.format(
		ExtraClasses = " error" if error_page else " disqus",
		Permalink = "/pages/" + dir + "/",
		Title = info.group("title"),
		Content = content_html,
		DisqusShortname = "magnetiq",
		RSS = "http://feeds.feedburner.com/magnetiq_rss",
		Favicon = "http://s.magnetiq.com/i/icon_16.png")

	page_path = os.path.join(page_dir, page_filename)
	
	print "\tWriting " + page_path
		
	f = open(page_path, "w")
	f.write(page_html)
	f.close

print "Done."
