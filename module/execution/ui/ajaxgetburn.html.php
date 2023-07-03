<?php
declare(strict_types=1);
/**
 * The ajaxGetBurn view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('workHour', $lang->execution->workHour);
panel
(
    div
    (
        set::class('flex flex-nowrap justify-between'),
        div
        (
            set('class', 'panel-title'),
            $execution->name . $lang->execution->burn,
            isset($execution->delay) ? label
            (
                setClass('danger-pale ring-danger ml-1'),
                $lang->execution->delayed
            ) : null,
        ),
        common::hasPriv('execution', 'burn') ? btn
        (
            setClass('ghost text-gray'),
            set::url(createLink('execution', 'burn', "executionID={$execution->id}")),
            $lang->more
        ) : null,
    ),
    common::hasPriv('execution', 'burn') ? echarts
    (
        set::grid(array('left' => '0', 'right' => '50px', 'bottom' => '0',  'containLabel' => true)),
        set::xAxis
        (
            array
            (
                'type' => 'category',
                'data' => $chartData['labels'],
                'name' => $lang->execution->burnXUnit,
                'boundaryGap' => false
            )
        ),
        set::yAxis
        (
            array
            (
                'type'     => 'value',
                'name'     => "({$lang->execution->workHour})",
                'axisLine' => array('show' => true),
            )
        ),
        set::legend
        (
            array
            (
                'data' => array($lang->execution->charts->burn->graph->actuality, $lang->execution->charts->burn->graph->reference, $lang->execution->charts->burn->graph->delay),
            )
        ),
        set::tooltip
        (
            array
            (
                'trigger' => 'axis',
                'axisPointer' => array(
                    'type' => 'none'
                ),
                'formatter' => "RAWJS<function(rowDatas){return window.randTipInfo(rowDatas);}>RAWJS"
            )
        ),
        set::series
        (
            array
            (
                array
                (
                    'data' => $chartData['baseLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->reference,
                    'symbolSize' => 8,
                    'symbol' => 'circle',
                    'itemStyle' => array
                    (
                        'normal' => array
                        (
                            'color' => '#D8D8D8',
                            'lineStyle' => array
                            (
                                'width' => 3,
                                'color' => '#F1F1F1',
                            )
                        ),
                        'emphasis' => array
                        (
                            'color' => '#FFF',
                            'borderColor' => '#D8D8D8',
                            'borderWidth' => 2,
                        )
                    ),
                    'emphasis' => array
                    (
                        'lineStyle' => array
                        (
                            'width' => 3,
                            'color' => '#F1F1F1',
                        )
                    ),
                ),
                array
                (
                    'data' => $chartData['burnLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->actuality,
                    'symbolSize' => 8,
                    'symbol' => 'circle',
                    'itemStyle' => array
                    (
                        'normal' => array
                        (
                            'color' => '#006AF1',
                            'lineStyle' => array
                            (
                                'width' => 3,
                                'color' => '#2B7DFE',
                            )
                        ),
                        'emphasis' => array
                        (
                            'color' => '#fff',
                            'borderColor' => '#006AF1',
                            'borderWidth' => 2
                        )
                    ),
                ),
                array
                (
                    'data' => $chartData['delayLine'],
                    'type' => 'line',
                    'name' => $lang->execution->charts->burn->graph->delay,
                    'symbolSize' => 8,
                    'symbol' => 'circle',
                    'itemStyle' => array
                    (
                        'normal' => array
                        (
                            'color' => '#F00',
                            'lineStyle' => array
                            (
                                'color' => '#F00',
                            )
                        ),
                        'emphasis' => array
                        (
                            'color' => '#fff',
                            'borderColor' => '#F00',
                            'borderWidth' => 2
                        )
                    ),
                ),
            )
        ),
    )->size('100%', '85%') : null
);

/* ====== Render page ====== */
render();
