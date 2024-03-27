$(document).off('click', '[data-formaction]').on('click', '[data-formaction]', function()
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('storyIdList[]', id));
    if($this.data('account')) postData.append('assignedTo', $this.data('account'));

    if($this.data('page') == 'batch')
    {
        postAndLoadPage($this.data('formaction'), postData);
    }
    else
    {
        $.ajaxSubmit({"url": $this.data('formaction'), "data": postData});
    }
});

$(document).off('click','#linkStoryByPlan button[type="submit"]').on('click', '#linkStoryByPlan button[type="submit"]', function()
{
    var planID = $('[name=plan]').val();
    if(planID)
    {
        $.ajaxSubmit({url: $.createLink('execution', 'importPlanStories', 'projectID=' + projectID + '&planID=' + planID)});
    }

    return false;
});

$(document).off('click', '.batchUnlinkStory').on('click', '.batchUnlinkStory', function(e)
{
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let batchUnlinkStoryURL = $.createLink('projectstory', 'batchUnlinkStory', 'projectID=' + projectID + '&stories=' + encodeURIComponent(checkedList.join(',')));
    $.get(batchUnlinkStoryURL, function(data)
    {
         try
         {
             data = JSON.parse(data);
             if(typeof data.result != 'undefined') return loadCurrentPage();
         }
         catch(error){}

         zui.Modal.open({id: 'batchUnlinkStoryBox'});
         $('#batchUnlinkStoryBox').html(data);
         $('#batchUnlinkStoryBox').off('mousedown', '.confirmBtn').on('mousedown', '.confirmBtn', function()
         {
             loadCurrentPage();
         });
    });
});

window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        const story = info.row.data;
        let html = '';
        if(typeof modulePairs[story.rawModule] != 'undefined') html += "<span class='label gray-pale rounded-xl clip'>" + modulePairs[story.rawModule] + "</span> ";
        if(story.parent > 0) if($.cookie.get('tab') == 'project') html += story.parentName + ' / ';

        let gradeLabel = gradeGroup[story.type][story.grade];
        if(!showGrade && story.grade < 2 && storyType == story.type) gradeLabel = '';
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(story.color) result[0].props.style = 'color: ' + story.color;
        if(html) result.unshift({html});
    }
    if(info.col.name == 'status' && result)
    {
        if(info.row.data.URChanged == '1')
        {
            result[0] = {html: "<span class='status-changed'>" + URChanged + "</span>"};
        }
        else
        {
            result[0] = {html: `<span class='status-${info.row.data.rawStatus}'>` + info.row.data.status + "</span>"};
        }
    }
    if(info.col.name == 'assignedTo' && info.row.data.status == 'closed')
    {
        delete result[0]['props']['data-toggle'];
        delete result[0]['props']['href'];
        result[0]['props']['className'] += ' disabled';
    }
    return result;
};

window.setStatistics = function(element, checkedIdList, pageSummary)
{
    if(checkedIdList == undefined || checkedIdList.length == 0) return {html: pageSummary};

    let total     = 0;
    let SRTotal   = 0;
    let estimate  = 0;
    let rate      = '0%';
    let hasCase   = 0;
    let rateCount = checkedIdList.length;

    const rows  = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIdList.includes(row.id))
        {
            const story = row.data;
            if(story.type != storyType) return;
            estimate   += parseFloat(story.estimate);
            if(story.caseCount > 0)
            {
                hasCase += 1;
            }
            else if(typeof story.isParent != 'undefined' && story.isParent)
            {
                rateCount -= 1;
            }

            if(storyType == story.type) total += 1;
            if(storyType == 'requirement' && story.type == 'story') SRTotal += 1;
        }
    })

    if(rateCount) rate = Math.round(hasCase / rateCount * 10000 / 100) + '' + '%';

    return {html: checkedSummary.replace('%total%', total).replace('%estimate%', estimate).replace('%rate%', rate).replace('%SRTotal%', SRTotal)};
};

window.setShowGrades = function()
{
    const showGrades = $('[name^=showGrades]').zui('picker').$.state.value;
    if(oldShowGrades == showGrades) return;

    const link = $.createLink('product', 'ajaxSetShowGrades', 'storyType=' + storyType + '&showGrades=' + showGrades);
    $.get(link, function()
    {
        loadCurrentPage();
    });
}
