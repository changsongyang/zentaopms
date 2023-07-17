<?php
/**
 * 按全局统计的年度启动项目中按期完成项目数。
 * count_of_undelayed_project_which_started.
 *
 * 范围：global
 * 对象：project
 * 目的：scale
 * 度量名称：按全局统计的年度启动项目中按期完成项目数
 * 单位：个
 * 描述：按全局统计的年度启动项目中按期完成项目数是指在某年度启动的项目中按预定计划时间关闭的项目数量。这个度量项可以帮助团队评估某年度项目的时间管理和执行能力，并衡量项目的进展和交付效果。较高的按时关闭项目数表明团队能够按时交付项目，有助于保持项目的正常进行和客户满意度。
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
class count_of_undelayed_project_which_started extends baseCalc
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