<?php

/**
 * Клас 'common_DistrictCourts - Окръжни съдилища'
 *
 * @todo: Да се документира този клас
 *
 * @category   Experta Framework
 * @package    common
 * @author
 * @copyright  2006-2011 Experta OOD
 * @license    GPL 2
 * @version    CVS: $Id:$\n * @link
 * @since      v 0.1
 */
class common_DistrictCourts extends core_Manager
{
    /**
     *  @todo Чака за документация...
     */
    var $loadList = 'plg_Created, plg_RowTools, common_Wrapper';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $listFields = 'id, city, type, code, tools=Пулт';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $rowToolsField = 'tools';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $title = 'Окръжни съдилища';
    

    /**
     *  @todo Чака за документация...
     */
    var $canRead = 'admin, common';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $canEdit = 'admin, common';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $canAdd = 'admin, common';
    
    
    /**
     *  @todo Чака за документация...
     */
    var $canDelete = 'admin, common';
    
    
    /**
     * Описание на модела
     */
    function description()
    {
        $this->FLD('city',  'varchar', 'caption=Град, mandatory');
        $this->FLD('type',  'enum(cityCourt=Градски съд, districtCourt=Окръжен съд )', 'caption=Обхват, mandatory');
        $this->FLD('code',  'varchar(3)', 'caption=Код, mandatory');
    }
    
    
    /**
     * Сортиране по name
     *
     * @param core_Mvc $mvc
     * @param StdClass $res
     * @param StdClass $data
     */
    function on_BeforePrepareListRecs($mvc, &$res, $data)
    {
        $data->query->orderBy('#city');
    }
    
    
    /**
     * Записи за инициализиране на таблицата
     *
     * @param core_Mvc $mvc
     * @param stdClass $res
     */
    function on_AfterSetupMvc($mvc, &$res)
    {
        $data = array(
            array('city' => 'Благоевград',    'type' => 'districtCourt', 'code' => '120'),
            array('city' => 'Бургас',         'type' => 'districtCourt', 'code' => '210'),
            array('city' => 'Варна',          'type' => 'districtCourt', 'code' => '310'),
            array('city' => 'Велико Търново', 'type' => 'districtCourt', 'code' => '410'),
            array('city' => 'Видин',          'type' => 'districtCourt', 'code' => '130'),
            array('city' => 'Враца',          'type' => 'districtCourt', 'code' => '140'),
            array('city' => 'Габрово',        'type' => 'districtCourt', 'code' => '420'),
            array('city' => 'Добрич',         'type' => 'districtCourt', 'code' => '320'),
            array('city' => 'Кюстендил',      'type' => 'districtCourt', 'code' => '150'),
            array('city' => 'Кърджали',       'type' => 'districtCourt', 'code' => '510'),
            array('city' => 'Ловеч',          'type' => 'districtCourt', 'code' => '430'),
            array('city' => 'Монтана',        'type' => 'districtCourt', 'code' => '160'),
            array('city' => 'Перник',         'type' => 'districtCourt', 'code' => '170'),
            array('city' => 'Плевен',         'type' => 'districtCourt', 'code' => '440'),
            array('city' => 'Пловдив',        'type' => 'districtCourt', 'code' => '530'),
            array('city' => 'Пазарджик',      'type' => 'districtCourt', 'code' => '520'),
            array('city' => 'Разград',        'type' => 'districtCourt', 'code' => '330'),
            array('city' => 'Русе',           'type' => 'districtCourt', 'code' => '450'),
            array('city' => 'София',          'type' => 'cityCourt',     'code' => '110'),
            array('city' => 'София-област',   'type' => 'districtCourt', 'code' => '180'),
            array('city' => 'Сливен',         'type' => 'districtCourt', 'code' => '220'),
            array('city' => 'Силистра',       'type' => 'districtCourt', 'code' => '340'),
            array('city' => 'Смолян',         'type' => 'districtCourt', 'code' => '540'),
            array('city' => 'Стара Загора',   'type' => 'districtCourt', 'code' => '550'),
            array('city' => 'Търговище',      'type' => 'districtCourt', 'code' => '350'),
            array('city' => 'Хасково',        'type' => 'districtCourt', 'code' => '560'),
            array('city' => 'Шумен',          'type' => 'districtCourt', 'code' => '360'),
            array('city' => 'Ямбол',          'type' => 'districtCourt', 'code' => '230')                        
        );
        
        if(!$mvc->fetch("1=1")) {
            
            $nAffected = 0;
            
            foreach ($data as $rec) {
                $rec = (object)$rec;
                
                if (!$this->fetch("#city='{$rec->city}'")) {
                    if ($this->save($rec)) {
                        $nAffected++;
                    }
                }
            }
        }
        
        if ($nAffected) {
            $res .= "<li>Добавени са {$nAffected} записа.</li>";
        }
    }
    
}