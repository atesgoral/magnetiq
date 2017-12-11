<h2>Freeware</h2>

<p>There are a couple of freeware tools that I wrote over the years. Some of them are outdated/obsolete, but here they are anyway:</p>

<ul>
    <li><a href="#acb2xml">ACB2XML</a> - Convert Photoshop Color Books to XML</li>
    <li><a href="#burrito">Burrito</a> - FTP-to-POP3 protocol translator</li>
    <li><a href="#eresq">E-Res-Q</a> - Simple, portable POP3 client</li>
    <li><a href="#winresq">Win-Res-Q</a> - Reveal hidden windows</li>
</ul>

<h3 id="acb2xml">ACB2XML</h3>

<p>Here's a freeware tool that I had written back in 2003, shortly after reverse-engineering the <a href="/pages/acb-spec">Adobe Color Book Format</a>. This command-line Windows application extracts color data from color book files and generates XML. Once the color data is safely in XML domain, the rest is up to your imagination...</p>

<p class="download">Download <a href="/downloads/acb2xml20.zip">acb2xml20.zip</a> (30 KB)</p>

<p>If you don't already know what color books are, there's the <em>Custom Colors</em> dialog which appears when you click the <em>Custom</em> button on the <em>Color Picker</em> dialog. There, you can pick colors from a variety of color books which represent standard color catalogs by organizations like <a href="http://pantone.com/">Pantone</a>, <a href="http://toyocolor.com/">Toyo</a>, <a href="http://trumatch.com/">Trumatch</a>, etc. The color book data is kept in files under the <em>Presets/Color Books</em> folder of your Photoshop installation. On Windows, they have the <em>.acb</em> extension.</p>

<p class="center"><img src="i/custom_colors.jpg" width="400" height="279" alt="Custom Colors Dialog"/></p>
<p class="center"><small>Custom Colors Dialog</small></p>

<p>To extract data, just pass in the filename of a color book. The resulting XML will go to standard output. To capture to a file, just redirect the output:</p>

<pre>acb2xml focoltone.acb &gt; focoltone.xml</pre>

<p>For long filenames or filenames with spaces, quote the filenames:</p>

<pre>acb2xml "PANTONE process coated.acb" > "PANTONE process coated.xml"</pre>

<p>The resulting output will look something like this:</p>

<pre class="brush: xml">
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;
&lt;color-book version=&quot;1.0&quot; xmlns=&quot;http://magnetiq.com/ns/2007/05/colorbook&quot;&gt;
    &lt;version&gt;1&lt;/version&gt;
    &lt;id&gt;0bb9&lt;/id&gt;
    &lt;title&gt;&lt;![CDATA[$$$/colorbook/FOCOLTONE/title=FOCOLTONE]]&gt;&lt;/title&gt;
    &lt;prefix&gt;&lt;![CDATA[$$$/colorbook/FOCOLTONE/prefix=FOCOLTONE ]]&gt;&lt;/prefix&gt;
    &lt;postfix&gt;&lt;![CDATA[$$$/colorbook/FOCOLTONE/postfix=]]&gt;&lt;/postfix&gt;
    &lt;description&gt;&lt;![CDATA[$$$/colorbook/FOCOLTONE/description=]]&gt;&lt;/description&gt;
    &lt;colors&gt;860&lt;/colors&gt;
    &lt;page-size&gt;5&lt;/page-size&gt;
    &lt;page-offset&gt;2&lt;/page-offset&gt;
    &lt;color-space&gt;CMYK&lt;/color-space&gt;
    &lt;color&gt;
        &lt;name&gt;1070&lt;/name&gt;
        &lt;alias&gt;&lt;![CDATA[1070  ]]&gt;&lt;/alias&gt;
        &lt;cyan&gt;100&lt;/cyan&gt;
        &lt;magenta&gt;0&lt;/magenta&gt;
        &lt;yellow&gt;0&lt;/yellow&gt;
        &lt;black&gt;0&lt;/black&gt;
    &lt;/color&gt;
    &lt;color&gt;
        &lt;name&gt;1071&lt;/name&gt;
        &lt;alias&gt;&lt;![CDATA[1071  ]]&gt;&lt;/alias&gt;
        &lt;cyan&gt;0&lt;/cyan&gt;
        &lt;magenta&gt;100&lt;/magenta&gt;
        &lt;yellow&gt;0&lt;/yellow&gt;
        &lt;black&gt;0&lt;/black&gt;
    &lt;/color&gt;
    ...
