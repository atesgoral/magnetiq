---
title: Morphing Depth of Field
---

## Morphing Depth of Field

Two (or more) photographs of the same scene and that have different focal distances can be joined together to create an image where both distant and close objects can be in focus.

Quoting from [A Multifocus Method for Controlling Depth of Field](http://www.graficaobscura.com/depth/index.html) by Paul Haeberli:

> When a photograph is taken with a camera, the lens is focused at a particular distance. Objects nearer or farther than this focal distance will appear blurred. By changing the focus of the lens, near objects or distant objects can be made to appear in sharp focus. If you want to create an image where distant objects as well as close objects are in focus, two or more images can be merged together to make an image with increased depth of field...

Instead of creating a single, in-focus image from two images, I wanted to see how a smooth morphing effect would look like.

<div class="center">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/processing.js/1.4.8/processing.min.js"></script>
  <canvas data-processing-sources="multi_focus.pde" width="250" height="250"></canvas>
</div>

<p>Hovering over the image controls how much each of the two images contributes to the final image. Vertical movement controls the contribution amount of the image where the bottle at the front in-focus. Horizontal movement controls the image where the bottle at the back is in-focus.</p>

<p>To get a natural, focus-back-and-forth experience, move the mouse from the top right corner to the bottom left corner and back.</p>

This was done using [Processing.js](http://processingjs.org/). You can download the [PDE file](multi_focus.pde).
