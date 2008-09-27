/*
To Do:
- on hover on page, show summary?
- g_ui_state?
- swap pages; update with xor :)
*/

/* Constants */

var PFX_CAT = "cat_"; // exclude _?
var PFX_DOC = "doc_";
var PFX_PAGE = "page_";
var PFX_FLD = "fld_";
var PFX_RSC = "rsc_";

var FLASH_CNT = 5;
var HILITE_COLOR = "#EDFFC6"; // -> classname

/*
var NODE_TYPE_CAT = 0;
var NODE_TYPE_DOC = 1;
var NODE_TYPE_PAGE = 2;
*/

/* Globals */

var g_initialized = false;
var g_fs_ns = "http://www.magnetiq.com/ns/2005/08/fs";
var g_selected_elem;
var g_selected_action;

//var g_move_urls = ["confirm", "", ""];

/* Utils */

function ge(id)
{
    return document.getElementById(id);
}

function getCategoryElem(id)
{
    return ge(PFX_CAT + id);
}

function getDocumentElem(id)
{
    return ge(PFX_DOC + id);
}

function getPageElem(id)
{
    return ge(PFX_PAGE + id);
}

function getFolderElem(id)
{
    return ge(PFX_FLD + id);
}

function getResourceElem(id)
{
    return ge(PFX_RSC + id);
}

function hasChild(node, child_node)
{
    var parent_node = child_node.parentNode;
    
    while (parent_node != undefined && parent_node != node)
    {
        parent_node = parent_node.parentNode;
    }
    
    return parent_node == node;
}

function getParentById(node, id)
{
    var parent_node = node.parentNode;
    
    while (parent_node != undefined && parent_node.id != id)
    {
        parent_node = parent_node.parentNode;
    }
    
    return parent_node;
}

function getChildById(node, id)
{
    for (var i = 0; i < node.childNodes.length; i++)
    {
        var child_node = node.childNodes[i];
        
        if (child_node.id == id)
        {
            return child_node;
        }
        else
        {
            child_node = getChildById(child_node, id);
            
            if (child_node != undefined)
            {
                return child_node;
            }
        }
    }
    
    return undefined;
}

function setAnchorEnabled(anchor, enabled)
{
    if (enabled)
    {
        anchor.style.color = "";

        if (anchor.getAttribute("dcp:no_click") != null)
        {
            anchor.onclick = anchor.fs_old_onclick;
            anchor.setAttribute("dcp:no_click", "false");
        }
        //anchor.className = "";
    }
    else
    {
        anchor.style.color = "#a0a0a0";
        anchor.fs_old_onclick = anchor.onclick;
        anchor.onclick = new Function("return false");
        //anchor.className = "disabled";
        anchor.setAttribute("dcp:no_click", "true");
    }  

    anchor.ctxmenu_disabled = !enabled;
}

function setAnchors(elem, id)
{
    var anchors = elem.getElementsByTagName("a");
    
    for (var i = 0; i < anchors.length;  i++)
    {
        var anchor = anchors[i];
        var href_template = anchor.getAttribute("fs:href_template");
        
        if (href_template == null)
        {
            href_template = anchor.href;
        }
        
        anchor.href = href_template.replace(/%id%/g, id);

        var allow_on_root =
            anchor.getAttribute("fs:allow_on_root") == "true";

        //anchor.style.className = fs_node.id != 0 ? "" : "disabled";
        setAnchorEnabled(anchor, allow_on_root || id != 0);
    }
    
    return true;
}

function log(str)
{
    ge("log").innerHTML += str + "<br/> ";    
}

function asyncExec(cmd) // Post to iframe on this page instead?
{
    setTimeout(cmd, 0);
}

/* Classes */

function FSNode(id, caption, parent_id)
{
    this.setCaption = function (caption)
    {
        //this.caption = caption;
        //this.elem.getElementsByTagName("a")[1].innerHTML = caption;
        getChildById(this.elem, "cap").innerHTML = caption;
    }

    this.setParent = function (parent_id)
    {
        if (this.elem.parentNode != undefined)
        {
            this.elem = this.elem.parentNode.removeChild(this.elem);
        }

        this.parent_id = parent_id;
        this.parent_elem_id = this.parent_pfx + parent_id;

        var parent_node = ge(this.parent_elem_id);
            
        if (parent_node != null)
        {
            //parent_node.getElementsByTagName("div")[2].appendChild(this.elem);    
            getChildById(parent_node, "branch").appendChild(this.elem);    
        }
    }
    
    this.canBeUnder = function (fs_node)
    {
        return fs_node.pfx == this.parent_pfx;
    }
    
    this.id = id;
    this.elem_id = this.pfx + id;
    this.elem = ge(this.pfx + "template").cloneNode(true);
    this.elem.id = this.elem_id;
    this.elem.fs_node = this;
    
    setAnchors(this.elem, id);
    
    this.setCaption(caption);
    this.setParent(parent_id);
}

