var g_popupref = undefined;

function popup()
{
    if (g_popupref == undefined || g_popupref.closed)
    {
        g_popupref = window.open("/poly/popup.htm", "poly", "resizable=no,scrollbars=no,status=no,dialog=yes,width=600,height=600");
    }
    else
    {
        g_popupref.focus();
    }

    return false;
}
