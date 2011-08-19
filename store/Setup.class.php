<?php

/**
 *  class dma_Setup
 *
 *  Инсталиране/Деинсталиране на
 *  мениджъри свързани с DMA
 *
 */
class store_Setup
{
    /**
     *  Версия на компонента
     */
    var $version = '0.1';
    
    
    /**
     *  Стартов контролер за връзката в системното меню
     */
    var $startCtr = 'store_Stores';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $startAct = 'default';
    
    
    /**
     *  Инсталиране на пакета
     */
    function install()
    {
        $managers = array(
            'store_Stores',
        );
        
        // Роля за power-user на този модул
        $role = 'store';
        $html = core_Roles::addRole($role) ? "<li style='color:green'>Добавена е роля <b>$role</b></li>" : '';
        
        $instances = array();
        
        foreach ($managers as $manager) {
            $instances[$manager] = &cls::get($manager);
            $html .= $instances[$manager]->setupMVC();
        }
        
        $Menu = cls::get('bgerp_Menu');
        $html .= $Menu->addItem(3, 'Логистика', 'Складове', 'store_Stores', 'default', "{$role}, admin");
        
        return $html;
    }
     
    
    /**
     *  Де-инсталиране на пакета
     */
    function deinstall()
    {
        return "";
    }
}