function FSCategory(id, name, parent_id)
{
    this.pfx = PFX_CAT;
    this.parent_pfx = PFX_CAT;
    this.child_pfx = PFX_DOC;
    this.superclass = FSNode;
    this.superclass(id, name, parent_id);
}

function FSDocument(id, title, category_id, alias, summary)
{
    this.setSummary = function (summary)
    {
        this.summary = summary;
        //this.elem. = summary;
    }
    
    this.pfx = PFX_DOC;
    this.parent_pfx = PFX_CAT;
    this.child_pfx = PFX_PAGE;
    this.superclass = FSNode;
    this.superclass(id, title, category_id);
    
    this.alias = alias;
    this.setSummary(summary);
}

function FSPage(id, title, document_id, page_index)
{
    this.pfx = PFX_PAGE;
    this.parent_pfx = PFX_DOC;
    this.superclass = FSNode;
    this.superclass(id, title, document_id);
    
    this.page_index = parseInt(page_index);
}

function FSFolder(id, name, parent_id)
{
    this.pfx = PFX_FLD;
    this.parent_pfx = PFX_FLD;
    this.child_pfx = PFX_RSC;
    this.superclass = FSNode;
    this.superclass(id, name, parent_id);
}

function FSResource(id, name, folder_id)
{
    this.pfx = PFX_RSC;
    this.parent_pfx = PFX_FLD;
    this.superclass = FSNode;
    this.superclass(id, name, folder_id);
}

/* Object factory */

function createCategory(id, name, parent_id)
{
    var cat = new FSCategory(id, name, parent_id);

    if (cat.elem.parentNode == null)
    {
        //log("Category " + parent_id + " not found for sub-category " + id);
        ge("orphanage").appendChild(cat.elem);
    }
    else
    {
        flashElem(cat.elem);
    }
}

function createDocument(id, title, category_id, alias, summary)
{
    var doc = new FSDocument(id, title, category_id, alias, summary);

    if (doc.elem.parentNode == null)
    {
        log("Category " + category_id + " not found for document " + id);
    }
    else
    {
        flashElem(doc.elem);
    }
}

function createPage(id, title, document_id, page_index)
{
    var page = new FSPage(id, title, document_id, page_index);

    if (page.elem.parentNode == null)
    {
        log("Document " + document_id + " not found for page " + id);
    }
    else
    {
        flashElem(page.elem);
    }
}

function createFolder(id, name, parent_id)
{
    var fld = new FSFolder(id, name, parent_id);

    if (fld.elem.parentNode == null)
    {
        //log("Folder " + parent_id + " not found for sub-folder " + id);
        ge("orphanage").appendChild(fld.elem);
    }
    else
    {
        flashElem(fld.elem);
    }
}

function createResource(id, name, folder_id)
{
    var rsc = new FSResource(id, name, folder_id);

    if (rsc.elem.parentNode == null)
    {
        log("Folder " + folder_id + " not found for resource " + id);
    }
    else
    {
        flashElem(rsc.elem);
    }
}

/* Node manipulation */

function moveElem(elem_id, parent_id)
{
    var elem = ge(elem_id);
    var fs_node = elem.fs_node;

    fs_node.setParent(parent_id);
    
    flashElem(elem);
}

function insertElemBefore(elem, before_elem)
{
    var parent = elem.parentNode;
    var fs_node = elem.fs_node;
    
    fs_node.elem = parent.insertBefore(parent.removeChild(elem), before_elem);
    
    return fs_node.elem;
}

function swapElem(elem_id, vec)
{
    var elem = ge(elem_id);
    var swap_elem;
    
    if (vec == -1)
    {
        swap_elem = elem.previousSibling;
        elem = insertElemBefore(elem, swap_elem);
    }
    else
    {
        swap_elem = insertElemBefore(elem.nextSibling, elem);
    }

    // Assume page!
    elem.fs_node.page_index += vec;
    swap_elem.fs_node.page_index -= vec;
    
    flashElem(elem);
}

function deleteElem(elem_id)
{
    var elem = ge(elem_id);
    var fs_node = elem.fs_node;
    var parent_id = fs_node.parent_id;

    elem.parentNode.removeChild(elem);

    flashElem(ge(fs_node.parent_elem_id));
}

function updateCategory(id, name)
{
    var cat_elem = getCategoryElem(id);
    cat_elem.fs_node.setCaption(name);
    flashElem(cat_elem);
}

function updateDocument(id, title, alias, summary)
{
    var doc_elem = getDocumentElem(id); 

    with (doc_elem.fs_node)
    {
        setCaption(title);
        // alias
        setSummary(summary);
    }
    
    flashElem(doc_elem);
}

function updatePage(id, title)
{
    var page_elem = getPageElem(id);
    page_elem.fs_node.setCaption(title);
    flashElem(page_elem);
}

function updateFolder(id, name)
{
    var fld_elem = getFolderElem(id);
    fld_elem.fs_node.setCaption(name);
    flashElem(fld_elem);
}

/* UI operations */

