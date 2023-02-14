<?php
/**
 * The html element class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin\core;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once 'wg.class.php';
require_once 'directive.func.php';

use function \zin\utils\flat;

class h extends wg
{
    protected static $defineProps = array('tagName' => array('type' => 'string', 'required' => true), 'selfClose' => array('type' => 'bool', 'default' => false), 'customProps' => array('type' => 'string|array'));

    public function getTagName()
    {
        return $this->props->get('tagName');
    }

    public function isSelfClose()
    {
        $selfClose = $this->props->get('selfClose');
        if($selfClose !== NULL) return $selfClose;

        return in_array($this->getTagName(), array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'));
    }

    public function build($isPrinted = false)
    {
        if($this->isSelfClose()) return $this->buildSelfCloseTag();

        return array($this->buildTagBegin(), parent::build($isPrinted), $this->buildTagEnd());
    }

    protected function getPropsStr()
    {
        $skipProps   = array_keys(static::$defineProps);
        $customProps = $this->props->get('customProps');

        if($customProps) $skipProps = array_merge($skipProps, is_string($customProps) ? explode(',', $customProps) : $customProps);

        $propStr = $this->props->toStr($skipProps);
        return empty($propStr) ? '' : " $propStr";
    }

    protected function buildSelfCloseTag()
    {
        $tagName = $this->getTagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr />";
    }

    protected function buildTagBegin()
    {
        $tagName = $this->getTagName();
        $propStr = $this->getPropsStr();
        return "<$tagName$propStr>";
    }

    protected function buildTagEnd()
    {
        $tagName = $this->getTagName();
        return "</$tagName>";
    }

    public function create($tagName, $args, $defaultProps = NULL)
    {
        $ele = new h(prop('tagName', $tagName), $args);
        if(is_array($defaultProps)) $ele->setDefaultProps($defaultProps);
        return $ele;
    }

    public static function __callStatic($tagName, $args)
    {
        return new h(prop('tagName', $tagName), $args);
    }

    public static function button()
    {
        return self::create('button', func_get_args(), array('type' => 'button'));
    }

    public static function input()
    {
        return self::create('input', func_get_args(), array('type' => 'text'));
    }

    public static function checkbox()
    {
        return self::create('input', func_get_args(), array('type' => 'checkbox'));
    }

    public static function radio()
    {
        return self::create('input', func_get_args(), array('type' => 'radio'));
    }

    public static function textarea()
    {
        $children = h::convertStrToRawHtml(func_get_args());
        return self::create('textarea', $children, array('type' => 'radio'));
    }

    public static function importJs($src)
    {
        return self::create('script', prop('src', $src));
    }

    public static function importCss($src)
    {
        return self::create('link', prop('rel', 'stylesheet'), prop('href', $src));
    }

    public static function import($file, $type = NULL)
    {
        if(is_array($file))
        {
            $children = array();
            foreach($file as $file)
            {
                $children[] = self::import($file, $type);
            }
            return $children;
        }
        if($type === NULL) $type = pathinfo($file, PATHINFO_EXTENSION);
        if($type == 'js') return self::importJs($file);
        if($type == 'css') return self::importCss($file);
        return null;
    }

    public static function css()
    {
        $children = h::convertStrToRawHtml(func_get_args());
        return self::create('style', $children);
    }

    public static function js()
    {
        $children = h::convertStrToRawHtml(func_get_args());
        return self::create('script', $children);
    }

    protected static function convertStrToRawHtml($children)
    {
        $children = flat($children);
        foreach($children as $key => $child)
        {
            if(is_string($child)) $children[$key] = html($child);
        }
        return $children;
    }
}