</pre>

<p>ACB2XML currently supports <a href="http://en.wikipedia.org/wiki/Cmyk">CMYK</a>, <a href="http://en.wikipedia.org/wiki/Rgb">RGB</a> and <a href="http://en.wikipedia.org/wiki/Lab_color_space">Lab</a> color spaces.</p>

<h4>Known Issues</h4>

<ul>
<li>Some textual information is enclosed in CDATA blocks, instead of doing proper XML escaping. While this has no harm whatsoever, I may fix it in the future.</li>
</ul>

<h4>Legalese</h4>

<p><em>Adobe&reg; and Photoshop&reg; are either registered trademarks or trademarks of Adobe Systems Incorporated in the United States and/or other countries.</em></p>

<h3 id="burrito">Burrito</h3>

<p>With Burrito you can read and manage your e-mails with any FTP client! It acts as a POP3/FTP protocol translator -- it's actually an FTP server that translates FTP commands to POP3 commands and serves your e-mail messages as individual files. You can view, delete and copy your e-mail messages as if they were files on an FTP server.</p>

<p class="download">Download <a href="/downloads/burrito10b.exe">burrito10b.exe</a> (479 KB)</p>

<p>This is a programming experiment that dates back to 2002. Having played around with POP3 for a while (see <a href="/2006/07/10/e-res-q-13/">E-Res-Q</a>), I just wanted to prove to myself that POP3/FTP protocol translation could work (and I guess I also had more free time back then). Burrito is certainly not a utility that would appeal to the general public, due to its obscure function. The only real-life scenario I can think of is an employee trying to circumvent the company firewall that blocks POP3 traffic (which is something I've experienced first-hand).</p>

<h4>Very Brief Usage Instructions</h4>

<p>The <em>About</em> tab has an <em>Check for update</em> button which takes you back to this site to tell you that you're using the most recent version of Burrito (since I currently don't have any intentions of releasing updates).</p>

<p class="center"><img src="i/burrito_idle.jpg" width="400" height="313" alt="About Tab"/></p>
<p class="center"><small>About Tab</small></p>

