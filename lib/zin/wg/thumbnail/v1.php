<?php
declare(strict_types=1);
/**
 * The thumbnail widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class thumbnail extends wg
{
    protected static array $defineProps = array(
        'name?: string="thumbnail"',
        'src?: string',
        'tips?: string'
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        $name = $this->prop('name');
        $src  = $this->prop('src');
        $tips = $this->prop('tips');

        return array
        (
            div
            (
                setClass('flex items-center justify-center cursor-pointer bg-gray-100 w-full h-64'),
                setData(array('on' => 'click', 'call' => 'uploadThumbnail')),
                img
                (
                    setID('thumbnail-img'),
                    setClass('w-full h-full' . ($src ? '' : ' hidden')),
                    set::src($src),
                    set::alt($tips)
                ),
                $src ? null : span(setID('thumbnail-tips'), setClass('text-primary font-bold'), $tips)
            ),
            input
            (
                set::type('hidden'),
                set::name($name),
                set::value($src)
            ),
            input
            (
                setID('thumbnail-file'),
                setClass('hidden'),
                set::type('file'),
                set::name('files[]'),
                set::accept('.jpg,.jpeg,.gif,.png,.bmp'),
                setData(array('on' => 'change', 'call' => 'changeThumbnail'))
            )
        );
    }
}
