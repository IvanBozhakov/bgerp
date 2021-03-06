<?php



/**
 * Документи за склада
 *
 *
 * @category  bgerp
 * @package   store
 * @author    Ts. Mihaylov <tsvetanm@ep-bags.com>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 */
class store_DocumentDetails extends core_Detail {
    
    
    /**
     * Заглавие
     */
    var $title = 'Детайли на документ';
    
    
    /**
     * Плъгини за зареждане
     */
    var $loadList = 'plg_RowTools, store_Wrapper';
    
    
    /**
     * Полета, които ще се показват в листов изглед
     */
    var $listFields = 'id, details, tools=Пулт';
    
    
    /**
     * Полето в което автоматично се показват иконките за редакция и изтриване на реда от таблицата
     */
    var $rowToolsField = 'tools';
    
    
    /**
     * Име на поле от модела, външен ключ към мастър записа
     */
    var $masterKey = 'documentId';
    
    
    /**
     * Активния таб в случай, че wrapper-а е таб контрол.
     */
    var $tabName = "store_Documents";
    
    
    /**
     * Описание на модела (таблицата)
     */
    function description()
    {
        $this->FLD('documentId', 'key(mvc=store_Documents, select=docType)', 'caption=Документ');
        $this->FLD('details', 'varchar(255)', 'caption=Dummy for test');
    }
}