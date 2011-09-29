<?php
/**
 * Бюджетиране - опаковка
 *
 * @category   BGERP
 * @package    budget
 * @author     Stefan Stefanov <stefan.bg@gmail.com>
 * @copyright  2006-2011 Experta OOD
 * @license    GPL 2
 */
class budget_Wrapper extends core_Plugin
{
    /**
     *  Извиква се след рендирането на 'опаковката' на мениджъра
     */
    function on_AfterRenderWrapping($invoker, &$tpl)
    {
        $tabs = cls::get('core_Tabs');
        
        $tabs->TAB('budget_Assets', 'Парични средства');
        $tabs->TAB('budget_IncomeExpenses', 'Приходи / Разходи');
        $tabs->TAB('budget_Balances', 'Баланс');
        $tabs->TAB('budget_Reports', 'По подразделения / Дейности');
        
        $tpl = $tabs->renderHtml($tpl, empty($invoker->currentTab)?$invoker->className:$invoker->currentTab);
        
        $tpl->append(tr($invoker->title) . " » ", 'PAGE_TITLE');

        $invoker->menuPage = 'Финанси:Бюджетиране';
    }
}