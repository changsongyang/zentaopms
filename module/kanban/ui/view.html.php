<?php
namespace zin;

$laneCount   = 0;
$columnCount = array();
$parentCols  = array();
foreach($kanbanList as $regionID => $region)
{
    foreach($region['items'] as $groupID => $group)
    {
        $group['getLane']   = jsRaw('window.getLane');
        $group['getCol']    = jsRaw('window.getCol');
        $group['getItem']   = jsRaw('window.getItem');
        $group['colProps']  = array('actions' => jsRaw('window.getColActions'));
        $group['laneProps'] = array('actions' => jsRaw('window.getLaneActions'));
        $group['itemProps'] = array('actions' => jsRaw('window.getItemActions'));

        foreach($group['data']['cols'] as $col) $parentCols[$col['id']] = $col['parent'];
        foreach($group['data']['items'] as $colGroup)
        {
            foreach($colGroup as $colID => $items)
            {
                if(!isset($columnCount[$colID])) $columnCount[$colID] = 0;
                $columnCount[$colID] += count($items);

                if(isset($parentCols[$colID]) && $parentCols[$colID] > 0)
                {
                    if(!isset($columnCount[$parentCols[$colID]])) $columnCount[$parentCols[$colID]] = 0;
                    $columnCount[$parentCols[$colID]] += count($items);
                }
            }
        }

        $kanbanList[$regionID]['items'][$groupID] = $group;
    }

    $laneCount += $region['laneCount'];
}

jsVar('laneCount',  $laneCount);
jsVar('kanbanLang', $lang->kanban);
jsVar('kanbanID', $kanbanID);
jsVar('columnCount', $columnCount);

zui::kanbanList
(
    set::key('kanban'),
    set::items($kanbanList),
    set::height('calc(100vh - 80px)')
);
