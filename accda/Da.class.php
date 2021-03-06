<?php



/**
 * Мениджър на дълготрайни активи
 *
 *
 * @category  bgerp
 * @package   accda
 * @author    Stefan Stefanov <stefan.bg@gmail.com>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 * @title     Дълготрайни активи
 */
class accda_Da extends core_Master
{
    
    
    /**
     * Интерфейси, поддържани от този мениджър
     */
    var $interfaces = 'acc_RegisterIntf,accda_DaAccRegIntf';
    
    
    /**
     * Заглавие
     */
    var $title = 'Регистър на дълготрайните активи';
    
    
    /**
     * Плъгини за зареждане
     */
    var $loadList = 'plg_RowTools, accda_Wrapper, plg_State2, plg_Printing, doc_DocumentPlg, bgerp_plg_Blank,
                     acc_plg_Registry, plg_Sorting, plg_SaveAndNew, plg_Search';
    
    
    /**
     * Абревиатура
     */
    var $abbr = 'Da';
    
    
    /**
     * Заглавие на единичен документ
     */
    var $singleTitle = 'Пускане в експлоатация на ДА';
    
    
    /**
     * Икона за единичния изглед
     */
    var $singleIcon = 'img/16/doc_table.png';
    
    
    /**
     * Кой има право да чете?
     */
    var $canRead = 'admin,accda';
    
    
    /**
     * Кой има право да променя?
     */
    var $canEdit = 'admin,accda';
    
    
    /**
     * Кой има право да добавя?
     */
    var $canAdd = 'admin,accda';
    
    
    /**
     * Кой може да го види?
     */
    var $canView = 'admin,accda';
    
    
    /**
     * Кой може да го изтрие?
     */
    var $canDelete = 'admin,accda';
    
    
    /**
     * @todo Чака за документация...
     */
    var $canSingle = 'admin,accda';
    
    
    /**
     * Поле за търсене
     */
    var $searchFields = 'num, serial, title';
    
    /**
     * Описание на модела
     */
    function description()
    {
        $this->FLD('num', 'varchar(32)', 'caption=Номер->Наш, mandatory');
        
        $this->FLD('serial', 'varchar', 'caption=Номер->Сериен');
        
        $this->FLD('title', 'varchar', 'caption=Наименование,mandatory,width=400px');
        
        $this->FLD('info', 'text', 'caption=Описание,column=none,width=400px');
        
        $this->FLD('origin', 'text', 'caption=Произход,column=none,width=400px');
        
        $this->FLD('inUseSince', 'date(format=d/m/Y)', 'caption=В&nbsp;употреба&nbsp;от');
        
        $this->FLD('amortNorm', 'percent', 'caption=ГАН,hint=Годишна амортизационна норма');
        
        $this->setDbUnique('num');
    }
    
    
    /**
     * Връща заглавието и мярката на перото за продукта
     *
     * Част от интерфейса: intf_Register
     */
    static function getItemRec($objectId)
    {
        $result = NULL;
        
        if ($rec = self::fetch($objectId)) {
            $result = (object)array(
                'num' => $rec->num,
                'title' => $rec->title,
                'features' => 'foobar' // @todo!
            );
        }
        
        return $result;
    }
    
    
    /**
     * @see crm_ContragentAccRegIntf::itemInUse
     * @param int $objectId
     */
    static function itemInUse($objectId)
    {
        // @todo!
    }
    
    
    /**
     * Интерфейсен метод на doc_DocumentIntf
     */
    function getDocumentRow($id)
    {
        if(!$id) return;
        
        $rec = $this->fetch($id);
        
        $row = new stdClass();
        $row->title = $rec->title;
        $row->author = $this->getVerbal($rec, 'createdBy');
        $row->state = $rec->state;
        $row->authorId = $rec->createdBy;
        
        return $row;
    }
 
}