<?php
declare(strict_types=1);
namespace zin;

class thinkStep  extends wg
{
    protected static array $defineProps = array(
        'item: object',
        'action?: string="detail"',
        'addType?: string',
        'isRun?: bool=false', // 是否是分析活动
    );

    protected function buildBody(): wg|array
    {
        list($item, $action, $addType, $isRun) = $this->prop(array('item', 'action', 'addType', 'isRun'));

        $step         = $addType ? null : $item;
        $questionType = $addType ? $addType : ($item->options->questionType ?? '');
        if($addType === 'node' || !$addType && $item->type === 'node') return thinkNodeBase(set::step($step), set::mode($action), set::isRun($isRun));
        if($addType === 'transition' || !$addType && $item->type === 'transition') return thinkTransition(set::step($step), set::mode($action), set::isRun($isRun));
        if($questionType === 'input')    return thinkInput(set::step($step), set::question('input'), set::mode($action), set::isRun($isRun));
        if($questionType === 'radio')    return thinkRadio(set::step($step), set::question('radio'), set::mode($action), set::isRun($isRun));
        if($questionType === 'checkbox') return thinkCheckbox(set::step($step), set::question('checkbox'), set::mode($action), set::isRun($isRun));

        $isEdit = $action === 'edit' ? true : false;
        $fields = !empty($item->options->fields) ? explode(', ', $itemi->options->fields) : array();
        if($action == 'detail' && $questionType === 'tableInput')
        {
            return thinkStepDetail
            (
                set::item($item),
                thinkTableInputDetail
                (
                    set::item($item),
                    set::required($item->options->required ?? 0),
                    set::rowsTitle($fields)
                )
            );
        }
        else
        {
            if($questionType == 'tableInput')
            {
                return thinkTableInput(
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::required($isEdit ? ($item->options->required ?? 0) : 0),
                    set::type('question'),
                    set::requiredRows($isEdit ? ($item->options->requiredRows ?? 1) : 1),
                    set::isSupportAdd($isEdit ? ($item->options->isSupportAdd ?? false) : false),
                    set::canAddRows($isEdit ? ($item->options->canAddRows ?? 1) : 1),
                    set::rowsTitle(!$isEdit ? null : $fields)
                );
            }
        }
    }

    protected function build(): wg|node
    {
        global $lang, $app;
        $app->loadLang('thinkstep');

        list($item, $action, $addType, $isRun) = $this->prop(array('item', 'action', 'addType', 'isRun'));
        if(!$item) return array();

        $basicType = $item->type;
        $typeLang  = $action . 'Step';
        $type      = $addType ? $addType : ($basicType == 'question' ? $item->options->questionType : $basicType);
        $title     = $action == 'detail' ? ($lang->thinkstep->$basicType . $lang->thinkstep->info) : sprintf($lang->thinkstep->formTitle[$type], $lang->thinkstep->$typeLang);

        return div
        (
            setClass('relative'),
            !$isRun ? array(
                div
                (
                    setClass('flex items-center justify-between text-gray-950 h-12'),
                    setStyle(array('padding-left' => '48px', 'padding-right' => '48px')),
                    div(setClass('font-medium'), $title),
                    ($action != 'detail') ? null : div
                    (
                        setClass('ml-2'),
                        setStyle(array('min-width' => '48px')),
                        btnGroup
                        (
                            btn
                            (
                                setClass('btn ghost text-gray w-5 h-5'),
                                set::icon('edit'),
                                set::url(createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}&status=edit")),
                            ),
                            !$item->existNotNode ? btn
                            (
                                setClass('btn ghost text-gray w-5 h-5 ml-1 ajax-submit'),
                                set::icon('trash'),
                                setData('url', createLink('thinkstep', 'ajaxDelete', "stepID={$item->id}")),
                                setData('confirm',  $lang->thinkwizard->step->deleteTips[$basicType])
                            ) : btn
                            (
                                set(array(
                                    'class'          => 'ghost w-5 h-5 text-gray opacity-50 ml-1',
                                    'icon'           => 'trash',
                                    'data-toggle'    => 'tooltip',
                                    'data-title'     => $lang->thinkwizard->step->cannotDeleteNode,
                                    'data-placement' => 'bottom-start',
                                ))
                            )
                        )
                    )
                ),
                h::hr()
            ) : null,
            div(setClass('pt-6 px-8 mx-4'), $this->buildBody())
        );
    }
}
