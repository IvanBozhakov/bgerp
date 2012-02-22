<?php
/**
 * Финансови индекси
 *
 *
 * @category  bgerp
 * @package   currency
 * @author    Milen Georgiev <milen@download.bg>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 */
class currency_FinIndexes extends core_Manager {
    /**
     * Плъгини за зареждане
     */
    var $loadList = 'plg_Created, plg_RowTools, currency_Wrapper,
                     plg_Sorting, plg_State2';
    
    
    /**
     * Заглавие
     */
    var $title = 'Индекси';
    
    
    /**
     * Полета, които ще се показват в листов изглед
     */
    var $listFields = "indexName, period, forDate, indexValue";
    
    
    /**
     * Описание на модела
     */
    function description()
    {
        $this->FLD('indexName',  'varchar(64)', 'caption=Индекс,mandatory');
        $this->FLD('period',     'enum(ON=овърнайт,
                                       1W=1 седмица,
                                       2W=2 седмици,
                                       3W=3 седмици,
                                       1M=1 месец,
                                       2M=2 месеца,
                                       3M=3 месеца,
                                       4M=4 месеца,
                                       5M=5 месеца,
                                       6M=6 месеца,
                                       7M=7 месеца,
                                       8M=8 месеца,
                                       9M=9 месеца,
                                       10M=10 месеца,
                                       11M=11 месеца,
                                       12M=12 месеца)', 'caption=Срочност,mandatory');
        $this->FLD('forDate',    'date',                'caption=За дата, mandatory');
        $this->FLD('indexValue', 'double',              'caption=Стойност, mandatory');
    }
    
    
    
    /**
     * Въвеждане на индекси от CSV файлове 
     */
    function act_LoadData()
    {
        // Зареждаме файлове за обработка
        $csvFile = __DIR__ . "/csv/hist_EURIBOR_2011.csv";
        
        // indexName
        if (preg_match("/(EURIBOR){1}/i", $csvFile)) {
            $indexName = 'EURIBOR';    
        }
        // ENDOF indexName
        
        $createdRecs = 0;
        
        // Зарежда, обработва и записва в базата CSV файл
        if (($handle = @fopen($csvFile, "r")) !== FALSE) {
            while (($csvRow = fgetcsv($handle, 10000, ",")) !== FALSE) {
                if (empty($coulmns)) {
                    // Колоните са броя на датите във файла + 1 
                    $columns = count($csvRow);
                }
                
                // Контейнер със всички редове от CSV файла 
                $csvContent[] = $csvRow;                 
            }
            
            fclose($handle);
            
            // За всяка дата
            for ($j = 1; $j <= ($columns - 1); $j++) {
                $forDate = substr($csvContent[0][$j], 6, 4) . "-" . substr($csvContent[0][$j], 3, 2) . "-" . substr($csvContent[0][$j], 0, 2);
                
                // За всеки ред след първия от CSV файла
                for ($k = 1; $k <= (count($csvContent) - 1); $k++) {
                    $period = $csvContent[$k][0];
                    
                    switch ($period) {
                        case "ON":
                        case "On":
                        case "on":
                            $period = "ON";    
                            break;        
                            
                        case "1w":
                        case "1W":
                        case "sw":
                        case "Sw":    
                        case "SW":                            
                            $period = "1W";    
                            break;
                            
                        case "2w":
                        case "2W":
                            $period = "2W";    
                            break;                            
                            
                        case "3w":
                        case "3W":
                            $period = "3W";    
                            break;

                        case "1m":
                        case "1M":
                            $period = "1M";    
                            break;                            
                            
                        case "2m":
                        case "2M":
                            $period = "2M";    
                            break;

                        case "3m":
                        case "3M":
                            $period = "3M";    
                            break;                            
                            
                        case "4m":
                        case "4M":
                            $period = "4M";    
                            break;                            
                            
                        case "5m":
                        case "5M":
                            $period = "5M";    
                            break;

                        case "6m":
                        case "6M":
                            $period = "6M";    
                            break;

                        case "7m":
                        case "7M":
                            $period = "7M";    
                            break;

                        case "8m":
                        case "8M":
                            $period = "8M";    
                            break;

                        case "9m":
                        case "9M":
                            $period = "9M";    
                            break;

                        case "10m":
                        case "10M":
                            $period = "10M";    
                            break;

                        case "11m":
                        case "11M":
                            $period = "11M";    
                            break;

                        case "12m":
                        case "12M":
                            $period = "12M";    
                            break;
                    }
                    
                    unset($rec->id);
                    $rec->indexName  = $indexName;
                    $rec->period     = $period;
                    $rec->forDate    = $forDate;
                    $rec->indexValue = $csvContent[$k][$j];
                    $rec->createdBy  = -1;
                    
                    $existingRecId = currency_FinIndexes::fetchField("#indexName      = '{$rec->indexName}'
                                                                      AND #period     = '{$rec->period}'
                                                                      AND #forDate    = '{$rec->forDate}'
                                                                      AND #indexValue = '{$rec->indexValue}'", 'id');
                    
                    if (empty($existingRecId)) {
                        $this->save($rec);
                        $createdRecs++;
                    }
                }
            }
            
            $res = "Създадени са {$createdRecs} нови индекса.</li>";
        } else {
            
            $res = "<li style='color:red'>Не може да бъде отворен файла '{$csvFile}'";
        }
        
        return $res;
    }    
    
}