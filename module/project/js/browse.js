$(function()
{
    $('#projectMine').click(function()
    {
        var mine = $(this).is(':checked') ? 1 : 0;
        $.cookie('projectMine', mine, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
});

$(".tree .active").parent('li').addClass('active');
