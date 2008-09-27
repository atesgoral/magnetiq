var g_jash_cache = new Array();

function syntaxHighlightAll(highlight)
{
    var codes = document.getElementsByTagName("CODE");
    
    for (var i = 0; i < codes.length; i++)
    {
        var code = codes[i];
        var lang_matches = code.lang.match(/^x-jash\.(.+)/);
    
        if (lang_matches && lang_matches.length == 2)
        {
            g_jash_cache[i] = jashElem(code, lang_matches[1],
                g_jash_cache[i], highlight);
        }
    }
}
