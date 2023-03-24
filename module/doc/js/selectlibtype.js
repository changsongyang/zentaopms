$(function()
{
    loadDocLibs(defaultType);
})

/**
 * Redirect the parent window.
 *
 * @param  string objectType
 * @param  int    libID
 * @param  string docType
 * @access public
 * @return void
 */
function redirectParentWindow(objectType, libID, docType)
{
    if(objectType == 'api')
    {
        parent.$.closeModal(function()
        {
            new parent.$.zui.ModalTrigger({
                iframe : createLink('api', 'create', 'libID=' + libID),
                width: '85%'
            }).show();
        });
        return false;
    }

    config.onlybody = 'no';
    var link = createLink('doc', 'create', 'objectType=' + objectType + '&objectID=0&libID=' + libID + '&moduleID=0&docType=' + docType + '&fromGlobal=true') + '#app=doc';
    window.parent.$.apps.open(link);
}

/**
 * Load doc libs by type.
 *
 * @param  string  type
 * @return void
 */
function loadDocLibs(type)
{
    $.get(createLink('doc', 'ajaxGetLibsByType', "type=" + type), function(data)
    {
        $('#lib').replaceWith(data);
        $('#lib_chosen').remove();
        $('#lib').chosen();
        $('#lib').siblings('div').css('width', 'calc(100% - 25px)');

        if($('#lib').find('option').length == 0)
        {
            $('#submit').attr('disabled', 'disabled');
        }
        else
        {
            $('#submit').removeAttr('disabled');
        }
    })

    $('#docType').toggleClass('hidden', type == 'api');
}
