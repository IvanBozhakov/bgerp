<?php

class cat_products_Packagings extends core_Detail
{
    
    
    /**
     * Име на поле от модела, външен ключ към мастър записа
     */
    var $masterKey = 'productId';
    
    
    /**
     * Заглавие
     */
    var $title = 'Опаковки';
    
    
    /**
     * Полета, които ще се показват в листов изглед
     */
    var $listFields = 'id, packagingId, quantity, netWeight, tareWeight, 
        sizeWidth, sizeHeight, sizeDepth,
        eanCode, customCode';
    
    
    /**
     * Плъгини за зареждане
     */
    var $loadList = 'cat_Wrapper, plg_RowTools, plg_SaveAndNew';
    
    
    /**
     * Активния таб в случай, че wrapper-а е таб контрол.
     */
    var $tabName = 'cat_Products';
    
    
    /**
     * Описание на модела (таблицата)
     */
    function description()
    {
        $this->FLD('productId', 'key(mvc=cat_Products,select=name)', 'input=hidden,silent');
        $this->FLD('packagingId', 'key(mvc=cat_Packagings,select=name)', 'input,silent,caption=Опаковка,mandatory');
        $this->FLD('quantity', 'double', 'input,caption=Количество');
        $this->FLD('netWeight', 'double', 'input,caption=Тегло->Нето');
        $this->FLD('tareWeight', 'double', 'input,caption=Тегло->Тара');
        $this->FLD('sizeWidth', 'double', 'input,caption=Габарит->Ширина');
        $this->FLD('sizeHeight', 'double', 'input,caption=Габарит->Височина');
        $this->FLD('sizeDepth', 'double', 'input,caption=Габарит->Дълбочина');
        $this->FLD('eanCode', 'gs1_TypeEan13', 'input,caption=Идентификация->EAN код');
        $this->FLD('customCode', 'varchar(64)', 'input,caption=Идентификация->Друг код');
        
        $this->setDbUnique('productId,packagingId');
    }
    
    
    /**
     * Извиква се след подготовката на toolbar-а за табличния изглед
     */
    function on_AfterPrepareListToolbar($mvc, $data)
    {
        if (count($mvc::getPackagingOptions($data->masterId)) > 0) {
            $data->toolbar->addBtn('Нова опаковка', array($mvc, 'edit', 'productId'=>$data->masterId, 'ret_url'=>getCurrentUrl()), 'id=btnAdd,class=btn-add');
        } else {
            $data->toolbar->removeBtn('btnAdd');
        }
    }
    
    
    /**
     * Извиква се след поготовката на колоните ($data->listFields)
     */
    function on_AfterPrepareListFields($mvc, $data)
    {
        $data->query->orderBy('#id');
    }
    
    
    /**
     * Подоготовка на бутоните на формата за добавяне/редактиране.
     *
     * @param core_Manager $mvc
     * @param stdClass $res
     * @param stdClass $data
     */
    function on_AfterPrepareEditToolbar($mvc, $data)
    {
        $data->form->toolbar->addBtn('Отказ', array($mvc->Master, 'single', $data->form->rec->productId), array('class'=>'btn-cancel'));
    }
    
    
    /**
     * Извиква се след подготовката на формата за редактиране/добавяне $data->form
     */
    function on_AfterPrepareEditForm($mvc, $data)
    {
        $options = $mvc::getPackagingOptions($data->form->rec->productId);
        
        if (empty($options)) {
            // Няма повече недефинирани опаковки
            redirect(getRetUrl());
        }
        $data->form->setOptions('packagingId', $options);
    }
    
    
    /**
     * Опаковките, определени от категорията на продукта и все още не дефинирани за този него.
     *
     * @param int ид на продукт
     * @return array опциите, подходящи за @link core_Form::setOptions()
     */
    static function getPackagingOptions($productId)
    {
        $categoryId = cat_Products::fetchField($productId, 'categoryId');
        
        // Извличаме id-тата на опаковките, дефинирани за категорията в масив.
        $packIds = cat_Categories::fetchField($categoryId, 'packagings');
        $packIds = type_Keylist::toArray($packIds);
        
        // Извличане на вече дефинираните за продукта опаковки
        $query = self::getQuery();
        $query->where("#productId = {$productId}");
        $recs = $query->fetchAll(NULL, 'packagingId');
        
        foreach ($recs as $rec) {
            if (isset($packIds[$rec->packagingId])) {
                unset($packIds[$rec->packagingId]);
            }
        }
        
        $options = array();
        
        if ($packIds) {
            $options = cat_Packagings::makeArray4Select(NULL, "#id IN (" . implode(',', $packIds) . ")");
        }
        
        return $options;
    }
    
    
    /**
     * @todo Чака за документация...
     */
    function on_AfterRenderDetail($mvc, $tpl, $data)
    {
        $tpl = new ET("<div style='display:inline-block;margin-top:10px;'>
                       <div style='background-color:#ddd;border-top:solid 1px #999; border-left:solid 1px #999; border-right:solid 1px #999; padding:5px; font-size:1.2em;'><b>Опаковки</b></div>
                       <div>[#1#]</div>
                       </div>", $tpl);
    }
}