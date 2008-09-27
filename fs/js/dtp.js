var g_active_tab;
var g_active_tab_linked;

function showTabbedPage(tab, show)
{
    var page_id = tab.getAttribute("ctp:page");
    
    if (page_id != null)
    {
        document.getElementById(page_id).style.display = show ? "block" : "none";
    }
}

function activateTab(anchor)
{
    if (g_active_tab != undefined)
    {
        showTabbedPage(g_active_tab_linked, false);
        
        g_active_tab.parentNode.replaceChild(g_active_tab_linked, g_active_tab);
    }

    var activate_handler = anchor.getAttribute("ctp:onactivate");
    
    if (activate_handler != null)
    {
        window[activate_handler](anchor);
    }

    showTabbedPage(anchor, true);
 
    g_active_tab = anchor.firstChild.cloneNode(true);
    g_active_tab_linked = anchor.parentNode.replaceChild(g_active_tab, anchor);
    
    return false;
} 
