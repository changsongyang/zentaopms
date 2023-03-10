$(function()
{
    setHeight();
    $(window).resize(setHeight);
});

/**
 * Set pane height.
 *
 * @access public
 * @return void
 */
function setHeight()
{
    var paneHeight = $(window).height() - 100;
    $('#sidebar .module-tree,#mainContent .module-content').css('height', paneHeight);
}
