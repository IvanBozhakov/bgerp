<?php

/**
 * Задаване начало на първия активен счетоводен период
 */
defIfNot('ACC_FIRST_PERIOD_START', '');


/**
 * class acc_Setup
 *
 * Инсталиране/Деинсталиране на
 * мениджъри свързани със счетоводството
 *
 *
 * @category  bgerp
 * @package   acc
 * @author    Milen Georgiev <milen@download.bg>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 */
class acc_Setup
{
    
    
    /**
     * Версия на пакета
     */
    var $version = '0.1';
    

    /**
     * Необходими пакети
     */
    var $depends = 'currency=0.1';
    
    /**
     * Мениджър - входна точка в пакета
     */
    var $startCtr = 'acc_Lists';
    
    
    /**
     * Екшън - входна точка в пакета
     */
    var $startAct = 'default';
    
    
    /**
     * Описание на модула
     */
    var $info = "Двустранно счетоводство: Настройки, Журнали";
    

    /**
     * Описание на конфигурационните константи
     */
    var $configDescription = array(
        'ACC_FIRST_PERIOD_START' => array('date'),                
    );
    
    /**
     * Инсталиране на пакета
     */
    function install()
    {
        $managers = array(
            'acc_Lists',
            'acc_Items',
            'acc_Periods',
            'acc_Accounts',
            'acc_Limits',
            'acc_Balances',
            'acc_BalanceDetails',
            'acc_Articles',
            'acc_ArticleDetails',
            'acc_Sales',
            'acc_SaleDetails',
            'acc_Journal',
            'acc_JournalDetails',
        );
        
        // Роля за power-user на този модул
        $role = 'acc';
        $html = core_Roles::addRole($role) ? "<li style='color:green'>Добавена е роля <b>$role</b></li>" : '';
        
        $instances = array();
        
        foreach ($managers as $manager) {
            $instances[$manager] = &cls::get($manager);
            $html .= $instances[$manager]->setupMVC();
        }
        
        $Menu = cls::get('bgerp_Menu');
        
        $html .= $Menu->addItem(2, 'Счетоводство', 'Книги', 'acc_Balances', 'default', "{$role}, admin");
        $html .= $Menu->addItem(2, 'Счетоводство', 'Настройки', 'acc_Periods', 'default', "{$role}, admin");
        
        return $html;
    }


    /**
     * Инициализране на началните данни
     */
    function loadSetupData()
    {
        $Periods = cls::get('acc_Periods');

        $html .= $Periods->loadSetupData();

        //Зарежда данни за инициализация от CSV файл за acc_Lists
        $html .= acc_setup_Lists::loadData();
        
        //Зарежда данни за инициализация от CSV файл за acc_Accounts
        $html .= acc_setup_Accounts::loadData();
        
        return $html;
    }
    
    
    /**
     * Де-инсталиране на пакета
     */
    function deinstall()
    {
        // Изтриване на пакета от менюто
        $res .= bgerp_Menu::remove($this);
        
        return $res;
    }
}