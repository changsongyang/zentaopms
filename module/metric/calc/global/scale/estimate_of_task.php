<?php
/**
 * 按全局统计的任务预计工时数。
 * estimate_of_task.
 *
 * 范围：global
 * 对象：task
 * 目的：scale
 * 度量名称：按全局统计的任务预计工时数
 * 单位：h
 * 描述：按全局统计的任务预计工时数是指所有任务预计完成所需的工时总和。该度量项可以用来规划资源和预估工期，为项目管理和团队协作提供依据。较准确的任务预计工时总数可以帮助团队更好地安排时间和资源，提高任务的完成效率。
 * 定义：所有的任务的预计工时数求和
 *       过滤已删除的任务
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class estimate_of_task extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.estimate');

    public $result = 0;

    public function calculate($row)
    {
        if(empty($row->estimate)) return false;

        $this->result += $row->estimate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
