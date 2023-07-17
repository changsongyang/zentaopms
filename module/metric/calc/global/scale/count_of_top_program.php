<?php
/**
 * 按全局统计的一级项目集总数。
 * count_of_top_program.
 *
 * 范围：global
 * 对象：program
 * 目的：scale
 * 度量名称：按全局统计的一级项目集总数
 * 单位：个
 * 描述：按全局统计的一级项目集总数表示组织中处于一级层级的项目集数量。此度量项反映了组织中一级项目集的数量，可以用于评估组织的项目集管理的结构和层级划分。
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
class count_of_top_program extends baseCalc
{
    public $dataset = null;

    public $fieldList = array();

    //public funtion getStatement($dao)
    //{
    //}

    public function calculate($data)
    {
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}