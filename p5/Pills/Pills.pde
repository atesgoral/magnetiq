int zoom = 7;
float rx = 2;
float ry = 3;
BImage cp;
BFont fnt;

void setup()
{
  size(250, 250);
  colorMode(HSB, 100);
  noCursor();
  
  fnt = loadFont("Futura-ExBlkCon.vlw.gz");

  background(50, 80, 80);
  cp = copy();
}

void loop() 
{
  background(50, 80, 80);

  noStroke();
  noSmooth();
  
  tint(90, 80);
  image(cp, 0 - zoom, 0 - zoom, 250 + zoom * 2, 250 + zoom *2);
  noTint();
  
  smooth();
  
  for (int y = 0; y < 6; y++)
  {
    for (int x = 0; x < 6; x++)
    {
      float posx = 125 + (x - 2.5) * 30;
      float posy = 110 + (y - 2.5) * 30;
      float d = pow(1 - dist(posx, posy, mouseX, mouseY) / 250, 5) * 10;

      if (d < 8)
        stroke(90, 90, 90);
      else
        stroke(50, 70, 90);

      fill(d, 80, 100);

      push();

      translate(posx + d, posy + d, 0);
      rotateX(rx + sin(d / 5));
      rotateY(ry + cos(d / 5));
      
      box(12 + d * 1.2, 12 + d * 1.2, 6);
      
      ellipse(0, 0, 12, 12);
      ellipse(-12, -12, 12, 12);
      ellipse(-12, 0, 12, 12);
      ellipse(0, -12, 12, 12);

      pop();
    }
  }
  
  cp = copy();

  String s = "*Generic Viagra!*";
  
  textFont(fnt, 35);
  textMode(ALIGN_CENTER);

  translate(0, 0, 30);

  fill(0);
  text(s, 125, 225);
  fill(50, 70, 90);
  text(s, 125, 224);

  rx += 0.03;
  ry += 0.05;
} 
