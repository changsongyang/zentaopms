<?php
declare(strict_types=1);
/**
 * The dashboard view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

$cpuInfo    = $this->cne->getCpuUsage($cneMetrics->metrics->cpu);
$memoryInfo = $this->cne->getMemUsage($cneMetrics->metrics->memory);
jsVar('cpuInfo',        $cpuInfo);
jsVar('memoryInfo',     $memoryInfo);
jsVar('instanceIdList', array_column($instances, 'id'));

/* 资源统计 */
div
(
    setClass('bg-white p-5'),
    div(setClass('text-xl font-semibold mb-5'), $lang->system->cneStatistic),
    div
    (
        setClass('flex'),
        div
        (
            setClass('basis-2/5 border h-40 rounded-md row'),
            div
            (
                setClass('basis-1/2 justify-center pl-6 pt-10 pb-10'),
                div
                (
                    setClass('flex row basis-2/3 justify-evenly h-full'),
                    icon('checked', set::size(80)),
                    div
                    (
                        setClass('p-2 ml-2 pr-6 flex col justify-between normal'),
                        setStyle('white-space', 'nowrap'),
                        div(setClass('text-xl font-semibold'), zget($lang->CNE->statusList, $cneMetrics->status, $lang->CNE->statusList['unknown'])),
                        $lang->system->cneStatus
                    ),
                    div(),
                    div(setClass('p-4 border-r'))
                ),
            ),
            div
            (
                setClass('basis-1/2 justify-center p-6 pt-10 pb-10'),
                div
                (
                    setClass('flex row basis-2/3 justify-evenly text-center h-full'),
                    div
                    (
                        setClass('flex col justify-between'),
                        div(setClass('text-4xl font-semibold text-primary'), $cneMetrics->node_count),
                        $lang->system->nodeQuantity
                    ),
                    div
                    (
                        setClass('flex col justify-between'),
                        div(setClass('text-4xl font-semibold text-primary'), $this->instance->totalServices()),
                        $lang->system->serviceQuantity
                    ),
                )
            )
        ),
        div
        (
            setClass('basis-3/5 h-40 flex row justify-evenly ml-3'),
            div
            (
                setClass(' flex row'),
                div
                (
                    setID('progressCpu'),
                    setClass('relative'),
                    span
                    (
                        setClass('absolute text-lg'),
                        setStyle(array('transform' => 'translate(-50%, -50%)', 'top' => '50%', 'left' => '50%', 'white-space' => 'nowrap')),
                        icon('checked', setClass('mr-1')),
                        $lang->system->cpuUsage
                    )
                ),
                div
                (
                    setClass('flex col justify-around p-5'),
                    div(setClass('text-4xl font-medium'), $cpuInfo['rate'], span('%', setClass('text-xl ml-1'))),
                    $cpuInfo['tip']
                )
            ),
            div
            (
                setClass(' flex row'),
                div
                (
                    setID('progressMemory'),
                    setClass('relative'),
                    span
                    (
                        setClass('absolute text-lg'),
                        setStyle(array('transform' => 'translate(-50%, -50%)', 'top' => '50%', 'left' => '50%', 'white-space' => 'nowrap')),
                        icon('checked', setClass('mr-1')),
                        $lang->system->memUsage
                    )
                ),
                div
                (
                    setClass('flex col justify-around p-5'),
                    div(setClass('text-4xl font-medium'), $memoryInfo['rate'], span('%', setClass('text-xl ml-1'))),
                    $memoryInfo['tip']
                )
            )
        )
    )
);

/* 运行中服务 */
$instances = initTableData($instances, $config->system->dtable->instanceList->fieldList, $this->system);
div
(
    setClass('bg-white p-5 mt-5'),
    div(setClass('text-xl font-semibold mb-5'), $lang->instance->runningService),
    dtable
    (
        set::cols($config->system->dtable->instanceList->fieldList),
        set::data($instances),
        set::onRenderCell(jsRaw('window.renderInstanceList')),
        set::footPager(usePager()),
    ),
);