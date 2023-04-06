<?php
/**
 * The base widget class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'props.class.php';
require_once 'directive.func.php';
require_once 'zin.class.php';
require_once 'context.class.php';

class wg
{
    /**
     * Define props for the element
     *
     * @todo @sunhao: Support for using string
     * @var array|string
     */
    protected static $defineProps = NULL;

    protected static $defaultProps = NULL;

    protected static $defineBlocks = NULL;

    protected static $wgToBlockMap = array();

    protected static $definedPropsMap = array();

    private static $gidSeed = 0;

    private static $pageResources = array();

    /**
     * The props of the element
     *
     * @access public
     * @var    props
     */
    public $props;

    public $blocks = array();

    public $parent = NULL;

    public $gid;

    public $displayed = false;

    protected $matchedPortals = NULL;

    protected $renderOptions = NULL;

    public function __construct(/* string|element|object|array|null ...$args */)
    {
        $this->props = new props();

        $this->gid = self::nextGid();
        $this->setDefaultProps(static::getDefaultProps());
        $this->add(func_get_args());        $this->created();

        zin::renderInGlobal($this);
        static::checkPageResources();
    }

    public function isDomElement()
    {
        return false;
    }

    public function isMatch($selector)
    {
        $list = is_string($selector) ? static::parseWgSelector($selector) : $selector;
        foreach($list as $item)
        {
            if(!empty($item['id']) && $this->id() !== $item['id']) continue;
            if(!empty($item['tag']) && $this->type() !== $item['tag']) continue;
            if(!empty($item['class']) && !$this->props->class->has($item['class'])) continue;
            return true;
        }
        return false;
    }

    protected function checkPortals()
    {
        $this->matchedPortals = array();
        $portals = context::current()->getPortals();
        foreach($portals as $portal)
        {
            if($this->isMatch($portal->prop('target'))) $this->matchedPortals[] = $portal->children();
        }
    }

    protected function getPortals()
    {
        $portals = $this->matchedPortals;
        $this->matchedPortals = NULL;
        return $portals;
    }

    public function buildDom()
    {
        $this->checkPortals();

        $options  = $this->renderOptions;
        $before   = $this->buildBefore();
        $children = $this->build();
        $after    = $this->buildAfter();
        $portals  = $this->getPortals();

        $list = [];
        if(!empty($before))   $list[] = $before;
        if(!empty($children)) $list[] = $children;
        if(!empty($portals))  $list[] = $portals;
        if(!empty($after))    $list[] = $after;

        $dom = new stdClass();
        $dom->type = 'wg';
        $dom->wg   = $this;
        $dom->list = static::buildDomList($list);

        if(!empty($options) && isset($options['selector']))
        {
            $selector = $options['selector'];
            $dom = static::filterDomTree($dom, $selector, $options);
        }

        return $dom;
    }

    /**
     * Render widget to html
     * @return string
     */
    public function render()
    {
        $dom  = $this->buildDom();
        $html = static::renderToHtml(is_array($dom) ? $dom : $dom->list);

        context::destroy($this->gid);

        return $html;
    }

    public function display($options = [])
    {
        zin::disableGlobalRender();

        $this->renderOptions = $options;

        echo $this->render();

        $this->displayed = true;
        return $this;
    }

    protected function created() {}

    protected function buildBefore()
    {
        return $this->block('before');
    }

    protected function buildAfter()
    {
        return $this->block('after');
    }

    protected function build()
    {
        return  $this->children();
    }

    protected function onAddBlock($child, $name)
    {
        return $child;
    }

    protected function onAddChild($child)
    {
        return $child;
    }

    protected function onSetProp($name, $value) {}

    public function add($item, $blockName = 'children')
    {
        if($item === NULL || is_bool($item)) return $this;

        if(is_array($item))
        {
            foreach($item as $child) $this->add($child, $blockName);
            return $this;
        }

        zin::disableGlobalRender();

        if($item instanceof wg)    $this->addToBlock($blockName, $item);
        elseif(is_string($item))   $this->addToBlock($blockName, htmlentities($item));
        elseif(isDirective($item)) $this->directive($item, $blockName);
        else                       $this->addToBlock($blockName, htmlentities(strval($item)));

        zin::enableGlobalRender();

        return $this;
    }

    public function addToBlock($name, $child = NULL)
    {
        if(is_array($name))
        {
            foreach($name as $blockName => $blockChildren)
            {
                $this->addToBlock($blockName, $blockChildren);
            }
            return;
        }
        if(is_array($child))
        {
            foreach($child as $blockChild)
            {
                $this->addToBlock($name, $blockChild);
            }
            return;
        }

        if($child instanceof wg && empty($child->parent)) $child->parent = &$this;
        if($child instanceof wg && $child->type() === 'zin\portal') return;

        if($name === 'children' && $child instanceof wg)
        {
            $blockName = static::getBlockNameForWg($child);
            if($blockName !== NULL) $name = $blockName;
        }

        $result = $name === 'children' ? $this->onAddChild($child) : $this->onAddBlock($child, $name);

        if($result === false) return;
        if($result !== NULL && $result !== true) $child = $result;

        if(isset($this->blocks[$name])) $this->blocks[$name][] = $child;
        else $this->blocks[$name] = array($child);
    }

    public function children()
    {
        return $this->block('children');
    }

    public function block($name)
    {
        return isset($this->blocks[$name]) ? $this->blocks[$name] : array();
    }

    public function hasBlock($name)
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Apply directive
     * @param object $directive
     */
    public function directive(&$directive, $blockName)
    {
        $data = $directive->data;
        $type = $directive->type;
        $directive->parent = &$this;

        if($type === 'prop')
        {
            $this->setProp($data);
            return;
        }
        if($type === 'class' || $type === 'style')
        {
            $this->setProp($type, $data);
            return;
        }
        if($type === 'cssVar')
        {
            $this->setProp('--', $data);
            return;
        }
        if($type === 'html')
        {
            $this->addToBlock($blockName, $directive);
            return;
        }
        if($type === 'text')
        {
            $this->addToBlock($blockName, htmlspecialchars($data));
            return;
        }
        if($type === 'block')
        {
            foreach($data as $blockName => $blockChildren)
            {
                $this->add($blockChildren, $blockName);
            }
            return;
        }
    }

    public function prop($name, $defaultValue = NULL)
    {
        if(is_array($name))
        {
            $values = array();
            foreach($name as $index => $propName)
            {
                $values[] = $this->props->get($propName, is_array($defaultValue) ? (isset($defaultValue[$propName]) ? $defaultValue[$propName] : $defaultValue[$index]) : $defaultValue);
            }
            return $values;
        }

        return $this->props->get($name, $defaultValue);
    }

    /**
     * Set property, an array can be passed to set multiple properties
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @return dataset
     */
    public function setProp($prop, $value = NULL)
    {
        if($prop instanceof props) $prop = $prop->toJsonData();

        if(is_array($prop))
        {
            foreach($prop as $name => $value) $this->setProp($name, $value);
            return $this;
        }

        if(!is_string($prop) || empty($prop)) return $this;

        if($prop[0] === '#')
        {
            $this->add($value, substr($prop, 1));
            return;
        }

        $result = $this->onSetProp($prop, $value);
        if($result === false) return $this;
        if(is_array($result))
        {
            $prop = $result[0];
            $value = $result[1];
        }

        if($prop === 'id' && $value === '$GID') $value = $this->gid;

        $this->props->set($prop, $value);
        return $this;
    }

    public function hasProp()
    {
        $names = func_get_args();
        if(empty($names)) return false;
        foreach ($names as $name) if(!$this->props->has($name)) return false;
        return true;
    }

    public function setDefaultProps($props)
    {
        if(!is_array($props) || empty($props)) return;

        foreach($props as $name => $value)
        {
            if($this->props->has($name)) continue;
            $this->setProp($name, $value);
        }
    }

    public function type()
    {
        return get_called_class();
    }

    public function shortType()
    {
        $type = $this->type();
        $pos = strrpos($type, '\\');
        return $pos === false ? $type : substr($type, $pos + 1);
    }

    public function id()
    {
        return $this->prop('id');
    }

    protected function onCreated() {}

    public function toJsonData()
    {
        $data = array();
        $data['gid'] = $this->gid;
        $data['props'] = $this->props->toJsonData();

        $data['type'] = $this->type();
        if(str_starts_with($data['type'], 'zin\\')) $data['type'] = substr($data['type'], 4);

        $data['blocks'] = array();
        foreach($this->blocks as $key => $value)
        {
            foreach($value as $index => $child)
            {
                if($child instanceof wg || (is_object($child) && method_exists($child, 'toJsonData')))
                {
                    $value[$index] = $child->toJsonData();
                }
                elseif(isHtml($child))
                {
                    $value[$index] = $child->data;
                }
            }
            if($key === 'children')
            {
                unset($data['blocks'][$key]);
                $data['children'] = $value;
            }
            else
            {
                $data['blocks'][$key] = $value;
            }
        }

        if(empty($data['blocks'])) unset($data['blocks']);

        if(!empty($this->parent)) $data['parent'] = $this->parent->gid;

        return $data;
    }

    protected static function getDefaultProps()
    {
        $defaultProps = array();
        foreach(static::getDefinedProps() as $name => $definition)
        {
            if(!isset($definition['default'])) continue;
            $defaultProps[$name] = $definition['default'];
        }
        return $defaultProps;
    }

    public static function getPageCSS() {}

    public static function getPageJS() {}

    protected static function checkPageResources()
    {
        $name = get_called_class();
        if(isset(static::$pageResources[$name])) return;

        static::$pageResources[$name] = true;

        $pageCSS = static::getPageCSS();
        $pageJS  = static::getPageJS();

        if(!empty($pageCSS)) context::css($pageCSS);
        if(!empty($pageJS))  context::js($pageJS);
    }

    public static function wgBlockMap()
    {
        $wgName = get_called_class();
        if(!isset(wg::$wgToBlockMap[$wgName]))
        {
            $wgBlockMap = array();
            if(isset(static::$defineBlocks))
            {
                foreach(static::$defineBlocks as $blockName => $setting)
                {
                    if(!isset($setting['map'])) continue;
                    $map = $setting['map'];
                    if(is_string($map)) $map = explode(',', $map);
                    foreach($map as $name) $wgBlockMap[$name] = $blockName;
                }
            }
            wg::$wgToBlockMap[$wgName] = $wgBlockMap;
        }
        return wg::$wgToBlockMap[$wgName];
    }

    public static function getBlockNameForWg($wg)
    {
        $wgType = ($wg instanceof wg) ? $wg->type() : $wg;
        $wgBlockMap = static::wgBlockMap();
        if(str_starts_with($wgType, 'zin\\')) $wgType = substr($wgType, 4);
        return isset($wgBlockMap[$wgType]) ? $wgBlockMap[$wgType] : NULL;
    }

    public static function nextGid($type = NULL)
    {
        return 'zin' . ++static::$gidSeed;
    }

    protected static function getDefinedProps($name = NULL)
    {
        if($name === NULL) $name = get_called_class();

        if(!isset(wg::$definedPropsMap[$name]) && $name === get_called_class())
        {
            wg::$definedPropsMap[$name] = static::parsePropsDefinition(static::$defineProps);
        }
        return wg::$definedPropsMap[$name];
    }

    /**
     * Parse props definition
     * @param $definition
     * @example
     *
     * $definition = 'name,desc:string,title?:string|element,icon?:string="star"'
     * $definition = array('name', 'desc:string', 'title?:string|element', 'icon?:string="star"');
     * $definition = array('name' => 'mixed', 'desc' => 'string', 'title' => array('type' => 'string|element', 'optional' => true), 'icon' => array('type' => 'string', 'default' => 'star', 'optional' => true))))
     */
    private static function parsePropsDefinition($definition)
    {
        $parentClass = get_parent_class(get_called_class());
        $props = $parentClass ? call_user_func("$parentClass::getDefinedProps") : array();

        if((!is_array($definition) && !is_string($definition)) || ($parentClass && $definition === $parentClass::$defineProps))
        {
            if(static::$defaultProps && static::$defaultProps !== $parentClass::$defaultProps)
            {
                foreach($props as $name => $value)
                {
                    if(is_array(static::$defaultProps) && isset(static::$defaultProps[$name]))
                    {
                        $value['default'] = static::$defaultProps[$name];
                        $props[$name]     = $value;
                    }
                }
            }
            return $props;
        }

        if(is_string($definition)) $definition = explode(',', $definition);

        foreach($definition as $name => $value)
        {
            $optional = false;
            $type     = 'mixed';
            $default  = (isset($props[$name]) && isset($props[$name]['default'])) ? $props[$name]['default'] : NULL;

            if(is_int($name) && is_string($value))
            {
                $value = trim($value);
                if(!str_contains($value, ':'))
                {
                    $name  = $value;
                    $value = '';
                }
                else
                {
                    list($name, $value) = explode(':', $value, 2);
                }
                $name = trim($name);
                if($name[strlen($name) - 1] === '?')
                {
                    $name     = substr($name, 0, strlen($name) - 1);
                    $optional = true;
                }
            }

            if(is_array($value))
            {
                $type     = isset($value['type'])    ? $value['type']    : $type;
                $default  = isset($value['default']) ? $value['default'] : $default;
                $optional = isset($value['optional'])? $value['optional']: $optional;
            }
            else if(is_string($value))
            {
                if(!str_contains($value, '='))
                {
                    $type    = $value;
                    $default = NULL;
                }
                else
                {
                    list($type, $default) = explode('=', $value, 2);
                }
                $type = trim($type);

                if(is_string($default)) $default = json_decode(trim($default));
            }

            $props[$name] = array('type' => empty($type) ? 'mixed' : $type, 'default' => $default, 'optional' => $default !== NULL || $optional);
        }

        if(static::$defaultProps && (!$parentClass || static::$defaultProps !== $parentClass::$defaultProps))
        {
            foreach(static::$defaultProps as $name => $value)
            {
                if(!isset($props[$name])) continue;
                $props[$name]['default'] = $value;
            }
        }
        return $props;
    }

    public static function buildDomList($list)
    {
        $domList = [];
        foreach($list as $item)
        {
            if(is_array($item))
            {
                $subDomList = static::buildDomList($item);
                if(!empty($subDomList)) $domList = array_merge($domList, $subDomList);
            }
            elseif($item instanceof wg)
            {
                $dom = $item->buildDom();
                if(is_array($dom)) $domList = array_merge($domList, $dom);
                else $domList[] = $dom;
            }
            else
            {
                $domList[] = $item;
            }
        }
        return $domList;
    }

    /**
     * @return string
     */
    public static function renderToHtml($children)
    {
        $html = array();
        foreach($children as $child)
        {
            if($child === NULL || is_bool($child)) continue;

            if(is_array($child))
            {
                $html[] = static::renderToHtml($child);
            }
            elseif(is_string($child))
            {
                $html[] = $child;
            }
            elseif($child instanceof wg)
            {
                $html[] = $child->render();
            }
            elseif(is_object($child))
            {
                if(isset($child->type) && $child->type === 'wg' && isset($child->list)) $html[] = static::renderToHtml($child->list);
                elseif(method_exists($child, 'render')) $html[] = $child->render();
                elseif(isHtml($child))              $html[] = $child->data;
                elseif(isText($child))              $html[] = htmlspecialchars($child->data);
                elseif(isset($child->html))         $html[] = $child->html;
                elseif(isset($child->text))         $html[] = htmlspecialchars($child->text);
                else                                $html[] = strval($child);
            }
            else
            {
                $html[] = strval($child);
            }
        }
        return implode('', $html);
    }

    public static function parseWgSelector($selector)
    {
        $selector = trim($selector);
        if(empty($selector)) return [];

        $results = [];
        $parts  = explode(',', $selector);
        foreach($parts as $part)
        {
            $part = trim($part);
            $len = strlen($part);
            if($len < 1) continue;

            $result = ['class' => [], 'id' => NULL, 'tag' => NULL];
            $type = 'tag';
            $current = '';
            for($i = 0; $i < $len; $i++)
            {
                $c = $part[$i];
                $t = '';

                if($c === '#')     $t = 'id';
                elseif($c === '.') $t = 'class';

                if(empty($t))
                {
                    $current .= $c;
                }
                else
                {
                    if(!empty($current))
                    {
                        if($type === 'class') $result[$type][] = $current;
                        else                  $result[$type]   = $current;
                    }
                    $current = '';
                    $type    = $t;
                }
            }
            if(!empty($current))
            {
                if($type === 'class') $result[$type][] = $current;
                else                  $result[$type]   = $current;
            }
            $results[] = $result;
        }
        return $results;
    }

    public static function filterDomTree($dom, $selector = NULL, $options = [])
    {
        if($selector === NULL) return $dom;

        if(is_string($selector)) $selector = static::parseWgSelector($selector);

        if(isset($dom->wg) && $dom->wg->isMatch($selector))
        {
            if($dom->wg->isDomElement())
            {
                if(isset($options['inner']) && $options['inner'])
                {
                    $dom->list = static::buildDomList($dom->wg->children());
                }
            }
            return $dom;
        }

        $list = [];
        $earlyStop = isset($options['earlyStop']) ? $options['earlyStop'] : false;
        foreach($dom->list as $item)
        {
            if(!is_object($item) || !isset($item->wg)) continue;

            $result = static::filterDomTree($item, $selector, $options);
            if(is_object($result))
            {
                $list[] = $result;
            }
            elseif(is_array($result) && !empty($result))
            {
                $list = array_merge($list, $result);
            }
            if($earlyStop && !empty($list)) break;
        }

        return $list;
    }
}
