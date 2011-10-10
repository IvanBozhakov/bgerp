<?php
/**
 * Продукти
 */
class store_Products extends core_Manager
{
    /**
     * Поддържани интерфейси
     */
    var $interfaces = 'store_AccRegIntf,acc_RegisterIntf';
    
    /**
     *  @todo Чака за документация...
     */
    var $title = 'Продукти';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $loadList = 'plg_RowTools, plg_Created, plg_Rejected, 
                     acc_plg_Registry, store_Wrapper, plg_Selected';
    
    
    /**
     * Права
     */
    var $canRead = 'admin,store';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $canEdit = 'admin,store';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $canAdd = 'admin,store';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $canView = 'admin,store';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $canDelete = 'admin,acc';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $listItemsPerPage = 300;
    
    
    /**
     *  @todo Чака за документация...
     */
    var $listFields = 'name, storeId, quantity, quantityNotOnPallets, quantityOnPallets, makePallets, tools=Пулт';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $rowToolsField = 'tools';
    
    
    function description()
    {
        $this->FLD('name',                 'key(mvc=cat_Products, select=name)',    'caption=Име,remember=info');
        $this->FLD('storeId',              'varchar(mvc=store_Stores,select=name)', 'caption=Склад');
        $this->FLD('quantity',             'int',                                   'caption=Количество->Общо');
        $this->FNC('quantityNotOnPallets', 'int',                                   'caption=Количество->Непалетирано');
        $this->FLD('quantityOnPallets',    'int',                                   'caption=Количество->На палети');
        $this->FNC('makePallets',          'varchar(255)',                          'caption=Палетирай');
    }
    
    
    /**
     * Извличане записите само от избрания склад
     *
     * @param core_Mvc $mvc
     * @param StdClass $res
     * @param StdClass $data
     */
    function on_BeforePrepareListRecs($mvc, &$res, $data)
    {
        $selectedStoreId = store_Stores::getCurrent();
        $data->query->where("#storeId = {$selectedStoreId}");
    }

    
    /**
     * При добавяне/редакция на палетите - данни по подразбиране 
     *
     * @param core_Mvc $mvc
     * @param stdClass $res
     * @param stdClass $data
     */
    function on_AfterPrepareEditForm($mvc, $res, $data)
    {
        // storeId
        $selectedStoreId = store_Stores::getCurrent();
        $data->form->setReadOnly('storeId', $selectedStoreId);

        $data->form->showFields = 'storeId,name,quantity';
    }
    
    
    /**
     * В зависимост от state-а
     *
     * @param core_Mvc $mvc
     * @param stdClass $row
     * @param stdClass $rec
     */
    function on_AfterRecToVerbal($mvc, $row, $rec)
    {
        $row->makePallets = Ht::createBtn('Палетирай', array('store_Pallets', 'add'));
        $row->quantityNotOnPallets = $rec->quantity - $rec->quantityOnPallets;
    }    
	
    
    /*******************************************************************************************
     * 
     * ИМПЛЕМЕНТАЦИЯ на интерфейса @see crm_ContragentAccRegIntf
     * 
     ******************************************************************************************/
    
    /**
     * @see crm_ContragentAccRegIntf::getItemRec
     * @param int $objectId
     */
    static function getItemRec($objectId)
    {
        $self = cls::get(__CLASS__);
        $result = null;
        
        if ($rec = $self->fetch($objectId)) {
            $result = (object)array(
                'num' => $rec->id,
                'title' => $rec->name,
                'features' => 'foobar' // @todo!
            );
        }
        
        return $result;
    }
    
    /**
     * @see crm_ContragentAccRegIntf::getLinkToObj
     * @param int $objectId
     */
    static function getLinkToObj($objectId)
    {
        $self = cls::get(__CLASS__);
        
        if ($rec  = $self->fetch($objectId)) {
            $result = ht::createLink($rec->name, array($self, 'Single', $objectId)); 
        } else {
            $result = '<i>неизвестно</i>';
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
     * КРАЙ НА интерфейса @see acc_RegisterIntf
     */

    
    /*
    function on_AfterPrepareListFilter($mvc, $data)
    {
        $nameOpt = array('Иван', 'Петър', 'Стоян');    	
        
        $data->listFilter->setOptions('name', $nameOpt);

        $data->listFilter->view = 'horizontal';
        
        $data->listFilter->showFields = 'name';
        
        $data->listFilter->toolbar->addSbBtn('Филтрирай', 'default', 'id=filter,class=btn-filter');
        
        $data->filter = $data->listFilter->input();
    }
    */
    
    
}