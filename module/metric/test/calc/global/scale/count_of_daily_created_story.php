#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_status', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_daily_created_story
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('160'); // 测试分组数。

r($calc->getResult(array('year' => '2017', 'month' => '09', 'day' => '21'))) && p('0:value') && e('2'); // 测试2017.09.21。
