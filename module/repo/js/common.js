/**
 * Swtich repo.
 *
 * @param  int    $repoID
 * @param  string $module
 * @param  string $method
 * @access public
 * @return void
 */
function switchRepo(repoID, module, method)
{
    if(typeof(eventKeyCode) == 'undefined') eventKeyCode = 0;
    if(eventKeyCode > 0 && eventKeyCode != 13) return false;

    /* The project id is a string, use it as the project model. */
    if(isNaN(repoID))
    {
        $.cookie('projectMode', repoID, {expires:config.cookieLife, path:config.webRoot});
        repoID = 0;
    }

    if(method != 'settings') method ="browse";
    link = createLink(module, method, 'repoID=' + repoID);
    location.href=link;
}

/**
 * Switch branch for git.
 *
 * @param  string $branchID
 * @access public
 * @return void
 */
function switchBranch(branchID)
{
    $.cookie('repoBranch', branchID, {expires:config.cookieLife, path:config.webRoot});
    $.cookie('repoRefresh', 1, {expires:config.cookieLife, path:config.webRoot});
    location.href=location.href;
}

/**
 * Limit select two.
 * @return void
 */
if($("input:checkbox[name='revision[]']:checked").length < 2)
{
    $("input:checkbox[name='revision[]']:lt(2)").attr('checked', 'checked');
}
$("input:checkbox[name='revision[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
$("input:checkbox[name='revision[]']").click(function(){
    var checkNum = $("input:checkbox[name='revision[]']:checked").length;
    if (checkNum >= 2)
    {
        $("input:checkbox[name='revision[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
        $('#submit').removeAttr('disabled');
    }
    else
    {
        $("input:checkbox[name='revision[]']").each(function(){$(this).attr("disabled", false)});
        $('#submit').attr('disabled', 'disabled');
    }
});

var distance = 0;

/**
 * Aarrow tabs area.
 *
 * @param  string domID
 * @param  number shift 1|-1
 * @param  bool   hideRightBtn
 * @access public
 * @return void
 */
function arrowTabs(domID, shift, hideRightBtn)
{
    if($('#' + domID).html() == '') return;

    $('.btn-right, .btn-left').show();
    if(hideRightBtn) $('.btn-right').hide();

    var tabItemWidth = $('#' + domID + ' > .tabs-navbar > .nav-tabs')[0].clientWidth;
    var tabsWidth    = $('#' + domID + '')[0].clientWidth;
    if(tabItemWidth < tabsWidth)
    {
        $('.btn-right, .btn-left').hide();
        return;
    }

    distance += tabsWidth * shift * 0.2;
    if(distance > 0) distance = 0;
    if(distance == 0)
    {
        $('.btn-left').hide();
    }

    if((tabItemWidth + distance) < tabsWidth * 0.75)
    {
        $('.btn-right').hide();
        return arrowTabs(domID, 1, true);
    }

    $('#' + domID + ' > .tabs-navbar > .tabs-nav')[0].style.transform = 'translateX('+ distance +'px)';
}

$(function()
{
    $(document).on('click', '.ajaxPager', function()
    {
        $('#sidebar .side-body').load($(this).attr('data-href'));
        return false;
    })

    if($("main").is(".hide-sidebar"))
    {
        $(".sidebar-toggle").children().attr("class", "icon icon-angle-left");
    }
    else
    {
        $("#sidebar").bind("click", function ()
        {
            $(".sidebar-toggle").children().attr("class", "icon icon-angle-left");
            $(this).unbind();
        });
    }
})

/**
 * Set pane height.
 *
 * @access public
 * @return void
 */
function setHeight()
{
    var paneHeight = $(window).height() - 120;
    $('#fileTabs .tab-pane').css('height', paneHeight + 'px')

    paneHeight += 45;
    if($('.label-exchange').html()) paneHeight -= 50;
    $('#filesTree').css('height', paneHeight + 'px')
}
setHeight();

$(document).on('click', '.repoFileName', function()
{
    var path  = encodeURIComponent($(this).data('path'));
    var name  = $(this).text();
    var $tabs = $('#fileTabs').data('zui.tabs');
    if(openedFiles.indexOf(path) == -1) openedFiles.push(path);

    $tabs.open(createTab(name, path));
    setHeight();
    arrowTabs('fileTabs', -2);
});

/* Remove file path for opened files. */
$('#fileTabs').on('onClose', function(event, tab) {
    var filepath = decodeURIComponent(Base64.decode(tab.id.replaceAll('-', '=')));
    var index    = openedFiles.indexOf(filepath);
    if(index > -1)
    {
        openedFiles.splice(index, 1)
        $('[data-path="' + filepath + '"]').closest('li').removeClass('selected');
    }

    if(index == openedFiles.length) arrowTabs('fileTabs', -2);
});

/* Append file path into the title. */
$('#fileTabs').on('onLoad', function(event, tab) {
    var filepath = Base64.decode(tab.id.replaceAll('-', '='));
    $('#tab-nav-item-' + tab.id).attr('title', decodeURIComponent(filepath));
});
