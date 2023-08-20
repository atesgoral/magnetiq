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

function initializeZoom() {
  const zoomMaskEl = document.createElement("div");
  zoomMaskEl.setAttribute("id", "zoom-mask");

  const imageEls = document.querySelectorAll(".image-240x135 > img");

  for (const imageEl of imageEls) {
    imageEl.addEventListener("click", () => toggleImageZoom(imageEl));
  }
}

function initializeTrails() {
  const trailsContainer = document.createElement("div");
  const trailsCanvas = document.createElement("canvas");

  trailsContainer.setAttribute("id", "trails-container");
  trailsCanvas.setAttribute("id", "trails");

  trailsContainer.appendChild(trailsCanvas);
  document.querySelector(".markdown-body").appendChild(trailsContainer);

  const dpr = window.devicePixelRatio;

  trailsCanvas.width = trailsCanvas.clientWidth * dpr;
  trailsCanvas.height = trailsCanvas.clientHeight * dpr;

  let lastTrailsMove = null;
  let trailsX = null;
  let trailsY = null;

  function moveTrails(x, y) {
    lastTrailsMove = performance.now();
    trailsX = x;
    trailsY = y;

    trailsCanvas.style.left = `${
      trailsX - trailsCanvas.clientWidth / 2 + window.scrollX
    }px`;
    trailsCanvas.style.top = `${
      trailsY - trailsCanvas.clientHeight / 2 + window.scrollY
    }px`;
  }

  document.body.addEventListener("mousemove", (evt) => {
    moveTrails(evt.clientX, evt.clientY);
  });

  document.body.addEventListener("mousedown", (evt) => {
    moveTrails(evt.clientX, evt.clientY);
  });

  document.body.addEventListener("touchstart", (evt) => {
    moveTrails(evt.touches[0].clientX, evt.touches[0].clientY);
  });

  document.body.addEventListener("touchmove", (evt) => {
    moveTrails(evt.touches[0].clientX, evt.touches[0].clientY);
  });

  const trailsCtx = trailsCanvas.getContext("2d");

  function renderTrails(t) {
    requestAnimationFrame(renderTrails);

    if (lastTrailsMove === null) {
      return;
    }

    const maxAge = 1000;

    const elapsed = performance.now() - lastTrailsMove;

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
    const size = 2;

    for (let i = 0; i < subs; i++) {
      for (let j = 0; j < subs; j++) {
        const x =
          i / subs - 0.5 - (trailsX % spacing) / trailsCanvas.clientWidth;
        const y =
          j / subs - 0.5 - (trailsY % spacing) / trailsCanvas.clientWidth;

        const distance = Math.hypot(x, y);
        const distanceAlpha = 1 - Math.min(distance, 0.5) / 0.5;

        trailsCtx.globalAlpha = decayAlpha * distanceAlpha;

        const displacement = distanceAlpha ** 0.5;
        const dispX = x / displacement;
        const dispY = y / displacement;

        trailsCtx.beginPath();
        trailsCtx.arc(
          dispX - (size / 2) * pixel,
          dispY - (size / 2) * pixel,
          size * pixel,
          0,
          Math.PI * 2
        );
        trailsCtx.fill();
      }
    }
  }

  requestAnimationFrame(renderTrails);
}

document.addEventListener("DOMContentLoaded", () => {
  initializeTrails();
  initializeZoom();
});
