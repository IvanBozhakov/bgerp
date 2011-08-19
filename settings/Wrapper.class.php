<?php

/**
 * Клас 'registry_Wrapper'
 *
 * Поддържа системното меню и табовете на пакета 'registry'
 *
 * @category   Experta Framework
 * @package    bgerp
 * @author     Milen Georgiev <milen@download.bg>
 * @copyright  2006-2011 Experta OOD
 * @license    GPL 2
 * @version    CVS: $Id: $
 * @link
 * @since
 */
class settings_Wrapper extends core_Plugin
{
    
    
    /**
     *  Извиква се след рендирането на 'опаковката' на мениджъра
     */
    function on_AfterRenderWrapping($invoker, &$tpl)
    {
        $tabs = cls::get('core_Tabs', array('class'=>$invoker->className));
        
        $tabs->TAB('settings_Settings', 'Настройки');
        
        $tpl = $tabs->renderHtml($tpl, $invoker->className);
        
        $tpl->append(tr($invoker->title) . " » " , 'PAGE_TITLE');
    }
}