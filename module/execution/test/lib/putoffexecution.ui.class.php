<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class putoffExecutionTester extends tester
{
    /**
     * 输入延期执行表单字段。
     * Input fields.
     *
     * @param  array  $execution
     * @param  string $executionId
     * @access public
     */
    public function inputFields($execution, $executionId)
    {
        $form = $this->initForm('execution', 'view', array('execution' => $executionId ), 'appIframe-execution');
        $status = $form->dom->status->getText();
        $form->dom->putoff->click();
        if(isset($execution['begin'])) $form->dom->begin->datePicker($execution['begin']);
        if(isset($execution['end'])) $form->dom->end->datePicker($execution['end']);
        $form->dom->putoffSubmit->click();
        $form->wait(1);
    }

    /**
     * 延期执行。
     * Putoff execution.
     *
     * @param  array  $execution
     * @param  string $executionId
     * @access public
     * @return bool
     */
    public function putoff($execution, $executionId)
    {
        $this->inputFields($execution, $executionId);
        $form = $this->loadPage();

        if($form->dom->status->getText() != $status) return $this->failed('执行状态错误');
        if($form->dom->plannedBegin->getText() != $execution['begin']) return $this->failed('计划开始时间错误');
        if($form->dom->plannedEnd->getText() != $execution['end']) return $this->failed('计划完成时间错误');
        return $this->success('延期执行成功');
    }

    /**
     * 延期执行的计划起止日期不正确。
     * Putoff execution with wrong date.
     *
     * @param  array  $execution
     * @param  string $executionId
     * @param  string $dateType    begin|end
     * @access public
     * @return bool
     */
    Public function putoffiWithWrongDate($execution, $executionId, $dateType = 'end')
    {
        $this->inputFields($execution, $executionId);

    }
}
