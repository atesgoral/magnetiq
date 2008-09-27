/* Returns the class of the argument or undefined if it's
   not a valid JavaScript object.
*/
function getObjectClass(obj)
{
    if (obj && obj.constructor && obj.constructor.toString)
    {
        var arr = obj.constructor.toString().match(
            /function\s*(\w+)/);

        if (arr && arr.length == 2)
        {
            return arr[1];
        }
    }

    return undefined;
}

function MyClass()
{
}

function test(type, expr)
{
    var obj = eval(expr);

    document.write("<p>" + type + ": <b>" +
        expr + "</b><br />");
    document.write("- toString() returns: " +
        obj.toString() + "<br />");
    document.write("- typeof() returns: <b>" +
        typeof(obj) + "</b><br />");
    document.write("- getObjectClass() returns: <b>" +
        getObjectClass(obj) + "</b></p>");
}

test("Integer", "42");
test("Boolean", "true");
test("String", "\"Hello World!\"");
test("Function", "MyClass");
test("Regular expression", "/Match this!/");
test("Intrinsic object", "document");
test("Array object", "new Array(1, 2, 3)");
test("Date object", "new Date()");
test("Object object", "new Object()");
test("MyClass object", "new MyClass()");
