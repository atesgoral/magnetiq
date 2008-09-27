/* Syntax Highlighter 1.0
   by Ates Goral
   http://magnetiq.com/
   (c) 2005
*/

/* JavaScript */

var g_sh_js_re = new RegExp(
    // Comments
    "(/\\*(?:[^\\*]|\\*[^/])*\\*/|//[^\\r\\n]*)|" +
    // Strings
    "(\"(?:(?:\\\\.)*[^\\\\\"]*)*\"|'(?:(?:\\\\.)*[^\\\\']*)*')|" +
    // Regular expressions
    "(/\S(?:(?:\\\\.)*[^\\\\/]*)*/g?i?m?)|" +
    // Built-in objects
    "\\b(Array|Boolean|Date|Function|Math|Number|Object|RegExp|String)\\b|" +
    // Top-level properties, special values
    "\\b(Infinity|NaN|undefined|null)\\b|" +
    // Statements, operators
    "\\b(break|catch|const|continue|do|else|export|for|function|if|import|in|return|switch|throw|try|var|while|with|delete|instanceof|new|this|typeof|void|case|default)\\b|" +
    // Boolean
    "\\b(true|false)\\b|" +
    // Numbers
    "\\b(\\d+(?:\\.\\d+)?(?:[Ee]-?\\d+)?|0x[\\dA-Fa-f]+)\\b",
    "g");
var g_sh_js_cls = [ "cmnt", "str", "re", "obj", "pv", "so", "bool", "num" ];

/* Java */

var g_sh_java_re = new RegExp(
    // Comments
    "(/\\*(?:[^\\*]|\\*[^/])*\\*/|//[^\\r\\n]*)|" +
    // Strings
    "(\"(?:(?:\\\\.)*[^\\\\\"]*)*\"|'(?:(?:\\\\.)*[^\\\\']*)*')|" +
    // Regular expressions
    "(/\S(?:(?:\\\\.)*[^\\\\/]*)*/g?i?m?)|" +
    // Built-in objects
    "\\b(Array|Boolean|Date|Function|Math|Number|Object|RegExp|String)\\b|" +
    // Top-level properties, special values
    "\\b(Infinity|NaN|undefined|null)\\b|" +
    // Statements, operators
    "\\b(assert|abstract|break|boolean|byte|continue|catch|class|char|const|case|default|do|double|extends|else|enum|final|finally|for|float|goto|interface|if|implements|import|instanceof|int|long|new|native|public|package|private|protected|return|synchronized|short|static|switch|super|strictfp|throw|try|throws|transient|this|volatile|void|while)\\b|" +
    // Boolean
    "\\b(true|false)\\b|" +
    // Numbers
    "\\b(\\d+(?:\\.\\d+)?(?:[Ee]-?\\d+)?|0x[\\dA-Fa-f]+)\\b",
    "g");
var g_sh_java_cls = [ "cmnt", "str", "re", "obj", "pv", "so", "bool", "num" ];

/* XML */

var g_sh_xml_re = new RegExp(
    // Elements
    "(&lt;(?![\\?!])[\\w]+)(\\s+[\\w]+)*\\b|" +
    // Processing instructions
    "(&lt;\\?(?:\\?(?!&gt;)|[^\\?])*\\?&gt;)|" +
    // Comments
    "(&lt;!--(?:-(?!-&gt;)|[^\\-])*--&gt;)",
    "g");
var g_sh_xml_cls = [ "elem", "attr", "pi", "cmnt" ];

var g_hl_langs = new Object();

g_hl_langs["javascript"] = { re: g_sh_js_re, cls: g_sh_js_cls };
g_hl_langs["java"] = { re: g_sh_java_re, cls: g_sh_java_cls };
g_hl_langs["xml"] = { re: g_sh_xml_re, cls: g_sh_xml_cls };

function syntaxHighlightStr(str, lang)
{
    var lang_obj = g_hl_langs[lang];
    
    if (lang_obj == undefined)
    {
        return str;
    }

    return str.replace(lang_obj.re,
        function ()
        {
            for (var i = 0; i < arguments.length - 2; i++)
            {
                var arg = arguments[i + 1];
                
                if (arg != undefined && arg.length > 0)
                {
                    return "<span class=\"synh_" + lang_obj.cls[i] + "\">" +
                        arg + "</span>";
                }
            }
        });
}

function syntaxHighlightInnerText(html, lang)
{
    return html.replace(
        /((?:<[A-Z]+(?:\s+[^=]+=\"?[^\">]*\"?)*>)*)([^<]*)((?:<\/[A-Z]+>)*)/i,
        function (str, p1, p2, p3)
        {
            return p1 + syntaxHighlightStr(p2, lang) + p3;
        });
}

function syntaxHighlightElem(elem, lang, cache, highlight)
{
    while (elem != undefined && elem.tagName != "PRE")
    {
        elem = elem.parentNode;
    }
    
    if (elem == undefined)
    {
        return;
    }
    
    var prop = "outerHTML" in elem ? "outerHTML" : "innerHTML";
    var str;
    
    if (highlight == undefined || highlight)
    {
        if (cache != undefined)
        {
            str = cache.highlighted;
        }
        else
        {
            var orig = elem[prop];

            str = syntaxHighlightInnerText(orig, lang);
             
            cache = {
                orig: orig,
                highlighted: str
            } 
        }
    }
    else if (cache != undefined)
    {
        str = cache.orig;
    }
    else
    {
        return undefined;
    }

    elem[prop] = str;
    
    return cache;
}
