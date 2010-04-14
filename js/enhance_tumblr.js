var meta = {};

$("meta").each(function () {
    var tokens = /(.+):(.+)/.exec(this.name);

    if (tokens) {
        meta[tokens[1]] = this.content;
        console.log(this.content);
    }
});

if (meta["Font Replacement"]) {
    Cufon.replace("h1, h2");
}

if (meta["Permalink Page"] && meta["Syntax Highlighting"]) {
    SyntaxHighlighter.all();
}