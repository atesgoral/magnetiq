Hi! I'm Ateş Göral. I enjoy writing code and tinkering with graphics and electronics. I'm currently working at [Shopify](https://www.shopify.com) on AI stuff.

## Elsewhere

- [Twitter](https://twitter.com/atesgoral)
- [Observable](https://observablehq.com/@atesgoral)
- [CodePen](https://codepen.io/atesgoral/)
- [Stack Overflow](https://stackoverflow.com/users/23501/ates-goral)
- [LinkedIn](https://www.linkedin.com/in/atesgoral/)

## Open Source

I open-source whatever I can. Usually fringe stuff.

- [GitHub](https://github.com/atesgoral)
- [npm](https://www.npmjs.com/~atesgoral)

## Hardware + Software Hacking

I've developed a penchant for ripping open IKEA LED lamps to replace their graphics chips with single-board computers to run my own animations.

- [Hackaday](https://hackaday.io/atesgoral)

## JavaScript Golfing

I write verbose, readable code at work, and go nuts with unreadable JavaScript in my spare time.

- [Dwitter](https://www.dwitter.net/u/magna/top)
- [BD1K](https://atesgoral.github.io/bd1k/)

## Projects

### Binary Versioning

[https://binver.org/](https://binver.org/) &middot; 2020
{: .meta}

### Dweet Player

![Dweet Player](i/dweet-player.png "UI showing a dweet along with its code")
{: .image-240x135 }

[dweetplayer.net](https://dweetplayer.net) &middot; 2017
{: .meta}

[Dweet Player](https://dweetplayer.net) is an audiovisual sequencer for [dweets](https://www.dwitter.net) (visual effects in 140 characters of JavaScript, in the [demoscene](https://en.wikipedia.org/wiki/Demoscene) spirit).

I love looking at and [writing my own dweets](https://www.dwitter.net/u/magna/top). I wanted to string together a bunch of dweets and make them dance to some music. I created Dweet Player to sate that appetite.

Using an esoteric language in the query string, you sequence a bunch of dweets and apply timing and effects, and then specify an audio track. Dweet Player performs real-time beat detection of the audio track to make the dweets dance to the beat by morphing space (trig function outputs) and time (the `requestAnimationFrame` time stamp), as well applying some post-processing effects.

I presented it at a talk on Octobor 17, 2017 at the [Toronto Hack && Tell](https://www.meetup.com/Toronto-Hack-and-Tell/). [Here are the slides.](https://speakerdeck.com/atesgoral/dweet-player) I won a Raspberry Pi as an audience award.

### Human Resource Machine Solutions

[http://atesgoral.github.io/hrm-solutions/](http://atesgoral.github.io/hrm-solutions/) &middot; 2015
{: .meta}

Human Resource Machine solutions and size/speed hacks.

### #direnturkce

[https://direnturkce.org](https://direnturkce.org) &middot; 2013
{: .meta}

### browsersize.com

[https://browsersize.com](https://browsersize.com) &middot; 2005
{: .meta}

Browser resizing utility to aid in responsive web development.

It needs to be either updated or archived because modern browsers are restrictive about programmatically resizing the window.

### Twofifty

[twofifty.net](http://twofifty.net) &middot; 2000
{: .meta}

Static snapshot.

## Talks

I'm not really an active speaker. I've only given a few talks in the past. Here are the slides for those talks.

### Unit Testing—The Whys, Whens and Hows

![Unit Testing—The Whys, Whens and Hows](i/unit-testing-the-whys-whens-and-hows.png "Example of a passing but faulty unit test")
{: .image-240x135 }

Oct 11, 2016 &middot; [Toronto Node.js](https://www.meetup.com/toronto-node-js/) &middot; [Slides](https://speakerdeck.com/atesgoral/unit-testing-the-whys-whens-and-hows)
{: .meta }

Talking about some best practices for unit testing in Node.js.

---

### Doorbell Ringer—An Intentionally Complex IoT Project

![Doorbell Ringer](i/doorbell-ringer.jpg "A doorbell, a mobile phone, and an Onion Omega")
{: .image-240x135 }

Jun 7, 2016 &middot; [Toronto Hack && Tell](https://www.meetup.com/Toronto-Hack-and-Tell/) &middot; [Slides](https://speakerdeck.com/atesgoral/doorbell-ringer)
{: .meta }

A very roundabout way of ringing a doorbell through a mixture of: electronics hacking, Onion Omega, Python, Node.js, Twitter streaming API, QR codes, TOTP, Travis CI. A story of learning through self-inflicted problems.

I won a Raspberry Pi as an audience award.

---

### Backend-less UI Development using Demock

![Demock diagram](i/backend-less-ui-development.png "Diagram showing where Demock sits in the development environment")
{: .image-240x135 }

Mar 16, 2014 &middot; jQueryTO &middot; [Slides](https://speakerdeck.com/atesgoral/backend-less-ui-development)
{: .meta }

A technique to transform any static web server into a simulated backend, capable of emulating various HTTP methods, generating different HTTP response codes, and mimicking latency.
