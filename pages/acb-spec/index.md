## Adobe Color Book File Format Specification

Adobe Photoshop's **Color Picker** has a **Custom Colors** dialog that offers a wide variety of colors from several industry-standard color catalogs such as ANPA, DIC, Focoltone and Pantone.

<center>
![Custom Colors Dialog](i/custom_colors.jpg)

[Adobe Photoshop's Custom Colors Dialog]
</center>

The color catalog data comes from **Adobe Color Book** files. These file have the **.ACB** file extension on Windows and reside inside the ".../Presets/Color Books" folder in a typical installation.

Partly out of curiosity and partly because I needed the color data for a job, I went through reverse-engineering the file format since an official file format specification wasn't readily available. I have put together what I've come up with into an unofficial file format specification.

### Data Types

There are three main data types used throughout a color book file. 16-bit and 32-bit integers and strings.

Integers are in <a href="http://en.wikipedia.org/wiki/Endianness">big-endian</a> arrangement, so make sure you swap the bytes as necessary when reading/writing. I don't know whether they should be treated as signed or unsigned. Signed has worked for me.

Strings are like <a href="http://en.wikipedia.org/wiki/String_(computer_science)">Pascal strings</a>, starting with a 32-bit integer that gives the string length, followed by a sequence of <a href="http://en.wikipedia.org/wiki/Utf-16"><abbr title="Unicode Transformation Format">UTF</abbr>-16</a> characters (as kindly pointed out by <a href="http://spike.grobste.in/">Spike Grobstein</a>). Strings are not null-terminated therefore the string length gives the exact length of the string (without the length field). The UTF-16 characters are in big-endian order. Again, some byte swapping may be necessary.

<table>
<tr><td>Length</td><td>UTF-16 characters</td></tr>
</table>

### Header

The file starts with a header that contains general information about the color book. The fields in the header are:

<dl>
<dt id="acb_file_signature">Signature</dt>
<dd>4 consecutive characters that always read "8BCB". This probably stands for "8B Color Book".</dd>
<dt id="acb_file_version">Book Version</dt>
<dd>This is a 16-bit integer denoting the file version. Photoshop 7.0 only opens files of version 1.</dd>
<dt id="acb_identifier">Book Identifier</dt>
<dd>A 16-bit integer. This is probably a unique identifier assigned by Adobe to each official color book. The most significant byte always seems to be 0x0b.</dd>
<dt id="acb_title">Book Title</dt>
<dd>A string. The title of the color book as it appears in Photoshop's color picker. A "^R" within the string stands for a registered trademark (&reg;) symbol.</dd>
<dt id="acb_prefix">Color Name Prefix</dt>
<dd>This string is prepended to the beginning of the name of each color in the book.</dd>
<dt id="acb_suffix">Color Name Suffix</dt>
<dd>This string is appended to the end of the name of each color in the book.</dd>
<dt id="acb_description">Book Description</dt>
<dd>This string usually contains copyright information. A "^C" stands for the copyright symbol (&copy;). "^R" is for the registered trademark symbol (&reg;). I don't know if there are any other special sequences.</dd>
<dt id="acb_color_count">Color Count</dt>
<dd>A 16-bit integer. This can be more than the colors visible within Photoshop. Some color records can be dummy records (see <a href="#acb_color_name">Color Name</a>) that are used as padding in order to control how colors get grouped in pages.</dd>
<dt id="acb_page_size">Page Size</dt>
<dd>A 16-bit integer that specifies the maximum number of colors that can appear on a page.</dd>
<dt id="acb_page_offset">Page Selector Offset</dt>
<dd>The page selector in Photoshop's color picker display a sample color from each page. This offset specifies which of the colors on a page will be used as the page sample. "0" means the first (topmost) color. "1" means the second one and so on. If the offset exceeds or is equal to the number of colors on the page, the last color is used.</dd>
<dt id="acb_color_space">Color Space/Library Identifier</dt>
<dd>
	A 16-bit integer that specifies the color space/library. Values that Photoshop 7.0 recognizes are:
	<table>
	<tr><th>Value</th><th>Meaning</th></tr>
	<tr><td>0</td><td>RGB</td></tr>
	<tr><td>2</td><td>CMYK</td></tr>
	<tr><td>7</td><td>Lab</td></tr>
	</table>
	The rest of the values can be found in the official "Adobe Photoshop 6.0 File Formats Specification", but they apparently don't work for color books. Note that the 6.0 specification has no information on color book files. The full range of color space/library identifier values are:
	<table>
	<tr><th>Value</th><th>Meaning</th></tr>
	<tr><td>0</td><td><a href="http://en.wikipedia.org/wiki/Rgb"><abbr title="Red, Green, Blue">RGB</abbr></a></td></tr>
	<tr><td>1</td><td><a href="http://en.wikipedia.org/wiki/HSV_color_space"><abbr title="Hue, Saturation, Brightness">HSB</abbr></a></td></tr>
	<tr><td>2</td><td><a href="http://en.wikipedia.org/wiki/CMYK"><abbr title="Cyan, Magenta, Yellow, Key (black)">CMYK</abbr></a></td></tr>
	<tr><td>3</td><td><a href="http://www.pantone.com/">Pantone</a></td></tr>
	<tr><td>4</td><td><a href="http://www.focoltone.com/">Focoltone</a></td></tr>
	<tr><td>5</td><td><a href="http://www.trumatch.com/">Trumatch</a></td></tr>
	<tr><td>6</td><td><a href="http://www.toyoink.com/">Toyo</a></td></tr>
	<tr><td>7</td><td><a href="http://en.wikipedia.org/wiki/Lab_color_space"><abbr title="Lightness, a chrominance, b chrominance">Lab</abbr></a> (CIELAB D50)</td></tr>
	<tr><td>8</td><td><a href="http://en.wikipedia.org/wiki/Grayscale">Grayscale</a></td></tr>
	<tr><td>10</td><td><a href="http://en.wikipedia.org/wiki/HKS_(colour_system)">HKS</a></td></tr>
	</table>
</dd>
</dl>

### Color Records

Immediately following the header are the individual color records. There are exactly as many color records as there was specified by the <a href="#acb_color_count">Color Count</a> field.

Photoshop may display fewer colors then there are in the color book file because some color records are dummy records used for padding purposes. When a page has to contain fewer colors than the number specified by <a href="#acb_page_size">Page Size</a>, dummy records are inserted. Photoshop doesn't display these records.

The structure of a single color record is as follows:

<dl>
<dt id="acb_color_name">Color Name</dt>
<dd>A string. Concatenating the <a href="#acb_prefix">Color Name Prefix</a>, the Color Name and the <a href="#acb_suffix">Color Name Suffix</a> gives us the full name of a color as it appears in Photoshop's color picker. If the length of this string is zero, the entire color record is for padding (see <a href="#acb_color_count">Color Count</a>.) The remaining fields of the color record are ignored.</dd>
<dt>Color Code</dt>
<dd>6 consecutive characters. This is probably a catalog code or short name. Sometimes they are padded with spaces on either side. Photoshop doesn't seem to be displaying this information to the user at all.</dd>
<dt>Color Components</dt>
<dd>
	Depending on the color space, there can be 3 or 4 bytes, one byte for each color component:
	<dl>
	<dt>RGB</dt>
	<dd>
		<table>
		<tr>
		<td>Red</td>
		<td>Green</td>
		<td>Blue</td>
		</tr>
		</table>
		A byte for each of the Red, Green and Blue components. Each byte value directly translates to the corresponding level.
```
r = r_byte; // 0 thru 255
g = g_byte; // 0 thru 255
b = b_byte; // 0 thru 255
```
	</dd>
	<dt>CMYK</dt>
	<dd>
		<table>
		<tr>
		<td>Cyan</td>
		<td>Magenta</td>
		<td>Yellow</td>
		<td>Black</td>
		</tr>
		</table>

		A byte for each of the Cyan, Magenta, Yellow and Black components. Each is an unsigned value ranging from 0 to 255, representing 100 minus the intensity percentage, quantized to 255. To calculate the corresponding intensity percentage, subtract a byte value from 255, divide by 2.55 and then round to the nearest integer.

```
c = (255 - c_byte) / 2.55 + 0.5; // 0% thru 100%
m = (255 - m_byte) / 2.55 + 0.5; // 0% thru 100%
y = (255 - y_byte) / 2.55 + 0.5; // 0% thru 100%
b = (255 - b_byte) / 2.55 + 0.5; // 0% thru 100%
```
	</dd>

	<dt>Lab</dt>
	<dd>
		<table>
		<tr>
		<td>Lightness</td>
		<td>a chrominance</td>
		<td>b chrominance</td>
		</tr>
		</table>

		A byte for each of the Lightness, a chrominance and b chrominance components. The lightness percentage is quantized to 255. To get the lightness level percentage, divide the byte value by 2.55 and round to the nearest integer. The a and b chrominance values are offset by 128. To calculate their values, subtract 128 from the corresponding byte values.

```
l = l_byte / 2.55 + 0.5; // 0% thru 100%
a = a_byte - 128; // -128 thru 127
b = b_byte - 128; // -128 thru 127
```
	</dd>
	</dl>
</dd>
</dl>

### Spot/Process Identifier

This field was probably added in Photoshop CS and beyond. 8 characters (bytes), "spflspot" for spot colors and "spflproc" for process colors. This just identifies the color book as a spot/process color book. (Thanks to Olivier for alerting me about the presence of these extra bytes in CS.)

### Illustrated Example

Below is a portion from the beginning of "ANPA Color.acb" that ships with Adobe Photoshop 7.0 for Windows. Separate fields have been enclosed inside rectangles. A verbal walk-through will follow.

```
{% raw %}
          +-----------+-----+-----+-----------+------------
00000000h:|38 42 43 42|00 01|0B B8|00 00 00 23|00 24 00 24 ; 8BCB.......#.$.$
          +-----------+-----+-----+-----------+------------
00000010h: 00 24 00 2F 00 63 00 6F 00 6C 00 6F 00 72 00 62 ; .$./.c.o.l.o.r.b
          -------------------------------------------------
00000020h: 00 6F 00 6F 00 6B 00 2F 00 41 00 4E 00 50 00 41 ; .o.o.k./.A.N.P.A
          -------------------------------------------------
00000030h: 00 2F 00 74 00 69 00 74 00 6C 00 65 00 3D 00 41 ; ./.t.i.t.l.e.=.A
          -------------------------------------------------
00000040h: 00 4E 00 50 00 41 00 20 00 43 00 6F 00 6C 00 6F ; .N.P.A. .C.o.l.o
          ------+-----------+------------------------------
00000050h: 00 72|00 00 00 1F|00 24 00 24 00 24 00 2F 00 63 ; .r.....$.$.$./.c
          ------+-----------+------------------------------
00000060h: 00 6F 00 6C 00 6F 00 72 00 62 00 6F 00 6F 00 6B ; .o.l.o.r.b.o.o.k
          -------------------------------------------------
00000070h: 00 2F 00 41 00 4E 00 50 00 41 00 2F 00 70 00 72 ; ./.A.N.P.A./.p.r
          -------------------------------------------------
00000080h: 00 65 00 66 00 69 00 78 00 3D 00 41 00 4E 00 50 ; .e.f.i.x.=.A.N.P
          ------------+-----------+------------------------
00000090h: 00 41 00 20|00 00 00 21|00 24 00 24 00 24 00 2F ; .A. ...!.$.$.$./
          ------------+-----------+------------------------
000000a0h: 00 63 00 6F 00 6C 00 6F 00 72 00 62 00 6F 00 6F ; .c.o.l.o.r.b.o.o
          -------------------------------------------------
000000b0h: 00 6B 00 2F 00 41 00 4E 00 50 00 41 00 2F 00 70 ; .k./.A.N.P.A./.p
          -------------------------------------------------
000000c0h: 00 6F 00 73 00 74 00 66 00 69 00 78 00 3D 00 20 ; .o.s.t.f.i.x.=.
          ------------------------------+-----------+------
000000d0h: 00 41 00 64 00 50 00 72 00 6F|00 00 00 1F|00 24 ; .A.d.P.r.o.....$
          ------------------------------+-----------+------
000000e0h: 00 24 00 24 00 2F 00 63 00 6F 00 6C 00 6F 00 72 ; .$.$./.c.o.l.o.r
          -------------------------------------------------
000000f0h: 00 62 00 6F 00 6F 00 6B 00 2F 00 41 00 4E 00 50 ; .b.o.o.k./.A.N.P
          -------------------------------------------------
00000100h: 00 41 00 2F 00 64 00 65 00 73 00 63 00 72 00 69 ; .A./.d.e.s.c.r.i
          ------------------------------------+-----+-----+
00000110h: 00 70 00 74 00 69 00 6F 00 6E 00 3D|01 2C|00 06|; .p.t.i.o.n.=.,..
          +-----+-----+-----------+-----------+-----+-----+
00000120h:|00 05|00 07|00 00 00 04|00 37 00 31 00 2D 00 31|; .........7.1.-.1
          +-----+-----+-----+--+--+--+-----------+--------+
00000130h:|20 37 31 2D 31 44|F8|7B|7B|00 00 00 04|00 37 00 ;  71-1Dø{{.....7.
          +--------------+--+--+--+--+-----+--+--+--+------
00000140h: 31 00 2D 00 32|20 37 31 2D 32 44|F1|78|78|00 00 ; 1.-.2 71-2Dñxx..
          ------+--------+--------------+--+--+--+--+-----+          /\    /
00000150h: 00 04|00 37 00 31 00 2D 00 34|20 37 31 2D 34 44|;/\..7.1./  \71/
          +--+--+--+-----------+--------+------------/\+--+/  \    /    \/
00000160h:|E3|72|7/\00 00 00 04|00 37 00 31 00 2D 00/  \20/    \../
          +--+--+/  \----/\-+--+--+----/\----/\----/    \/      \/
00000170/\ 37 31/    \ 4/  \|68|67|00 /  \0 /  \0 /
 /\    /  \----/      \/    \--+-/\--/    \/    \/
/  \01/    \0 /              \1 /  \/
    \/      \/                \/
{% endraw %}
```

The file starts with the mandatory "8BCB" signature. Following that, the file version comes. The version must be 1 for Photoshop 7.0 to open the file. Then comes the unique color book identifier: 0x0bb8.

Next, the length of the title string is reported to be 0x23 (35) wide characters long. The title string is: "$$$/colorbook/ANPA/title=ANPA Color". The 31- character prefix string is: "$$$/colorbook/ANPA/prefix=ANPA ". Note the extra space character at the end. The suffix, "$$$/colorbook/ANPA/postfix= AdPro" is 33 characters long. This also has an extra space just after the equal sign. This file has no description, but the description string is there anyway. Having a length of 31 characters, it reads: "$$$/colorbook/ANPA/description=". I don't know what this path scheme is all about.

The following 0x012c gives us the number of colors in this book (300). There will be a maximum of 6 colors per page with a page offset of 5, which in turn means that the right-hand page selector in Photoshop's color picker will use the last color of each page. The color space is 7 (Lab).

The color records start here. The first color name is 4 characters long: "71-1". This should be concatenated with the prefix and suffix we saw earlier to produce the full color name: "ANPA 71-1 AdPro". The 6-character short name for this color is: " 71-1D". Notice the padding space at the left. The lightness component reads 0xf8 (248). This rounds down to a percentage of 248 / 255 * 100 = 97. The a and b chrominance values are both 0x7b (123). Subtracting 128 gives -5 for both components.

Immediately after this, the second color record starts. The name length is again 4, etc...

### See Also

* <a href="http://www.nomodes.com/aco.html">Adobe Photoshop Color File Format</a> by <a href="http://www.nomodes.com/">Larry Tesler</a></li>
* <a href="http://www.duo-creative.com/chrisb/aco/">Reverse-Engineering of the Adobe Color File Format</a> by <a href="http://www.duo-creative.com/chrisb/">Chris Berry</a></li>
* <a href="http://javastruct.googlecode.com/svn/trunk/javastruct/samples/photoshop/">AdobeColorBook Java class</a> by <a href="http://parduspardus.blogspot.com/">Mehmet D. Akin</a></li>
* <a href="http://magnetiq.com/pages/freeware#acb2xml">ACB2XML - Tool for exporting color book data as XML</a></li>
