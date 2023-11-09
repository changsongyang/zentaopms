window.getLane = function(lane)
{
    /* 看板只有一个泳道时，铺满全屏。 */
    if(laneCount < 2) lane.minHeight = window.innerHeight - 235;
}

/*
 * 构造看板泳道上的操作按钮。
 * Build action buttons on the kanban lane.
 */
window.getLaneActions = function(lane)
{
    return [{
        type: 'dropdown',
        icon: 'ellipsis-v',
        caret: false,
        items: [
            lane.actionList.includes('editLaneName') ? {text: kanbanLang.editLaneName, icon: 'edit',  url: $.createLink('kanban', 'editLaneName', 'id=' + lane.id), 'data-toggle': 'modal'} : null,
            lane.actionList.includes('editLaneColor') ? {text: kanbanLang.editLaneColor, icon: 'color',  url: $.createLink('kanban', 'editLaneColor', 'id=' + lane.id), 'data-toggle': 'modal'} : null,
            lane.actionList.includes('sortLane') ? {text: kanbanLang.sortLane, icon: 'move',  url: 'javascript:;', 'data-on': 'click', 'data-call': 'sortItems', 'data-params': 'event', 'data-type': 'lane', 'data-id': lane.id, 'data-region': lane.region} : null,
            lane.actionList.includes('deleteLane') ? {text: kanbanLang.deleteLane, icon: 'trash',  url: $.createLink('kanban', 'deleteLane', 'regionID=' + lane.region + '&kanbanID=' + kanbanID + '&id=' + lane.id), 'data-confirm': laneLang.confirmDelete, 'innerClass': 'ajax-submit'} : null,
        ],
    }];
}

window.getCol = function(col)
{
    /* 计算WIP。*/
    const limit = col.limit == -1 ? "<i class='icon icon-md icon-infinite'></i>" : col.limit;
    const cards = col.cards;

    col.subtitleClass = 'ml-1';

    let wip = `(${cards} / ${limit})`;

    if(col.limit != -1 && cards > col.limit)
    {
        col.subtitleClass += ' text-danger';
        wip += ' <i class="icon icon-exclamation-sign"></i>';
    }

    col.subtitle = {html: wip};
}

window.getColActions = function(col)
{
    let actionList = [];

    /* 父列不需要创建卡片相关的操作按钮。 */
    if(col.parent != '-1') actionList.push(
        {
            type: 'dropdown',
            icon: 'expand-alt text-primary',
            caret: false,
            items: buildColCardActions(col),
        }
    );

    actionList.push(
        {
            type: 'dropdown',
            icon: 'ellipsis-v',
            caret: false,
            items: buildColActions(col),
        }
    );

    return actionList;
}

/*
 * 构造看板上的创建卡片相关操作按钮。
 * Build create card related action buttons on the kanban.
 */
