/*
    Decoupled Context Popup 1.0
    (c) 2005 Ates Goral
    http://magnetiq.com/
*/

var g_dcp_bIsOpera = navigator.userAgent.indexOf("Opera") != -1;
var g_dcp_bIsIE = !g_dcp_bIsOpera &&
    navigator.userAgent.indexOf("MSIE") != -1;

var g_dcp_popup;
var g_dcp_prev_popup;

function dcp_handleMouseUp(e)
{
	var posx = 0;
	var posy = 0;
	var rb;
	
	if (e == undefined)
	{
	    var e = window.event;
	}
	
	if (e.pageX || e.pageY)
	{
		posx = e.pageX;
		posy = e.pageY;
		rb = e.which == 3;
	}
	else if (e.clientX || e.clientY)
	{
		posx = e.clientX;
		posy = e.clientY;
		rb = window.event.button == 2;

		if (g_dcp_bIsIE)
		{
			posx += document.body.scrollLeft;
			posy += document.body.scrollTop;
		}
	}

    var source;
    
    if (e.target != undefined) // For Mozilla
    {
        source = e.target;
    }
    else if (e.srcElement != undefined) // For IE
    {
        source = e.srcElement;
    }
    else
    {
        return true;
    }

    if (source.nodeType == source.TEXT_NODE) // For Safari
    {
        source = source.parentNode;
    }
    
    var popup;
    var popup_id = source.getAttribute("dcp:popup");

    if (popup_id != null && rb)
    {
        popup = document.getElementById(popup_id);
        
    	if (popup != null)
    	{
    	    var popup_handler = popup.getAttribute("dcp:onpopup");
            
            if (popup_handler != null && popup_handler.length > 0)
            {
                var popup_info = {
                    source: source,
                    popup: popup,
                    x: posx,
                    y: posy
                };
                
                popup.style.display = "block"; // For clientWidth/clientHeight
                
                if (window[popup_handler](popup_info))
                {
                    popup = popup_info.popup;
                    posx = popup_info.x;
                    posy = popup_info.y;
                }
                else
                {
                    popup.style.display = "none";
                    popup = undefined;
                }
            }
        }
        else
        {
            popup = undefined;
        }
    }
    else if (source.getAttribute("dcp:no_click") == "true")
    {
        return false;
    }


    if (g_dcp_popup != undefined && g_dcp_popup != popup)
    {
        g_dcp_prev_popup = g_dcp_popup;
        setTimeout("g_dcp_prev_popup.style.display = \"none\"", 0); // Hacky!
        //g_dcp_popup.style.display = "none";
    }

    g_dcp_popup = popup;
    
    if (popup != undefined)
    {
    	with (popup.style)
    	{
    	    left = posx + "px";
    	    top = posy + "px";
    	    display = "block";
    	}
    }

    return popup == undefined;
}

document.oncontextmenu = new Function("return g_dcp_popup == undefined");
document.onmouseup = dcp_handleMouseUp;
