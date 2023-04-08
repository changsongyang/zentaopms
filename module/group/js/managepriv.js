function showPriv(value)
{
  location.href = createLink('group', 'managePriv', "type=byGroup&param="+ groupID + "&menu=&version=" + value);
}

/**
 * Control the packages select control for a module.
 *
 * @param   string $module
 * @access  public
 * @return  void
 */
function setModulePackages(module)
{
    $('#packageBox select').addClass('hidden');                      // Hide all select first.
    $('#packageBox select').val('');                                 // Unselect all select.
    $("select[data-module='" + module + "']").removeClass('hidden'); // Show the action control for current module.

    updatePrivList('module', module);
}

/**
 * Control the actions select control for a package.
 *
 * @access public
 * @return void
 */
function setActions()
{
    $('#actionBox select').val('');

    var hasSelectedPackage = $('#packageBox select').not('.hidden').val().join(',');

    updatePrivList('package', hasSelectedPackage);

}

function setNoChecked()
{
    var noCheckValue = '';
    $(':checkbox').each(function()
    {
        if(!$(this).prop('checked') && $(this).next('span').attr('id') != undefined) noCheckValue = noCheckValue + ',' + $(this).next('span').attr('id');
    })
    $('#noChecked').val(noCheckValue);
}

/**
 * Update the action box when module or package selected.
 *
 * @param  string  parentType  module|package
 * @param  string  parentList
 * @access public
 * @return void
 */
function updatePrivList(parentType, parentList)
{
    var selectedModule = $('#module').val();
    $.get(createLink('group', 'ajaxGetPrivByParents', 'module=' + selectedModule + '&parentType=' + parentType + '&parentList=' + parentList), function(data)
    {
        $('#actions').replaceWith(data);
    })
}

/**
 * Change parent item checked.
 *
 * @access public
 * @return void
 */
function changeParentChecked($item, moduleName, packageID)
{
    var moduleAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName +']').length;
    var moduleSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName +']').find('[checked=checked]').length;
    var $moduleItem       = $item.closest('tbody').find('.module[data-module=' + moduleName +']');
    if(moduleSelectPrivs == 0)
    {
        $moduleItem.find('input[type=checkbox]').removeAttr('checked');
        $moduleItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(moduleAllPrivs == moduleSelectPrivs)
    {
        $moduleItem.find('input[type=checkbox]').attr('checked', 'checked');
        $moduleItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $moduleItem.find('input[type=checkbox]').removeAttr('checked');
        $moduleItem.find('label').addClass('checkbox-indeterminate-block');
    }

    if(packageID == '') return;

    var packageAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName +'][data-package=' + packageID +']').length;
    var packageSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName +'][data-package=' + packageID +']').find('[checked=checked]').length;
    var $packageItem       = $item.closest('tbody').find('.package[data-module=' + moduleName +'][data-package=' + packageID +']');
    if(packageSelectPrivs == 0)
    {
        $packageItem.find('input[type=checkbox]').removeAttr('checked');
        $packageItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(packageAllPrivs == packageSelectPrivs)
    {
        $packageItem.find('input[type=checkbox]').attr('checked', 'checked');
        $packageItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $packageItem.find('input[type=checkbox]').removeAttr('checked');
        $packageItem.find('label').addClass('checkbox-indeterminate-block');
    }
}

/**
  * Init comp recomend-tree.
  *
  * @param  array  data
  * @access public
  * @return void
  */
function initRecomendTree(data)
{
    $(".menuTree.recommend").tree(
    {
        data: data,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href="#" title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden" data-type="depend" data-privid=' + item.privID + ' data-relationpriv=' + item.relationPriv + '></i>') +  '</a>');
            // $li.append('<input data-has-children="' + (item.children ? !!item.children.length : false) + '"' + (item.children ? '' : 'data-type="depend" data-privid="' + item.privID + '" data-relationpriv="' + item.relationPriv + '"') + 'type="checkbox" name="recommendPrivs[]" value="' + item.id + '" title="' + item.title + '" id="recommendPrivs[' + item.id + ']"');
            if(item.active) $li.addClass('active open in');
        }
    });
}

/**
  * Init comp depend-tree.
  *
  * @param  array  data
  * @access public
  * @return void
  */
function initDependTree(data)
{
    $(".menuTree.depend").tree(
    {
        data: data,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href="#" title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden" data-type="recommend" data-privid=' + item.privID + ' data-relationpriv=' + item.relationPriv + '></i>') + '</a>');
            if(item.active) $li.addClass('active open in');
        }
    });
}

/**
  * update comp tree after click checkbox-label.
  *
  * @param  obj objTree
  * @access public
  * @return void
  */
