var g_js_hl_re = new RegExp(
    "(/\\*[^\\*]*\\*/|//[^\\r\\n]*)|" +
    "(\"(?:(?:\\\\.)*[^\\\\\"]*)*\"|'(?:(?:\\\\.)*[^\\\\']*)*')|" +
    "(/(?:(?:\\\\.)*[^\\\\/]*)*/g?i?m?)|" +
    "\\b(Array|Boolean|Date|Function|Math|Number|Object|RegExp|String)\\b|" +
    "\\b(Infinity|NaN|undefined|null)\\b|" +
    "\\b(break|catch|const|continue|do|else|export|for|function|if|import|in|return|switch|throw|try|var|while|with|delete|instanceof|new|this|typeof|void|case|default)\\b|" +
    "\\b(true|false)\\b|" +
    "\\b(\\d+(?:\\.\\d+)?(?:[Ee]-?\\d+)?|0x[\dA-Fa-f]+)\\b",
    "g");
var g_js_hl_classes = [
    "cmnt",
    "str",
    "re",
    "obj",
    "pv",
    "so",
    "bool",
    "num"
    ];

function syntaxHighlightJS(str)
{
    return str.replace(g_js_hl_re,
        function (str, p1, p2, offset, s)
        {
            for (var i = 0; i < arguments.length - 2; i++)
            {
                var arg = arguments[i + 1];
                
                if (arg != undefined && arg.length > 0)
                {
                    return "<span class=\"syn_" + g_js_hl_classes[i] + "\">" +
                        arg + "</span>";
                }
            }
        });
}