function toggleCollapse(elem)
{
    //var branch = elem.getElementsByTagName("div")[2];
    var branch = getChildById(elem, "branch");
    var icons = elem.getElementsByTagName("img");

    if (branch.style.display == "none")
    {
        icons[0].style.display = "inline";
        icons[1].style.display = "none";
        branch.style.display = "block";
    }
    else
    {
        icons[0].style.display = "none";
        icons[1].style.display = "inline";
        branch.style.display = "none";
    }
}

function elemFlashTick(elem_id, tick_cnt)
{
    var elem = ge(elem_id);
    
    if (elem == undefined)
    {
        log("Elem " + elem_id + " not found");
        return;
    }
    
    elem.style.backgroundColor = (tick_cnt & 1) == 1 ? "transparent" : 
        HILITE_COLOR;
    
    if (--tick_cnt > 0)
    {
        var cmd = "elemFlashTick(\"" + elem_id + "\", " + tick_cnt + ")";
        setTimeout(cmd, 200);
    }
}

function flashElem(elem) // -> flashElemById ?
{
    if (g_initialized)
    {
        elemFlashTick(elem.fs_node.elem_id, FLASH_CNT * 2);
    }
}

// delete fs_node; first dissociate from elem

/* Backend requests */
/* Backend callbacks */

function checkMoveTarget(node)
{
    var target_node = node.parentNode.parentNode;
    
    if (g_selected_elem != target_node &&
        g_selected_elem.fs_node.parent_id != target_node.fs_node.id &&
        !hasChild(g_selected_elem, target_node) &&
        g_selected_elem.fs_node.canBeUnder(target_node.fs_node))        
    {
        parent.content.document.location =
            g_selected_elem.fs_node.pfx + "move.php" +
            "?parent_id=" + target_node.fs_node.id +
            "&id=" + g_selected_elem.fs_node.id;

        return;
    }

    clearSelection();
}

/* UI event handlers */

function handleNodeCollapse(node)
{
    toggleCollapse(node.parentNode.parentNode);
    
    return false;
}

function handleNodeClick(node)
{
    var content = getContent();
    
    if (content != undefined && content.performMove != undefined)
    {
        var target_node = node.parentNode.parentNode;
        
        if (g_selected_elem != target_node &&
            g_selected_elem.fs_node.parent_id != target_node.fs_node.id &&
            !hasChild(g_selected_elem, target_node) &&
            g_selected_elem.fs_node.canBeUnder(target_node.fs_node))        
        {
            content.performMove(target_node.fs_node.id);
        }
    }
    else
    {
        return true;
    }

    return false; 
}

function clearSelection()
{
    if (g_selected_elem != undefined)
    {
        g_selected_elem.style.backgroundColor = "transparent";
        g_selected_elem = undefined;
        g_selected_action = undefined;
    }
}

function selectElem(elem, action)
{
    if (g_selected_elem != undefined && g_selected_elem != elem)
    {
        clearSelection();
    }
    
    if (elem != undefined)
    {
        elem.style.backgroundColor = HILITE_COLOR;
        g_selected_elem = elem;
        g_selected_action = action;
    }
}

function handleAddNode(node, pfx)
{
    //var fs_node = node.parentNode.parentNode.parentNode.fs_node;
    var fs_node = g_fs_node;
    
    parent.content.document.location = (pfx == null ? fs_node.child_pfx : pfx) +
        "form.php?parent_id=" + fs_node.id;
    
    return false;
}

function handleEditNode(node)
{
    var fs_node = node.parentNode.parentNode.parentNode.fs_node;
    
    parent.content.document.location = fs_node.pfx + "form.php" +
        "?id=" + fs_node.id;

    return false;
}

/* Initialization */

function relocateOrphans()
{
    var orphanage = ge("orphanage");
    var orphan = orphanage.firstChild;
    
    while (orphan != undefined)
    {
        var next_orphan = orphan.nextSibling;

        orphan.fs_node.setParent(orphan.fs_node.parent_id);
        
        orphan = next_orphan;
    }
}

function getContent()
{
    return parent != undefined && parent.content != undefined &&
        parent.content.g_initialized ? parent.content : undefined;
}

function checkContent()
{
    var content = getContent();
    
    if (content != undefined && content.checkNav != undefined)
    {
        content.checkNav();
    }
}

/* Context menu */

function handlePopup(popup_info)
{
    var elem = popup_info.source.parentNode.parentNode;
    //var elem = getParentById(source, "node").fs_node;

    //selectElem(elem);
    
    setAnchors(popup_info.popup, elem.fs_node.id);
    
    var overflowx = popup_info.x + popup_info.popup.clientWidth -
        document.body.clientWidth; // std?
    
    if (overflowx > 0)
    {
        popup_info.x -= overflowx;
    }
    
    var overflowy = popup_info.y + popup_info.popup.clientHeight -
        document.body.clientHeight; // std?
    
    if (overflowy > 0)
    {
        popup_info.y -= overflowy;
    }
    
    return true;
}
