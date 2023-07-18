#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('productplan')->config('productplan', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_annual_created_plan_in_product
timeout=0
cid=1

*/

r(count($calc->getResult()))                                     && p('')        && e('55'); // 测试计划按产品分组数。
r($calc->getresult(array('product' => '1', 'year' => '2010')))   && p('0:value') && e('5');  // 测试产品1,2010年创建的的计划数。
r($calc->getresult(array('product' => '7', 'year' => '2016')))   && p('0:value') && e('4');  // 测试产品7,2016年创建的的计划数。
r($calc->getresult(array('product' => '6', 'year' => '2016')))   && p('0:value') && e('0');  // 测试已删除产品666666,2016年创建的的计划数。
r($calc->getresult(array('product' => '100', 'year' => '2023'))) && p('')        && e('0');  // 测试不存在的产品的计划数。
