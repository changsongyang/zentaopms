#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', $useCommon = true, $levels = 4)->gen(3000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=scale_of_annual_closed_story_in_product
cid=1
pid=1

*/
r(count($calc->getResult())) && p('') && e('25'); // 测试按产品的年度关闭需求分组数。

r($calc->getResult(array('product' => '7',  'year' => '2012')))  && p('0:value') && e('180'); // 测试2012年产品7关闭的需求规模数。
r($calc->getResult(array('product' => '7',  'year' => '2015')))  && p('0:value') && e('208'); // 测试2015年产品7关闭的需求规模数。
r($calc->getResult(array('product' => '9',  'year' => '2011')))  && p('0:value') && e('276'); // 测试2011年产品9关闭的需求规模数。
r($calc->getResult(array('product' => '9',  'year' => '2012')))  && p('0:value') && e('216'); // 测试2012年产品9关闭的需求规模数。
r($calc->getResult(array('product' => '8',  'year' => '2012')))  && p('0:value') && e('0');   // 测试已删除产品关闭的需求规模数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');   // 测试不存在的产品的需求规模数。
