Hi! I'm <abbr title="IPA: [ɑˈt̪eʃ ɟœɾɑɫ]">Ateş Göral</abbr>. I enjoy writing code, tinkering with graphics, experimenting with electronics, and executing ambitious DIY projects.

I'm a self-taught programmer with 25+ years of professional and 35+ years of hobbyist experience. I'm a telco and web veteran with both breadth and depth, spanning multiple programming languages, disciplines, and technologies. I also have an ornamental Associate of Science degree in Molecular Biology and Genetics.

I'm currently working at [Shopify](https://www.shopify.com) on AI stuff ([Sidekick](https://www.shopify.com/magic)).

Past: [Orium](https://orium.com/) (Myplanet), [Genesys](https://www.genesys.com/) (Alcatel-Lucent, VoiceGenie), PhonoClick, Turk Nokta Net. And a whole bunch of odd jobs.

## Elsewhere

- [Twitter / <del>X</del>](https://twitter.com/atesgoral): I project-log, dad-joke, and sub-tweet.
- [Dwitter](https://www.dwitter.net/u/magna/top) (JavaScript golfing): By day, a paragon of verbose, readable code – by night, a wizard of terse, cryptic JavaScript.
- [GitHub](https://github.com/atesgoral): I open-source whatever I can. Usually fringe stuff.
- [npm](https://www.npmjs.com/~atesgoral): I publish whatever I can. Usually fringe stuff.
- [Hackaday](https://hackaday.io/atesgoral): I've developed a penchant for cracking open IKEA LED lamps to replace their graphics chips with single-board computers to run my own animations.
- [Stack Overflow](https://stackoverflow.com/users/23501/ates-goral): I used to actively post answers. I attained a high reach and moderator status. I just edit people's typos nowadays.
- [LinkedIn](https://www.linkedin.com/in/atesgoral/): I'm happy where I am, but open to expanding my network.
- [Observable](https://observablehq.com/@atesgoral): I just created a Dwitter runtime.
- [CodePen](https://codepen.io/atesgoral/): An unorganized mess of pens.

## Projects

Finished personal projects in reverse chronological order.

### IKEA OBEGRÄNSAD Hack

![IKEA OBEGRÄNSAD Hack](i/obegransad-hack.jpg "Collage showing electronics, a Mario scene, and a metaballs scene")
{: .image-240x135 }

[github.com/atesgoral/obegraensad-hack](https://github.com/atesgoral/obegraensad-hack) &middot; 2022
{: .meta}

I hacked an IKEA OBEGRÄNSAD LED wall lamp to replace its graphics chip with an ESP32 and wrote some C++, JavaScript and WebAssembly to run my own graphics on it.

---

### Airgap—True Analog Glitching by Transmitting Pixel Data Through Air

![Airgap](i/airgap.jpg "Photo of a laptop screen with a piece of aluminum over the camera, showing an image being transmitted through reflection")
{: .image-240x135 }

[atesgoral.github.io/airgap](https://atesgoral.github.io/airgap/) &middot; 2021
{: .meta}

Can we really get true analog/chaotic glitching with an entirely digital device? Using the screen as a signal source and the camera as a receiver, I experimented with transmitting a digital signal through the air, bouncing off random surfaces.

A source image is scanned pixel-by-pixel and the pixel value is drawn as a large square on the screen, close to where the webcam is. Something reflective (even the palm of a hand works) is cupped around the webcam and where the "signal" square is. After a calibration run, the image is transmitted through the reflector, picking up gnarly analog glitches.

---

### IKEA FREKVENS Hack

![IKEA FREKVENS Hack](i/frekvens-hack.jpg "Collage showing the original cube, electronics, and some scenes")
{: .image-240x135 }

[hackaday.io/project/171034-frekvens-fjrrkontroll](https://hackaday.io/project/171034-frekvens-fjrrkontroll) &middot; [github.com/atesgoral/node-omega-frekvens](https://github.com/atesgoral/node-omega-frekvens) &middot; 2020
{: .meta}

I hacked an IKEA FREKVENS LED cube lamp to replace its graphics chip with an Onion Omega 2+ and wrote some C++, JavaScript to run my own graphics on it.

---

### Binary Versioning

![Binary Versioning](i/binver.png "Screenshot of binver.org")
{: .image-240x135 }

[binver.org](https://binver.org/) &middot; 2020
{: .meta}

A silly take on [Semantic Versioning](https://semver.org/). I apparently had too much free time on my hands (and disposable income to buy a new domain).

---

### put.io Starry Night

![Starry Night](i/starry-night.png "The Starry Night animation of put.io")
{: .image-240x135 }

[put.io Starry Night](https://atesgoral.github.io/put.io.starry.night/) &middot; 2017
{: .meta}

This is a canvas-based animation I created to replace a large MP4 movie on the landing page of [put.io](https://put.io/). The project was a freelance commission by put.io.

I developed a tweakable version for the client to fine-tune the animation to their liking.

---

### Dweet Player

![Dweet Player](i/dweet-player.png "UI showing a dweet along with its code")
{: .image-240x135 }

[dweetplayer.net](https://dweetplayer.net) &middot; 2017
{: .meta}

[Dweet Player](https://dweetplayer.net) is an audiovisual sequencer for [dweets](https://www.dwitter.net) (visual effects in 140 characters of JavaScript, in the [demoscene](https://en.wikipedia.org/wiki/Demoscene) spirit).

I love looking at and [writing my own dweets](https://www.dwitter.net/u/magna/top). I wanted to string together a bunch of dweets and make them dance to some music. I created Dweet Player to sate that appetite.

Using an esoteric language in the query string, you sequence a bunch of dweets and apply timing and effects, and then specify an audio track. Dweet Player performs real-time beat detection of the audio track to make the dweets dance to the beat by morphing space (trig function outputs) and time (the `requestAnimationFrame` time stamp), as well applying some post-processing effects.

I gave [a talk](#dweet-player-1) about this project.

---

### BD1K—A Boulder Dash Clone in 1024 Bytes of JavaScript

![BD1K](i/bd1k.png "Screenshot from the game")
{: .image-240x135 }

[atesgoral.github.io/bd1k](https://atesgoral.github.io/bd1k/) &middot; 2017
{: .meta}

A Boulder Dash clone in 1024 bytes of JavaScript (including the sprites from the original game). My contribution to [JS1k 2017 - Magic](https://js1k.com/2017-magic/).

---

### Doorbell Ringer—An Intentionally Complex IoT Project

![Doorbell Ringer](i/doorbell-ringer.jpg "A doorbell, a mobile phone, and an Onion Omega")
{: .image-240x135 }

[github.com/atesgoral/doorbell-ringer](https://github.com/atesgoral/doorbell-ringer) &middot; [github.com/atesgoral/doorbell-nudger](https://github.com/atesgoral/doorbell-nudger) &middot; 2016
{: .meta }

A very roundabout way of ringing a doorbell through a mixture of: electronics hacking, Onion Omega, Python, Node.js, Twitter streaming API, QR codes, TOTP, Travis CI. A story of learning through self-inflicted problems.

I gave [a talk](#doorbell-ringeran-intentionally-complex-iot-project-1) about this project.

---

### Human Resource Machine Solutions

![Human Resource Machine Solutions](i/human-resource-machine.png "A screenshot from the game, Human Resource Machine")
{: .image-240x135 }

[http://atesgoral.github.io/hrm-solutions](http://atesgoral.github.io/hrm-solutions/) &middot; 2015
{: .meta}

[Human Resource Machine](https://tomorrowcorporation.com/humanresourcemachine) is a fun, little, dark-humoured puzzle game that can either teach you assembly from the ground up, or allow you to apply your existing assembly knowledge in order to devise speed/size-optimized solutions to increasingly challenging problems.

This project started with me [publishing my own solutions](https://github.com/atesgoral/hrm-solutions) and then snowballed into a massive collaborative repository of solutions from some 80+ contributors. I've never had any of my open-source projects forked and PR'd this much.

---

### Autonomous Cockroach

![Autonomous Cockroach](i/autonomous-cockroach.jpg "A collage showing LEGO, electronics, and an IKEA lamp.")
{: .image-240x135 }

[hackaday.io/project/171720-cockroach](https://hackaday.io/project/171720-cockroach) &middot; [github.com/atesgoral/autonomous-cockroach](https://github.com/atesgoral/autonomous-cockroach) &middot; 2015
{: .meta}

This is the very first microcontroller (and IKEA lamp hack) project I did with an Arduino starter kit and (mostly) found parts. A LEGO drivetrain + two motors controlled by an Arduino Uno.

---

### #direnturkce

![#direnturkce](i/diren-turkce.png "Screenshot of the website")
{: .image-240x135 }

[direnturkce.org](https://direnturkce.org) &middot; 2013
{: .meta}

I have many pet peeves. I created a quick reference page for the most common misspellings in Turkish.

---

### browsersize.com

![browsersize.com](i/browsersize.png "Screenshot of the website")
{: .image-240x135 }

[browsersize.com](https://browsersize.com) &middot; 2005
{: .meta}

Browser resizing utility to aid in responsive web development.

It needs to be either updated or archived, as modern browsers have become more restrictive regarding the programmatic resizing of windows.

---

### The Unofficial Adobe Color Book File Format Specification

![Photoshop Color Picker](i/custom-colors.png "The color picker dialog of Adobe Photoshop")
{: .image-240x135 }

[Adobe Color Book File Format Specification](pages/acb-spec) &middot; 2003
{: .meta}

Adobe Photoshop's **Color Picker** has a **Custom Colors** dialog that offers a wide variety of colors from several industry-standard color catalogs such as ANPA, DIC, Focoltone and Pantone. The color catalog data comes from **Adobe Color Book** files.

Partly out of curiosity and partly because I needed the color data for a job, I went through reverse-engineering the file format since an official file format specification wasn't readily available. I have put together what I've come up with into an [unofficial file format specification](pages/acb-spec).

---

### Burrito

![Burrito](i/burrito.png "Screenshot of the app")
{: .image-240x135 }

[Download Burrito 1.0 beta](downloads/Burrito10b.zip) &middot; 2002
{: .meta}

With Burrito you can read and manage your e-mails with any FTP client! It acts as a POP3/FTP protocol translator -- it's actually an FTP server that translates FTP commands to POP3 commands and serves your e-mail messages as individual files. You can view, delete and copy your e-mail messages as if they were files on an FTP server.

---

### Twofifty

![Twofifty](i/twofifty.png "Screenshot of the website")
{: .image-240x135 }

[twofifty.net](https://twofifty.net) &middot; 2000
{: .meta}

Constraints stoke creativity. In that spirit, this is a showcase of digital art, strictly 250x250 pixels in dimensions, contributed by many different artists. (My own works are published as "MaGnA".)

Twofifty garnered popularity in the early 2000's and was featured in several websites and print publications.

I used hidden iframes to inject JavaScript into the parent frame to achieve a SPA (Single Page Application) before AJAX was a thing.

This is a static snapshot of the once PHP+MySQL-powered website.

---

### E-Res-Q

![E-Res-Q](i/e-res-q.png "Screenshot of the app")
{: .image-240x135 }

[Download E-Res-Q 1.3](downloads/EResQ13.zip) &middot; 1998
{: .meta}

E-Res-Q (pronounced "ee rescue") is a very simple, portable POP3 email reader. It enables you to selectively read and delete messages without having to download them all. It's ideal for getting rid of large messages that clog up your mailbox or getting rid of spam messages without even downloading them.

This small program has proved very useful back in the times when most of us still had dial-up connections. It's still a very useful utility for setting up quick access to your POP3 mailbox when you're temporarily using someone else's computer. The fact that it doesn't require installation is a plus. You just download the zip file, extract the binary and run it, punch in your account settings and you're ready to go!

---

## Talks

While I'm not a frequent public speaker, I have had the opportunity to present a few talks in the past.

### Dweet Player

![Dweet Player](i/dweet-player-talk.jpg "Photo of me presenting")
{: .image-240x135 }

October 17, 2017 &middot; [Toronto Hack && Tell](https://www.meetup.com/Toronto-Hack-and-Tell/) &middot; [Slides](https://speakerdeck.com/atesgoral/dweet-player)
{: .meta }

A talk about my [Dweet Player project](#dweet-player). I won a Raspberry Pi as an audience award.

---

### Unit Testing—The Whys, Whens and Hows

![Unit Testing—The Whys, Whens and Hows](i/unit-testing.jpg "Photo of me presenting")
{: .image-240x135 }

October 11, 2016 &middot; [Toronto Node.js](https://www.meetup.com/toronto-node-js/) &middot; [Slides](https://speakerdeck.com/atesgoral/unit-testing-the-whys-whens-and-hows)
{: .meta }

Talking about some best practices for unit testing in Node.js.

---

### Doorbell Ringer—An Intentionally Complex IoT Project

![Doorbell Ringer](i/doorbell-ringer-talk.jpg "Photo of me presenting")
{: .image-240x135 }

June 7, 2016 &middot; [Toronto Hack && Tell](https://www.meetup.com/Toronto-Hack-and-Tell/) &middot; [Slides](https://speakerdeck.com/atesgoral/doorbell-ringer)
{: .meta }

A talk about my [Doorbell Ringer project](#doorbell-ringeran-intentionally-complex-iot-project). I won a Raspberry Pi as an audience award.

---

### Backend-less UI Development using Demock

![Backend-less UI Development using Demock](i/demock.jpg "Photo of me presenting")
{: .image-240x135 }

March 16, 2014 &middot; jQueryTO &middot; [Slides](https://speakerdeck.com/atesgoral/backend-less-ui-development)
{: .meta }

A technique to transform any static web server into a simulated backend, capable of emulating various HTTP methods, generating different HTTP response codes, and mimicking latency.
