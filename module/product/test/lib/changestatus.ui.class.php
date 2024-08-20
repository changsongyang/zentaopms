<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';

class changeStatus extends tester
{
    /**
     * 关闭产品
     * close product
     *
     * @param  $producrID 产品ID
     * @return mixed
     */
    public function closeProduct($productID)
    {
        $form = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $form->dom->btn($this->lang->product->close)->click();//产品详情页中点击关闭
        $form->wait(2);
        $form->dom->closeBtn->click();//弹窗中点击关闭产品
        $viewPage = $this->initForm('product', 'view', $productID, 'appIframe-product');
        $form->wait(2);
        $closed = $this->lang->product->statusList->closed;//产品详情页中关闭状态的语言项
        //判断详情页中的状态是否为[关闭]
        if($viewPage->dom->status->getText() == $closed)
        {
            return $this->success('关闭产品成功');
        }
        return $this->failed('关闭产品失败');
    }
}
