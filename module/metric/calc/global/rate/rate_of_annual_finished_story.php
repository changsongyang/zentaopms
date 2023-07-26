<?php
/**
 * 按全局统计的年度研发需求完成率。
 * rate_of_annual_finished_story.
 *
 * 范围：global
 * 对象：story
 * 目的：rate
 * 度量名称：按全局统计的年度研发需求完成率
 * 单位：%
 * 描述：按全局统计的研发需求完成率表示按全局统计的年度已完成的研发需求数相对于按全局统计的有效研发需求数。这个指标衡量了整体研发团队在完成年度研发需求方面的效率和质量。完成率越高，说明研发团队能够按时完成年度目标，并且需求达到预期的质量标准。
 * 定义：复用：
 *       按全局统计的年度完成研发需求数
 *       按全局统计的年度有效研发需求数
 *       公式：
 *       按全局统计的年度研发需求完成率=按全局统计的年度完成研发需求数/按全局统计的年度有效研发需求数*100%
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_annual_finished_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.closedDate', 't1.closedReason');

    public function calculate($data)
    {
        $closedDate   = $data->closedDate;
        $closedReason = $data->closedReason;

        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);

        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();

        if(!isset($this->result[$year]['finished'])) $this->result[$year]['finished'] = 0;
        if(!isset($this->result[$year]['valid']))    $this->result[$year]['valid'] = 0;

        if($closedReason == 'done') $this->result[$year]['finished'] += 1;
        if(in_array($closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel')) === false) $this->result[$year]['valid'] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $finished = $value['finished'];
            $valid    = $value['valid'];

            $ratio = $valid == 0 ? 0 : round($finished / $valid, 2);
            $records[] = array('year' => $year, 'value' => $ratio);
        }
        return $this->filterByOptions($records, $options);
    }
}