window.buildColCardActions = function(col)
{
    let actions = [];

    if(col.actionList.includes('createCard')) actions.push({text: kanbanLang.createCard, url: $.createLink('kanban', 'createCard', `kanbanID=${kanbanID}&reginID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal'});
    if(col.actionList.includes('batchCreateCard')) actions.push({text: kanbanLang.batchCreateCard, url: $.createLink('kanban', 'batchCreateCard', `kanbanID=${kanbanID}&reginID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(kanban.object.indexOf('cards') != -1) actions.push({text: kanbanLang.importCard, url: $.createLink('kanban', 'importCard', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal', 'data-size': 'lg'});
    if(kanban.object && kanban.object != 'cards' && vision != 'lite')
    {
        actions.push({type: 'divider'});
        actions.push(
            {
                text: kanbanLang.importAB,
                caret: true,
                items: [
                    {text: kanbanLang.importPlan, url: $.createLink('kanban', 'importPlan', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal', 'data-size': 'lg'},
                    {text: kanbanLang.importRelease, url: $.createLink('kanban', 'importRelease', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal', 'data-size': 'lg'},
                    {text: kanbanLang.importExecution, url: $.createLink('kanban', 'importExecution', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal', 'data-size': 'lg'},
                    {text: kanbanLang.importBuild, url: $.createLink('kanban', 'importBuild', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal', 'data-size': 'lg'},
                    {text: kanbanLang.importTicket, url: $.createLink('kanban', 'importTicket', `kanbanID=${kanbanID}&regionID=${col.region}&groupID=${col.group}&columnID=${col.id}`), 'data-toggle': 'modal', 'data-size': 'lg'},
                ]
            }
        );
    }

    return actions;
}

/*
 * 构造看板上的创建列、拆分列等操作按钮。
 * Build create column, split column and other action buttons on the kanban.
 */
window.buildColActions = function(col)
{
    let actions = [];

    if(col.actionList.includes('setColumn')) actions.push({text: kanbanLang.setColumn, url: $.createLink('kanban', 'setColumn', `columnID=${col.id}`), 'data-toggle': 'modal', 'icon': 'edit'});
    if(col.actionList.includes('setWIP')) actions.push({text: kanbanLang.setWIP, url: $.createLink('kanban', 'setWIP', `columnID=${col.id}`), 'data-toggle': 'modal', 'icon': 'alert'});
    if(col.actionList.includes('sortColumn')) actions.push({text: kanbanLang.sortColumn, url: 'javascript:;', 'icon': 'move', 'data-on': 'click', 'data-call': 'sortItems', 'data-params': 'event', 'data-type': 'column', 'data-id': col.id, 'data-region': col.region});
    actions.push({type: 'divider'});
    if(col.actionList.includes('splitColumn')) actions.push({text: kanbanLang.splitColumn, url: $.createLink('kanban', 'splitColumn', `columnID=${col.id}`), 'data-toggle': 'modal', 'icon': 'col-split'});
    if(col.actionList.includes('createColumn')) actions.push({text: kanbanLang.createColumnOnLeft, url: $.createLink('kanban', 'createColumn', `columnID=${col.id}&position=left`), 'data-toggle': 'modal', 'icon': 'col-add-left'});
    if(col.actionList.includes('createColumn')) actions.push({text: kanbanLang.createColumnOnRight, url: $.createLink('kanban', 'createColumn', `columnID=${col.id}&position=right`), 'data-toggle': 'modal', 'icon': 'col-add-right'});
    actions.push({type: 'divider'});
    if(col.actionList.includes('archiveColumn')) actions.push({text: kanbanLang.archiveColumn, url: $.createLink('kanban', 'archiveColumn', `columnID=${col.id}`), 'icon': 'card-archive', 'data-confirm': columnLang.confirmArchive, 'innerClass': 'ajax-submit'});
    if(col.actionList.includes('deleteColumn')) actions.push({text: kanbanLang.deleteColumn, url: $.createLink('kanban', 'deleteColumn', `columnID=${col.id}`), 'icon': 'trash', 'innerClass': 'ajax-submit', 'data-confirm': columnLang.confirmDelete});

    return actions;
}

/**
 * 渲染卡片内容。
 * Render card content.
 */
window.getItem = function(info)
{
    let begin = info.item.begin;
    let end   = info.item.end;
    let beginAndEnd = '';

    if(begin < '1970-01-01' && end > '1970-01-01')
    {
        beginAndEnd = end + ' ' + cardLang.deadlineAB;
    }
    else if(end < '1970-01-01' && begin > '1970-01-01')
    {
        beginAndEnd = begin + ' ' + cardLang.beginAB;
    }
    else if(begin > '1970-01-01' && end > '1970-01-01')
    {
        beginAndEnd = formatDate(begin) + ' ~ ' + formatDate(end);
    }

    const avatar = renderAvatar(info.item.avatarList);

    const content = `
      <div class='flex items-center'>
        <span class='pri-${info.item.pri}'>${info.item.pri}</span>
        <span class='date ml-1'>${beginAndEnd}</span>
        <div class='flex-1 flex justify-end' title='${info.item.realnames}'>${avatar}</div>
      </div>
    `;

    info.item.titleUrl   = $.createLink('kanban', 'viewCard', `id=${info.item.id}`);
    info.item.titleAttrs = {'data-toggle': 'modal', 'data-size' : 'lg', 'title' : info.item.title};

    info.item.content  = {html: content};
    if(info.item.color && info.item.color != '#fff') info.item.className = 'color-' + info.item.color.replace('#', '');
    if(kanban.performable == 1 && (info.item.fromType == '' || info.item.fromType == 'execution'))
    {
        info.item.footer = {html: "<div class='flex'><div class='circle progress mt-3' style='width:80%'><div class='progress-bar' style='width: " + info.item.progress + '%\'></div></div><div class="mt-2 ml-2">' + info.item.progress + '%' + '</div></div>'};
    }
}

function renderAvatar(avatarList)
{
    if(avatarList.length == 0)
    {
        return '<div class="avatar rounded-full size-xs ml-1 primary" title="' + cardLang.noAssigned + '" style="background: #ccc"><i class="icon icon-person"></i></div>';
    }

    let assignees = '<div class="avatar-group size-sm">';
    let count     = 0;
    for(let avatar of avatarList)
    {
        if(count > 2) break;
        assignees += `<div class="avatar rounded-full size-xs ml-1 primary">${avatar}</div>`;
        count ++;
    }

    if(count > 2) assignees += '<div class="avatar circle size-xs gray-pale">...</div>';
    assignees += '</div>';

    return assignees;
}

window.getItemActions = function(item)
{
    return [{
        type: 'dropdown',
        icon: 'ellipsis-v',
        caret: false,
        items: buildCardActions(item),
    }];
}

window.buildCardActions = function(item)
{
    let actions = [];

    if(item.actionList.includes('editCard'))   actions.push({text: kanbanLang.editCard, url: $.createLink('kanban', 'editCard', `id=${item.id}`), 'data-toggle': 'modal', 'icon': 'edit'});
    if(item.actionList.includes('deleteCard')) actions.push({text: kanbanLang.deleteCard, url: $.createLink('kanban', 'deleteCard', `id=${item.id}`), 'data-confirm': cardLang.confirmDelete, 'innerClass': 'ajax-submit', 'icon': 'trash'});

    if(kanban.performable == 1 && item.fromType == '')
    {
        if(item.status == 'done')
        {
            if(item.actionList.includes('activateCard')) actions.push({text: kanbanLang.activateCard, url: $.createLink('kanban', 'activateCard', `id=${item.id}`), 'icon': 'magic', 'data-toggle': 'modal'});
        }
        else
        {
            if(item.actionList.includes('finishCard')) actions.push({text: kanbanLang.finishCard, url: $.createLink('kanban', 'finishCard', `id=${item.id}`), 'icon': 'checked', 'innerClass': 'ajax-submit'});
        }
    }

    if(kanban.archived == '1' && item.actionList.includes('archiveCard')) actions.push({text: kanbanLang.archiveCard, url: $.createLink('kanban', 'archiveCard', `id=${item.id}`), 'icon': 'card-archive', 'data-confirm': cardLang.confirmArchive, 'innerClass': 'ajax-submit'});

    /* Append divider. */
    const editCardAction    = (item.actionList.includes('editCard') && item.fromType == '') ? true : false;
    const deleteCardAction  = item.actionList.includes('deleteCard');
    const archiveCardAction = (item.actionList.includes('archiveCard') && kanban.archived == '1') ? true : false;

    const performable  = kanban.performable == 1 ? true : false;

    const moveCardAction     = item.actionList.includes('moveCard');
    const setCardColorAction = item.actionList.includes('setCardColor');

    const basicActions = (editCardAction || deleteCardAction || archiveCardAction) ? true : false;
    const otherActions = (moveCardAction || setCardColorAction) ? true : false;

    if((performable || basicActions) && otherActions) actions.push({type: 'divider'});

    if(item.actionList.includes('moveCard'))
    {
        moveColumns = groupCols[item.group] || {};
        let moveCardItems = [];

        for(const toColID in moveColumns)
        {
            if(toColID == item.column) continue;
            moveCardItems.push({text: moveColumns[toColID], url: $.createLink('kanban', 'moveCard', `cardID=${item.id}&fromColID=${item.column}&toColID=${toColID}&fromLaneID=${item.lane}&toLaneID=${item.lane}&kanbanID=${kanbanID}`), 'innerClass': 'ajax-submit'});
        }

        actions.push({text: kanbanLang.moveCard, icon: 'move', items: moveCardItems});
    }
    if(item.actionList.includes('setCardColor'))
    {
        actions.push({text: kanbanLang.cardColor, 'icon': 'color', items: buildColorItems(item)});
    }

    return actions;
}

window.buildColorItems = function(card)
{
    let items = [];

    for (let index in colorList)
    {
        let color = colorList[index];
        let icon  = (card.color == color) || (!card.color && color == '#fff') ? "<i class='icon icon-check' style='position: relative; right: 3px'></i>" : '';
        items.push({text: {html: `<div class="colorbox" onclick="changCardColor('${card.id}', '${color}');"><div class='cardcolor' style='background:${color}'></div>` + cardLang.colorList[color] + ' ' + icon + '</div>'}});
    }

    return items;
}

window.changCardColor = function(cardID, color)
{
    color = color.replace('#', '');
    $.ajaxSubmit({url: $.createLink('kanban', 'setCardColor', 'cardID=' + cardID + '&color=' + color + '&kanbanID=' + kanbanID)});
}

window.canDrop = function(dragInfo, dropInfo)
{
    if(!dragInfo) return false;

    const column = this.getCol(dropInfo.col);
    const lane   = this.getLane(dropInfo.lane);
    if(!column || !lane) return false;

    /* 卡片的排序目前仅支持本单元格内排序 */
    if(dropInfo.type == 'item' && (dropInfo.col != dragInfo.item.col || dropInfo.lane != dragInfo.item.lane)) return false;

    /* 卡片可在同组内拖动。 */
    return dragInfo.item.group == column.group;
}

window.onDrop = function(changes, dropInfo)
{
    if(!dropInfo || !canMoveCard) return false;

    const item     = dropInfo['drag']['item'];
    const toColID  = dropInfo['drop']['col']
    const toLaneID = dropInfo['drop']['lane']

    if(item.col == toColID && item.lane == toLaneID)
    {
        let sortList = '';
        for(let i = 0; i < dropInfo['data']['list'].length; i++) sortList += dropInfo['data']['list'][i] + ',';
        url = $.createLink('kanban', 'sortCard', `kanbanID=${kanbanID}&laneID=${toLaneID}&columnID=${toColID}&cards=${sortList}`);
    }
    else
    {
        url = $.createLink('kanban', 'moveCard', `cardID=${item.id}&fromColID=${item.column}&toColID=${toColID}&fromLaneID=${item.lane}&toLaneID=${toLaneID}&kanbanID=${kanbanID}`);
    }
    $.ajaxSubmit({url});
}

window.clickRegionMenu = function(event)
{
    $('.regionMenu li').removeClass('active');
    $(event.target).closest('li').addClass('active');

    const regionID = $(event.target).closest('li').data('region');
    const url      = $.createLink('kanban', 'view', 'kanbanID=' + kanbanID + '&regionID=' + regionID);
    loadPartial(url, '#kanbanList');
}

function formatDate(inputDate)
{
    const date = new Date(inputDate);
    const formattedDate = `${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getDate().toString().padStart(2, '0')}`;

    return formattedDate;
}

window.loadMore = function(type, regionID)
{
    const method   = 'viewArchived' + type;
    const selector = '#archived' + type + 's';
    const link     = $.createLink('kanban', method, 'regionID=' + regionID);
    $(selector).load(link, function()
    {
        const height  = $(window).height() - $('#header').height();
        $(selector + ' .panel').css('height', height);
    });
}

window.fullScreen = function()
{
    let element       = document.getElementById('kanbanList');
    let requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;

    if(requestMethod)
    {
        let afterEnterFullscreen = function()
        {
            $('#kanbanList').addClass('fullscreen').css('background', '#fff');
            window.hideAllAction();
            $.cookie.set('isFullScreen', 1);
        };

        let whenFailEnterFullscreen = function()
        {
            exitFullScreen();
        };

        try
        {
            let result = requestMethod.call(element);
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
}

/**
 * Exit full screen.
 *
 * @access public
 * @return void
 */
function exitFullScreen()
{
    $('.btn').show();
    $.cookie.set('isFullScreen', 0);
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

window.hideAllAction = function()
{
    $('.btn').hide();
}

window.sortItems = function(event)
{
    const title      = $(event.target).closest('a').text();
    const objectType = $(event.target).closest('a').data('type');
    const objectID   = $(event.target).closest('a').data('id');
    const regionID   = $(event.target).closest('a').data('region');
    const url = $.createLink('kanban', 'ajaxGetSortItems', 'objectType=' + objectType + '&objectID=' + objectID);

    $.getJSON(url, function(items)
    {
        zui.Modal.sortView({
            title,
            items,
        }).then(orders => {
            if(orders)
            {
                if(objectType == 'region') link = $.createLink('kanban', 'sortRegion', 'regions=' + orders);
                if(objectType == 'column') link = $.createLink('kanban', 'sortColumn', 'regionID=' + regionID + '&columns=' + orders);
                if(objectType == 'lane')   link = $.createLink('kanban', 'sortLane',   'regionID=' + regionID + '&lanes=' + orders);
                $.getJSON(link, function(result)
                {
                    if(objectType == 'region') loadCurrentPage();
                    $('.kanban-list').zui('kanbanlist').$.getKanban(result.regionID).update(result.kanbanData);
                });
            }
        });
    });

}
