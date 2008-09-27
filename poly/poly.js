/*
    DHTML Polygon Rendering

    Copyright 2006 Ates Goral, magnetiq.com

    Licensed under a Creative Commons License
    http://creativecommons.org/licenses/by-nc/2.0/
*/

var g_canvasw = g_canvash = 600;

var g_primitives = {};

var g_properties = {
    sides: { current: 5, min: 3, max: 1000 },
    fps: { current: 40, min: 1, max: 1000 },
    speed: { current: 30, min: -1000, max: 1000 }
};

var g_options = {
    show_vertices: false,
    show_triangles: false,
    show_rectangles: false,
    fake_splines: false
};

var g_nodes = {};

var g_epoch, g_phase;
var g_offset = 0;

function $(id) { return document.getElementById(id); }

function getPrimitive(name)
{
    var p = g_primitives[name];
    var prim;
    
    if (p != undefined)
    {
        if (prim = p.available.pop())
        {
            prim.style.visibility = "visible";
        }
    }
    else
    {
        p = { available: [], used: [] };
        g_primitives[name] = p;
    }
    
    if (prim == undefined)
    {
        prim = g_nodes["tmp_" + name].cloneNode(false);
        
        prim.id = "";
        
        g_nodes.canvas.appendChild(prim);
    }
    
    p.used.push(prim);
    
    return prim;
}

function point(x, y)
{
    var node = getPrimitive("point");
    
    node.style.left = x - 1 + "px";
    node.style.top = y - 1 + "px";
}

function primitive(prim, x, y, w, h)
{
    if (w * h == 0)
    {
        return;
    }
    
    var node = getPrimitive(prim);
    var border_adj = 0;
    
    if ((g_options.show_triangles && prim.substr(0, 3) == "tri") ||
        (g_options.show_rectangles && prim == "rect"))
    {
        var dist = x * y / g_canvasw / g_canvash;

        node.style.border = "1px solid rgb(" +
            Math.round(255 - dist * 255) + ", " +
            Math.round(128 + dist * 64) + ", " +
            Math.round(64 + dist * 192) +
            ")";

        border_adj = 1;
    }
    else
    {
        node.style.border = "none";
    }

    node.style.left = x + "px";
    node.style.top = y + "px";
    node.style.width = w - border_adj + "px";
    node.style.height = h - border_adj + "px";
}

function polygon(x, y, r, n, tilt)
{
    var vertices = new Array();
    var minx, miny, maxx, maxy;

    minx = miny = Number.POSITIVE_INFINITY;
    maxx = maxy = Number.NEGATIVE_INFINITY;

    for (var i = 0; i < n; i++)
    {
        var a = Math.PI * 2 / n * i + tilt;
        var vertx = x + Math.round(r * Math.cos(a));
        var verty = y + Math.round(r * Math.sin(a));
        
        minx = Math.min(minx, vertx);
        miny = Math.min(miny, verty);
        maxx = Math.max(maxx, vertx);
        maxy = Math.max(maxy, verty);

        vertices.push({
            x: vertx,
            y: verty
        });
    }
        
    primitive("fill", minx, miny, maxx - minx, maxy - miny);

    for (var i = 0; i < vertices.length; i++)
    {
        var vert1 = vertices[(i - 1 + vertices.length) % vertices.length];
        var x1 = vert1.x;
        var y1 = vert1.y;
        var x2 = vertices[i].x;
        var y2 = vertices[i].y;

        var dx = x2 - x1;
        var dy = y2 - y1;
        
        if (dx * dy)
        {
            var sdx = Math.abs(dx) / dx;
            var sdy = Math.abs(dy) / dy;
            
            var norm = sdx / 2 + sdy + 1.5;
            var prim = "tri" + (g_options.fake_splines ? "r" : "") + norm;
            
            switch (norm)
            {
            case 0:
                /* sw */
                primitive(prim, x2, y2, -dx, -dy);
                
                if (x2 > minx)
                {
                   primitive("rect", minx, y2, x2 - minx, y1 - y2);
                }
                
                break;
            case 1:
                /* nw */
                primitive(prim, x1, y2, dx, -dy);

                if (x1 > minx)
                {
                    primitive("rect", minx, y2, x1 - minx, y1 - y2);
                }
                
                break;
            case 2:
                /* se */
                primitive(prim, x2, y1, -dx, dy);
                
                if (x1 < maxx)
                {
                    primitive("rect", x1, y1, maxx - x1, y2 - y1);
                }
                
                break;
            case 3:
                /* ne */
                primitive(prim, x1, y1, dx, dy);
                
                if (x2 < maxx)
                {
                    primitive("rect", x2, y1, maxx - x2, y2 - y1);
                }
                
                break;
            }
        }
        else if (dx == 0)
        {
            if (dy < 0)
            {
                primitive("rect", minx, y2, x1 - minx, y1 - y2);
            }
            else
            {
                primitive("rect", x1, y1, maxx - x1, y2 - y1);
            }
        }
            
        if (g_options.show_vertices)
        {
            point(x1, y1);
        }
    }
}

function beginUpdate()
{
    for (var name in g_primitives)
    {
        var p = g_primitives[name];
        var prim;
        
        while (prim = p.used.pop())
        {
            prim.style.visibility = "hidden"; // Optimize?
            p.available.push(prim);
        }
    }   
}

function endUpdate()
{
    // Hide only unused primitives?
}

function tick()
{
    var start = new Date();
    var t = start - g_epoch;
    
    beginUpdate();

    g_phase = t / 100000 * g_properties.speed.current + g_offset;

    polygon(300, 320, 200, g_properties.sides.current, g_phase);
    
    if (g_options.evil_twin)
    {
        polygon(550, 550, 40, g_properties.sides.current + 1, -g_phase);
    }

    endUpdate();

    var elapsed = new Date() - start;
    var sleep = 1000 / g_properties.fps.current;
    
    var delta = sleep - elapsed;
           
    var load_color;
    
    if (delta < 0)
    {
        load_color = "#ff8000";
        delta = 0;
    }
    else
    {
        load_color = "#ffffff";
    }
        
    g_properties.fps.input_node.style.color = load_color;
    g_nodes.load.style.color = load_color;
    
    var load = 1 - delta / sleep;
    
    var bars_text = "!!!!!!!!!!!!!!!!!!!!!!!";
    g_nodes.load.value = bars_text.substr(0, Math.round(bars_text.length * load));

    setTimeout("tick()", delta);
}

function setProp(name, value)
{
    var prop = g_properties[name];
    
    prop.current = Math.min(Math.max(value, prop.min), prop.max);;
    prop.input_node.value = prop.current;
    
    if (name == "speed")
    {
        g_offset = g_phase;
        g_epoch = new Date();
    }
    
    return false;
}

function spinProp(name, vec)
{
    return setProp(name, g_properties[name].current + vec);
}

function toggleOpt(opt, anchor_node)
{
    anchor_node.style.backgroundPosition = "0 " + (g_options[opt] ? 3 : -17) + "px";
    g_options[opt] = !g_options[opt];
    return false;
}

function init()
{
    for (var name in g_properties)
    {
        g_properties[name].input_node = $("prop_" + name);
        g_properties[name].input_node.value = g_properties[name].current;
    }

    g_nodes.canvas = $("canvas");
    g_nodes.load = $("load");
        
    var templates = $("tmp").childNodes;

    for (var i = 0; i < templates.length; i++)
    {
        var node = templates[i];
        
        if (node.nodeType == 1)
        {
            g_nodes[node.id] = templates[i];
        }
    }
   
    g_epoch = new Date();
    
    tick();
}