<p>In the <em>Options</em> tab, you can determine how you'll be passing the POP3 username and server to your FTP server. The password that you use for the FTP connection is used as the POP3 server password, so Burrito doesn't have to know your password in advance. However, the POP3 username and password must be combined and used as the FTP username. You can change the username-server separator here (defaults to "\").</p>

<p>You can also configure how filenames for individual messages are to be composed. The default is to use the name of the sender, followed by a dash, followed by the message subject. The default file extension is ".eml". After copying files to your local disk, you should be able to simply double click to view them in Outlook Express. Use ".msg" etc. to match whatever e-mail client you're actually using.</p>

<p class="center"><img src="i/burrito_options.jpg" width="400" height="313" alt="Options Tab"/></p>
<p class="center"><small>Options Tab</small></p>

<p>By default, Burrito listens on port 21, on all IP addresses. The <em>Security</em> tab allows you to tweak the FTP server listen settings.</p>

<p class="center"><img src="i/burrito_security.jpg" width="400" height="313" alt="Security Tab"/></p>
<p class="center"><small>Security Tab</small></p>

<p>Here's how a typical FTP client configuration looks like (I use <a href="http://ghisler.com" target="_blank">Total Commander</a>):</p>

<p class="center"><img src="i/burrito_ftpsettings.jpg" width="373" height="403" alt="FTP Client Settings"/></p>
<p class="center"><small>FTP Client Settings</small></p>

<p>And here's how your e-mail messages appear as files in your FTP client:</p>

<p class="center"><img src="i/burrito_ftpaction.jpg" width="400" height="300" alt="FTP Client in Action"/></p>
<p class="center"><small>FTP Client in Action</small></p>

<p>For every FTP connection you establish with Burrito, it will typically open a POP3 connection to the server that specified in the FTP username. It has a connection sharing feature for when multiple FTP clients access the same POP3 account.</p>

<p>Because Burrito doesn't download entire messages until you attempt to do an FTP-copy, simply listing and deleting messages are typically faster than a normal POP3 client. Therefore, it can actually be used for cleaning up a POP3 account after it gets choked by a huge messages or spam.</p>

<h4>Known Issues</h4>

<ul>
<li>After the system wakes up from hibernation/suspension, the FTP listen port becomes unresponsive. Momentarily chaning the <em>FTP server listen mode</em> in the <em>Security</em> tab to some other mode may (depending on the mode change) reset the listen port.</li>
</ul>

<h3 id="eresq">E-Res-Q</h3>

<p>E-Res-Q (read "ee rescue") is a very simple, portable POP3 mail reader. It enables you to selectively read and delete messages without having to download them all. It's ideal for getting rid of large messages that clog up your mailbox or getting rid of spam messages without even downloading them.</p>

<p class="msg">If <strong>E-Res-Q has saved your life</strong> in more than one occasion, feel free to <strong>express your appreciation</strong> through a small <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=1037532">PayPal donation</a>. Your donation will help towards the hosting expenses I've had over the years for serving this completely free utility.</p>

<p class="download">Download <a href="/downloads/eresq13.zip">eresq13.zip</a> (225.7 KB)</p>

<p class="center"><img src="i/e-res-q-ss.jpg" alt="E-Res-Q Screenshot" height="295" width="400"/></p>
<p class="center"><small>E-Res-Q Main Window</small></p>

<p>This small program has proved very useful back in the times when most of us still had dial-up connections. It's still a very useful utility for setting up quick access to your POP3 mailbox when you're temporarily using someone else's computer. The fact that it doesn't require installation is a plus. You just download the zip file, extract the binary and run it, punch in your account settings and you're ready to go!</p>

<h3 id="winresq">Win-Res-Q</h3>

<p>Win-Res-Q (read "win rescue") is a simple utility that restores (shows) hidden windows. It can be used for bringing back your "lost" applications after their taskbar icons disappear following Explorer crashes that abundantly occur on Windows 98. It's also useful for exposing strange, hidden windows lurking around your desktop.</p>

<p class="msg">If <strong>Win-Res-Q has saved your life</strong> on more than one occasion, feel free to <strong>express your appreciation</strong> through a small <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=1037532">PayPal donation</a>. Your donation will help towards the hosting expenses I've had over the years for serving this completely free utility.</p>

<p class="download">Download <a href="/downloads/winresq10.zip">winresq10.zip</a> (136.5 KB)</p>

<p class="center"><img src="i/win-res-q-ss.gif" alt="Win-Res-Q Screenshot" height="344" width="295"/></p>
<p class="center"><small>Win-Res-Q Main Window</small></p>

<p>This was one of the first Windows applications that I had written using Delphi. I forgot exactly when I wrote it but the file date hints that it's around August 1998. Back then, I was still using the nickname "HeaT" and I also thought that splash screens were cool. When you launch the program, an annoying splash screen pops up with the name "HeaT" on it . It automatically goes away after a few seconds or as soon as you click on it. An installer is not available since it's only a single executable (and I was lazy).</p>

<p>You should be cautious when getting weird system windows shown since it might not be possible to re-hide them unless you restart Windows.</p>

<p>For a more comprehensive taskbar icon rescue solution, you can use <a href="http://www.mlin.net/TraySaver.shtml">TraySaver</a> by <a href="http://www.mlin.net/">Mike Lin</a>.</p>