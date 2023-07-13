window.loadProduct = function()
{
    const $product  = $('#product').zui('picker');
    const productID = $product.$.value;

    if(twins && productID != oldProductID)
    {
        confirmRelievedTwins = confirm(relievedTwinsTip);
        if(!confirmRelievedTwins)
        {
            $product.$.setValue(oldProductID.toString());
            return false;
        }
    }

    if(parentStory)
    {
        confirmLoadProduct = confirm(moveChildrenTips);
        if(!confirmLoadProduct)
        {
            $product.$.setValue(oldProductID.toString());
            return false;
        }
    }

    loadProductBranches(productID);
    loadProductReviewers(productID);
    loadURS();

    if(storyType == 'story')
    {
        var storyLink = $.createLink('story', 'ajaxGetParentStory', 'productID=' + productID + '&labelName=parent');
        var $parent   = $('#parent').zui('picker');
        $.get(storyLink, function(data)
        {
            $parent.render(JSON.parse(data));
        });
    }
}

window.linkStories = function(e)
{
    var storyIdList = [];
    $('#linkStoriesBox input').each(function()
    {
        storyIdList.push($(this).val());
    });
    storyIdList = storyIdList.join(',');

    var link = $.createLink('story', 'linkStories', 'storyID=' + storyID + '&browseType=&excludeStories=' + storyIdList);
    if(storyType != 'story') link = $.createLink('story', 'linkRequirements', 'storyID=' + storyID + '&browseType=&excludeStories=' + storyIdList);

    $('#linkStoriesLink').attr('data-url', link);
}

window.changeNeedNotReview = function(obj)
{
    $this = $(obj);
    $('#reviewer').val($this.prop('checked') ? '' : lastReviewer).attr('disabled', $this.prop('checked') ? 'disabled' : null);
};

window.changeReviewer = function()
{
    if(storyStatus == 'reviewing')
    {
        if(!$('#reviewer').val())
        {
            zui.Modal.alert(reviewerNotEmpty);
            $('#reviewer').val(reviewers);
        }
        else
        {
            reviewers = $('#reviewer').val();
        }
    }
    else
    {
        if(!$('#reviewer').val())
        {
            $('#needNotReview').prop('checked', true);
            changeNeedNotReview($('#needNotReview'));
        }
    }
}

if(!$('#reviewer').val()) changeNeedNotReview($('#needNotReview'));

function loadProductBranches(productID)
{
    var param   = 'all';
    var isTwins = 'no';
    var branch  = 0;

    var $product   = $('#product');
    var $branchBox = $product.closest('.row').find('.branchIdBox');
    $branchBox.addClass('hidden');
    $.get($.createLink('branch', 'ajaxGetBranches', "productID=" + productID + "&oldBranch=0&param=" + param + "&projectID=" + executionID + "&withMainBranch=1&isTwins=" + isTwins), function(data)
    {
        if(data)
        {
            $branchBox.html("<div class='picker-box' id='branch'></div>").removeClass('hidden');
            $branch = new zui.Picker('.branchIdBox #branch', {items: JSON.parse(data), name: 'branch', onChange: "loadBranch()"});
            branch  = $branch.$.value;
        }

        loadProductModules(productID, branch);
        loadProductPlans(productID, branch);
    });
}

function loadProductReviewers(productID)
{
    var reviewerLink  = $.createLink('product', 'ajaxGetReviewers', 'productID=' + productID + '&storyID=' + storyID);
    var needNotReview = $('#needNotReview').prop('checked');
    $('.reviewerBox').load(reviewerLink, function()
    {
        if(needNotReview) $('.reviewerBox #reviewer').attr('disabled', 'disabled');
    });
}
