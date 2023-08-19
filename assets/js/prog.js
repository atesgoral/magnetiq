function toggleClass(el, className) {
  let classNames = el.getAttribute("class");
  const classes = new Set(classNames && classNames.split(" "));

  if (classes.has(className)) {
    classes.delete(className);
  } else {
    classes.add(className);
  }

  classNames = [...classes.keys()].join(" ");

  el.setAttribute("class", classNames);
}

function toggleImageZoom(imageEl) {
  toggleClass(imageEl, "zoomed");
}

// const imageEls = document.querySelectorAll(".image-240x135 > img");

// for (const imageEl of imageEls) {
//   imageEl.addEventListener("click", () => toggleImageZoom(imageEl));
// }

const trailsCanvas = document.createElement("canvas");

trailsCanvas.className = "trails";

document.body.appendChild(trailsCanvas);

const dpr = window.devicePixelRatio;

trailsCanvas.width = trailsCanvas.clientWidth * dpr;
trailsCanvas.height = trailsCanvas.clientHeight * dpr;

let lastMouseMove = null;
let mouseX = null;
let mouseY = null;

document.body.addEventListener("mousemove", (evt) => {
  lastMouseMove = performance.now();
  mouseX = evt.clientX;
  mouseY = evt.clientY;

  trailsCanvas.style.left = `${mouseX - trailsCanvas.clientWidth / 2}px`;
  trailsCanvas.style.top = `${mouseY - trailsCanvas.clientHeight / 2}px`;

  trailsCanvas;
});

const trailsCtx = trailsCanvas.getContext("2d");

function renderTrails(t) {
  requestAnimationFrame(renderTrails);

  if (lastMouseMove === null) {
    return;
  }

  const maxAge = 1000;

  const elapsed = performance.now() - lastMouseMove;

  if (elapsed > maxAge) {
    return;
  }

  const decayAlpha = 1 - elapsed / maxAge;

  const scale = trailsCanvas.width;

  trailsCanvas.width |= 0;
  trailsCtx.scale(scale, scale);
  trailsCtx.translate(0.5, 0.5);

  const pixel = 1 / scale;

  trailsCtx.fillStyle = "#a484ff";

  const subs = 15;
  const spacing = trailsCanvas.clientWidth / subs;
  const size = 3;

  for (let i = 0; i < subs; i++) {
    for (let j = 0; j < subs; j++) {
      const x = i / subs - 0.5 - (mouseX % spacing) / trailsCanvas.clientWidth;
      const y = j / subs - 0.5 - (mouseY % spacing) / trailsCanvas.clientWidth;

      const distanceAlpha = 1 - Math.min(Math.hypot(x, y), 0.5) / 0.5;

      trailsCtx.globalAlpha = decayAlpha * distanceAlpha;

      trailsCtx.fillRect(
        x - (size / 2) * pixel,
        y - (size / 2) * pixel,
        size * pixel,
        size * pixel
      );
    }
  }
}

requestAnimationFrame(renderTrails);
