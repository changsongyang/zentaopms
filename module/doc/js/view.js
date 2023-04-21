$(function()
{
    if($.cookie('isFullScreen') == 1) fullScreen();

    $('.menu-actions > a').click(function()
    {
        $(this).parent().hasClass('open') ? $(this).css('background', 'none') : $(this).css('background', '#f1f1f1');
    })

    $('.menu-actions > a').blur(function() {$(this).css('background', 'none');})

    $('.comment-edit-form').ajaxForm({
        success: function(data) {
            location.reload();
        }
    })
})

/**
 * Ajax delete doc.
 *
 * @param  string $link
 * @param  int    $replaceID
 * @param  stirng $notice
 * @access public
 * @return void
 */
function ajaxDeleteDoc(link, replaceID, notice)
{
    if(confirm(notice))
    {
        $.get(link, function(data)
        {
            location.href = JSON.parse(data).locate;
        });
    }
}

/**
 * Delete a file.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href = createLink('file', 'delete', 'fileID=' + fileID);
}

/**
 * Display the document in full screen.
 *
 * @access public
 * @return void
 */
function fullScreen()
{
    var element       = document.getElementById('content');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#mainActions').removeClass('hidden');
            $('#content').addClass('scrollbar-hover');
            $('#content .actions').addClass('hidden');
            $('#content .file-image .right-icon').addClass('hidden');
            $('#content .detail').eq(1).addClass('hidden');
            $.cookie('isFullScreen', 1);
        };

        var whenFailEnterFullscreen = function(error)
        {
            $.cookie('isFullScreen', 0);
        };

        try
        {
            var result = requestMethod.call(element);
            if(result && (typeof result.then === 'function' || result instanceof window.Promise))
            {
                result.then(afterEnterFullscreen).catch(whenFailEnterFullscreen);
            }
            else
            {
                afterEnterFullscreen();
            }
        }
        catch (error)
        {
            whenFailEnterFullscreen(error);
        }
    }

    $('.main-col iframe').css('min-height', $(window).height() + 'px');
    adjustPageTitleWidth();
}

/**
 * Exit full screen.
 *
 * @access public
 * @return void
 */
function exitFullScreen()
{
    $('#mainActions').addClass('hidden');
    $('#content').removeClass('scrollbar-hover');
    $('#content .actions').removeClass('hidden');
    $('#content .file-image .right-icon').removeClass('hidden');
    $('#content .detail').eq(1).removeClass('hidden');
    $('.main-col iframe').css('min-height', '380px');
    $.cookie('isFullScreen', 0);
    adjustPageTitleWidth();
}

/**
 * Adjust title width.
 *
 * @access public
 * @return void
 */
function adjustPageTitleWidth()
{
    var $titleContent = $('.detail-title.doc-title');
    var titleWidth    = $titleContent.width() - $titleContent.find('.info').width() - $titleContent.find('.actions').width() - $titleContent.find('#editorBox').width();
    $titleContent.find('.flex-left > .title').css('max-width', titleWidth + 'px');
}

document.addEventListener('fullscreenchange', function (e)
{
    if(!document.fullscreenElement) exitFullScreen();
});

document.addEventListener('webkitfullscreenchange', function (e)
{
    if(!document.webkitFullscreenElement) exitFullScreen();
});

document.addEventListener('mozfullscreenchange', function (e)
{
    if(!document.mozFullScreenElement) exitFullScreen();
});

document.addEventListener('msfullscreenChange', function (e)
{
    if(!document.msfullscreenElement) exitFullScreen();
});

