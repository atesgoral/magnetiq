import os
import re

template_dir = "../.templates"
pages_dir = "../pages_tumblr"

template_filename = "tumblr.html"
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
        
    # Processing blocks
    def processBlocks(s):
        while True:
            m = re.search("(?ms)\{block:(?P<name>.*)\}(?P<contents>.*?)\{/block:(?P=name)\}", s)
            if not m:
                break
            print m.group("name")
            s = s[:m.start()] + m.group("contents") + s[m.end():]
        return s
    
    template = processBlocks(template)

    print template
    
    #template = re.sub("(?ms)\{block:(?P<name>.*)\}(.*?)\{/block:(?P=name)\}",
    #	"BLOCK",
    #	template);

    template = re.sub("(?m)\{text:(?P<name>.*)\}",
        "TEXT",
        template);
        
    # Replace tokens
    print "\t\tReplacing tokens"
    page_html = template.format(
        ExtraClasses = "" if error_page else "disqus",
        Description = "by Ates Goral",
        DisqusShortname = "magnetiq",
        Permalink = "/pages/" + dir,
        Favicon = "favicon.png",
        RSS = "rss.xml",
        CustomCSS = "",
        Title = info.group("title"),
        Body = "body",
        PostTitle = "Hi",
        TimeAgo = "1 hr ago",
        ShortMonth = "Jan",
        DayOfMonth = "1",
        Year = 2010,
        TagURLChrono = "http://example.com",
        Tag = "hey",
        NoteCountWithLabel = "56",
        PostNotes = "",
        PreviousPost = "",
        NextPost = "",
        PreviousPage = "",
        NextPage = "",
        CurrentPage = 1,
        TotalPages = 3,
        Content = content_html)

    page_path = os.path.join(page_dir, page_filename)
    
    print "\tWriting " + page_path
        
    f = open(page_path, "w")
    f.write(page_html)
    f.close

print "Done."
