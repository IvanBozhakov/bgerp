<?php



/**
 * Имейли - опаковка
 *
 *
 * @category  bgerp
 * @package   fax
 * @author    Yusein Yuseinov <yyuseinov@gmail.com>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 */
class fax_Wrapper extends core_Plugin
{
    
    
    /**
     * Извиква се след рендирането на 'опаковката' на мениджъра
     */
    function on_AfterRenderWrapping($invoker, &$tpl)
    {        
        $tabs = cls::get('core_Tabs');
        
        $tabs->TAB('fax_Incomings', 'Входящи');
                
        //Показва таба за постинги, само ако имаме права за листване
        if (fax_Outgoings::haveRightFor('list', core_Users::getCurrent())) {
            $tabs->TAB('fax_Outgoings', 'Изходящи');
        }
        
        $tabs->TAB('fax_Inboxes', 'Кутии');
        
        if(haveRole('admin')) {
            $tabs->TAB('fax_Router', 'Рутиране');
        }
                
        $tpl = $tabs->renderHtml($tpl, $invoker->currentTab ? : $invoker->className);
        
        $tpl->append(tr($invoker->title) . " « ", 'PAGE_TITLE');
        
        $invoker->menuPage = 'Имейли';
    }
}