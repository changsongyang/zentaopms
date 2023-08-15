#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close', $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('execution', $useCommon = true, $levels = 4)->gen(20, false);
zdTable('task')->config('task', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_daily_finished_task
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('244'); // 测试分组数。

r($calc->getResult(array('year' => '2020', 'month' => '09', 'day' => '05'))) && p('0:value') && e('1'); // 测试2020.09.05。
r($calc->getResult(array('year' => '2020', 'month' => '09', 'day' => '15'))) && p('0:value') && e('1'); // 测试2020.09.15。

r($calc->getResult(array('year' => '2021', 'month' => '04'))) && p('') && e('0'); // 测试不存在。