function updatePrivTree(privList)
{
    if(privList == null)
    {
        privList = new Array();
        $('#privList .group-item input:checked').each(function()
        {
            privList.push($(this).closest('.group-item').attr('data-id'));
        });
    }

    $.ajax(
    {
        url: createLink('group', 'ajaxGetRelatedPrivs'),
        dataType: 'json',
        method: 'post',
        data: {"privList" : privList.toString()},
        success: function(data)
        {
            if(data.depend == undefined || data.depend.length == 0)
            {
                $('.side .menuTree.depend').empty();
                $('.side .menuTree.depend').closest('.priv-panel').addClass('hidden');
            }
            else
            {
                $(".menuTree.depend").data('zui.tree').reload(data.depend);
                $('.side .menuTree.depend').closest('.priv-panel').removeClass('hidden');
            }

            if(data.recommend == undefined || data.recommend.length == 0)
            {
                $('.side .menuTree.recommend').empty();
                $('.side .menuTree.recommend').closest('.priv-panel').addClass('hidden');
            }
            else
            {
                $(".menuTree.recommend").data('zui.tree').reload(data.recommend);
                $('.side .menuTree.recommend').closest('.priv-panel').removeClass('hidden');
            }

            resizeContainer();
        }
    });
}

function checkAllChange()
{
    var id      = $(this).find('input[type=checkbox]').attr('id');
    var checked = $(this).find('input[type=checkbox]').prop('checked');

    if(id == 'allChecker')
    {
        $('input[type=checkbox]').prop('checked', checked);

        if(checked) $('input[type=checkbox]').attr('checked', checked);
        if(!checked) $('input[type=checkbox]').removeAttr('checked');
        $('#privList tbody .checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        var moduleName = $(this).closest('th').attr('data-module');
        var packageID  = $(this).closest('th').hasClass('package') ? $(this).closest('th').attr('data-package') : '';
        var $children  = $(this).closest('th').hasClass('package') ? $(this).closest('tbody').find('[data-module=' + moduleName +'][data-package=' + packageID +']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

        $children.find('input[type=checkbox]').prop('checked', checked);
        $children.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');

        if(checked) $children.find('input[type=checkbox]').attr('checked', checked);
        if(!checked) $children.find('input[type=checkbox]').removeAttr('checked');

        changeParentChecked($(this), moduleName, packageID);
    }
    updatePrivTree(null);
}

/**
 * Resize container flex.
 *
 * @access public
 * @return void
 */
function resizeContainer()
{
    if($(".menuTree.depend").closest('.priv-panel').css('display') == 'none' && $(".menuTree.recommend").closest('.priv-panel').css('display') == 'none')
    {
        $('#mainContainer > .main').css('flex', '1 1 100%');
        $('#mainContainer > .side').css('flex', '1 1 0%');
        $('#mainContainer > .side').addClass('hidden');
    }
    else
    {
        $('#mainContainer > .main').css('flex', '1 1 75%');
        $('#mainContainer > .side').css('flex', '1 1 25%');
        $('#mainContainer > .side').removeClass('hidden');
    }
}

$(function()
{
    selectedPrivIdList = Object.values(selectedPrivIdList);

    relatedPrivData = $.parseJSON(relatedPrivData);
    initDependTree(relatedPrivData.depend);
    if(relatedPrivData.depend == undefined || relatedPrivData.depend == 0) $(".menuTree.depend").closest('.priv-panel').addClass('hidden');
    initRecomendTree(relatedPrivData.recommend);
    if(relatedPrivData.recommend == undefined || relatedPrivData.recommend == 0) $(".menuTree.recommend").closest('.priv-panel').addClass('hidden');

    resizeContainer();

    $('#privList > tbody > tr > th .check-all').change(checkAllChange);
    $('.priv-footer .check-all').change(checkAllChange);

    $('#privList > tbody > tr > td input[type=checkbox]').change(function()
    {
        var checked = $(this).prop('checked');
        if(checked)
        {
            $(this).attr('checked', 'checked');
        }
        else
        {
            $(this).removeAttr('checked');
        }
        var moduleName = $(this).closest('.group-item').attr('data-module');
        var packageID  = $(this).closest('.group-item').attr('data-package');
        changeParentChecked($(this), moduleName, packageID);

        var privID = $(this).closest('.group-item').attr('data-id');
        if(privID != 0)
        {
            var index = selectedPrivIdList.indexOf(privID);

            if(privID > 0 && index < 0 && checked) selectedPrivIdList.push(privID);
            if(privID > 0 && index > -1 && !checked) selectedPrivIdList.splice(index, 1);

            updatePrivTree(selectedPrivIdList);
        }
    });

    $('#submit').click(function()
    {
        $('#managePrivForm').submit();
    });
});