$(function()
{

    $(document).keydown(function(event)
    {
        if($.cookie('isFullScreen') == 1)
        {
            if(event.keyCode == 37) $('#prevPage').click();
            if(event.keyCode == 39) $('#nextPage').click();
        }
    });

    $('.outline').height($('.article-content').height());

    $('body').on('click', '.outline-toggle i.icon-menu-arrow-left', function()
    {
        $('.outline').css({'min-width' : '180px', 'border-left' : '2px solid #efefef'});
        $(this).removeClass('icon-menu-arrow-left').addClass('icon-menu-arrow-right').css('left', '-9px');
        $('.outline').removeClass('hidden');
        $('.outline-content').show();
        if($('#sidebar>.cell').is(':visible')) $('#sidebar .icon.icon-menu-arrow-right').trigger("click");
    }).on('click', '.outline-toggle i.icon-menu-arrow-right', function()
    {
        $(this).removeClass('icon-menu-arrow-right').addClass('icon-menu-arrow-left');
        $('.outline').css({'min-width' : '180px', 'border-left' : 'none'});
        $('.outline').addClass('hidden');
    }).on('click', '#outline li', function(e)
    {
        $('#outline li.active').removeClass('active');
        $(e.target).closest('li').addClass('active');
    }).on('click', '.comment-edit-form .btn-hide-form', function()
    {
        $('.comment-edit-form #submit').attr('disabled', false);
    }).on('click', '#docVersionMenu.diff > .drop-body > li', function(e)
    {
        e.stopPropagation();
    }).on('click', '#docVersionMenu #changeBtn', function(e)
    {
        $('#docVersionMenu').addClass('diff');
        e.stopPropagation();
    }).on('click', '#hisTrigger', function()
    {
        var $history = $('#history');
        var $icon = $(this);
        if($history.hasClass('hidden'))
        {
            $history.removeClass('hidden');
            $icon.addClass('text-primary');
        }
        else
        {
            $history.addClass('hidden');
            $icon.removeClass('text-primary');
        }
        adjustPageTitleWidth();
    }).on('click', '#closeBtn', function()
    {
        $('#history').addClass('hidden');
        $('#hisTrigger').removeClass('text-primary');
        adjustPageTitleWidth();
    });

    $('#outline li.has-list').addClass('open in');
    $('#outline li.has-list > i + ul').prev('i').remove();
    $('.outline-toggle i.icon-menu-arrow-left').trigger('click');

    $(document).on('click', '.detail-content a', function(event)
    {
        var target = $(this).attr('target');
        if($.cookie('isFullScreen') == 1 && target != '_blank') exitFullScreen();
    })

    /* Update doc content silently on switch doc version, story #40503 */
    $(document).on('click', '.doc-version-menu a, #mainActions .container a', function(event)
    {
        var $tmpDiv = $('<div>');
        $tmpDiv.load($(this).data('url') + ' #mainContent', function()
        {
            $('#content').html($tmpDiv.find('#content').html());
            $('#sidebarContent').html($tmpDiv.find('#sidebarContent').html());
            $('#actionbox .histories-list').html($tmpDiv.find('#actionbox .histories-list').html());
            if($.cookie('isFullScreen') == 1) fullScreen();
            $('#content [data-ride="tree"]').tree();
            $('#outline li.has-list').addClass('open in');
            $('#outline li.has-list > i + ul').prev('i').remove();
            if($('#markdownContent').val())
            {
                var simplemde = new SimpleMDE({element: $("#markdownContent")[0],toolbar:false, status: false});
                simplemde.value(String($('#markdownContent').val()));
                simplemde.togglePreview();
            }
            $('#docExport').attr('href', createLink('doc', exportMethod, 'libID=' + libID + '&moduleID=0&docID=' + docID + '&version=' + $('#content .doc-title .version').data('version')));
            if($('.files-list').length) $('#content .detail-content.article-content').css('height', 'calc(100vh - 300px)');
        });
    })

    $('#history').append('<a id="closeBtn" href="###" class="btn btn-link"><i class="icon icon-close"></i></a>');

    $('#history').find('.btn.pull-right').removeClass('pull-right');
    if($('.files-list').length) $('#content .detail-content.article-content').css('height', 'calc(100vh - 300px)');
    $('.outline .outline-toggle i.icon-menu-arrow-left').trigger("click");
    adjustPageTitleWidth();
})
