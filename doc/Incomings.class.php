<?php 


/**
 * Входящи документи
 *
 * Създава на документи от файлове.
 *
 * @category  bgerp
 * @package   doc
 * @author    Yusein Yuseinov <yyuseinov@gmail.com>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 */
class doc_Incomings extends core_Master
{
    
    
    /**
     * Поддържани интерфейси
     */
    var $interfaces = 'doc_DocumentIntf';
    
    
    /**
     * Заглавие на модела
     */
    var $title = 'Входящи документи';
    
    
    /**
     * 
     */
    var $singleTitle = 'Входящ документ';
    
    
    /**
     * Кой има право да чете?
     */
    var $canRead = 'admin, doc';
    
    
    /**
     * Кой има право да променя?
     */
    var $canEdit = 'user';
    
    
    /**
     * Кой има право да добавя?
     */
    var $canAdd = 'user';
    
    
    /**
     * Кой има право да го види?
     */
    var $canView = 'user';
    
    
    /**
     * Кой може да го разглежда?
     */
    var $canList = 'ceo';
    
    
    /**
     * Необходими роли за оттегляне на документа
     */
    var $canReject = 'admin, doc';
    
    
    /**
     * Кой има право да го изтрие?
     */
    var $canDelete = 'no_one';
    
    
    /**
     *
     */
    var $canActivate = 'user';
    
    
    /**
     * Кой има права за
     */
    var $canDoc = 'admin, doc, user';
    
    
    /**
     * Плъгини за зареждане
     */
    var $loadList = 'doc_Wrapper, plg_RowTools, doc_DocumentPlg, 
         plg_Printing, plg_Sorting, plg_Search, doc_ActivatePlg, bgerp_plg_Blank';
    
    
    /**
     * Сортиране по подразбиране по низходяща дата
     */
    var $defaultSorting = 'createdOn=down';
    
    
    /**
     * Нов темплейт за показване
     */
    var $singleLayoutFile = 'doc/tpl/SingleLayoutIncomings.shtml';
    
    
    /**
     * Икона по подразбиране за единичния обект
     */
    var $singleIcon = 'img/16/page_attach.png';
    
    
    /**
     * Абревиатура
     */
    var $abbr = "D";
    
    
    /**
     * Полето "Заглавие" да е хипервръзка към единичния изглед
     */
    var $rowToolsSingleField = 'title';
    
    
    /**
     * Полета, които ще се показват в листов изглед
     */
    var $listFields = 'id, title, date, total, createdOn, createdBy';
    
    
    /**
     * Полета от които се генерират ключови думи за търсене (@see plg_Search)
     */
    var $searchFields = 'title, fileHnd, date, total, keywords';

    
    /**
     * Описание на модела
     */
    function description()
    {
        $this->FLD('title', 'varchar', 'caption=Заглавие, width=100%, mandatory, recently');
        $this->FLD('fileHnd', 'fileman_FileType(bucket=Documents)', 'caption=Файл, width=50%, mandatory');
        $this->FLD('number', 'varchar', 'caption=Номер, width=50%');
        $this->FLD('date', 'date', 'caption=Дата, width=50%');
        $this->FLD('total', 'double(decimals=2)', 'caption=Сума, width=50%');
        $this->FLD('keywords', 'text', 'caption=Описание, width=100%');
        $this->FLD("dataId", "key(mvc=fileman_Data)", 'caption=Данни, input=none');
        
        $this->setDbUnique('dataId');
    } 

    
    /**
     * 
     */
    static function on_AfterRenderSingleLayout($mvc, &$tpl, &$data)
    {   
        // $tpl->replace(log_Documents::getSharingHistory($data->rec->containerId, $data->rec->threadId), 'shareLog');
    }
    
    
    /**
     * 
     * 
     */
    function on_AfterPrepareEditForm($mvc, &$data)
    {
        // Предложения в полето Заглавие
        $titleSuggestions['Фактура'] = 'Фактура';
        $titleSuggestions['Платежно нареждане'] = 'Платежно нареждане';
        $titleSuggestions['Товарителница'] = 'Товарителница';
        $data->form->prependSuggestions('title', $titleSuggestions);

        // Манупулатора на файла
        $fileHnd = $mvc->db->escape(Request::get('fh'));
        
        // Вземаме текстовата част
        // TODO може и да се направи форматиране - Интервалите да се заменят с един
        // може и повтарящите думи да се премахнат
        $content = trim(fileman_Indexes::getInfoContentByFh($fileHnd, 'text'));
        
        // Вземаме текста извлечен от OCR
        $contentOcr = trim(fileman_Indexes::getInfoContentByFh($fileHnd, 'textOcr'));
        
        // Ключовите думи ги вземаме от OCR текста, ако няма тогава от обикновенния
        $keyWords = ($contentOcr) ? $contentOcr : $content;
        
        // Ако създаваме документа от файл
        if (($fileHnd) && (!$data->form->rec->id)) {
            
            // Ескейпваме файл хендлъра
            $fileHnd = $mvc->db->escape($fileHnd);
            
            // Масив с баркодовете
            $barcodesArr = fileman_Indexes::getInfoContentByFh($fileHnd, 'barcodes');
            
            // Ако има масив и съдържанието е празно
            if (is_array($barcodesArr) && (!$content)) {
                foreach ($barcodesArr as $barcodesArrPage) {
                    
                    foreach ($barcodesArrPage as $barcodeObj) {
                        
                        // Вземаме cid'a на баркода
                        $cid = log_Documents::getDocumentCidFromURL($barcodeObj->code);

                        // Ако не може да се намери cid, прескачаме
                        if (!$cid) continue;
    
                        // Попълваме описанието за файла
                        $data->form->setDefault('title', "Сканиран");    
                        
                        // Вземаме данните за контейнера
                        $cRec = doc_Containers::fetch($cid);
    
                        // Задаваме папката и нишката
                        $data->form->rec->folderId = $cRec->folderId;
                        $data->form->rec->threadId = $cRec->threadId;
                        
                        // Ако открием съвпадение
                        // Прекъсваме цикъла
                        break;
                    }
                    
                    // Ако сме открили съвпадение, прекъсваме цикъла
                    if ($cid) break;
                }    
            }

            // Попълваме описанието за файла
            $data->form->setDefault('keywords', $keyWords);    
            
            // Файла да е избран по подразбиране
            $data->form->setDefault('fileHnd', $fileHnd);
            
            // Файла да е само за четене
//            $data->form->setReadOnly('fileHnd'); // TODO след като се промени core_FieldSet
        }
        
        // Ако създаваме нов и не е сканиран
        if ((!$data->form->rec->id) && (!$scanned)) {

            // Вземаме от сесията id' то на текущата папка
            $currFolderId = Mode::get('lastfolderId');
            if ($currFolderId) {
                
                // Задаваме id' то на текущата папка
                $data->form->rec->folderId = $currFolderId;
            }
        }
    }
    
    
    /**
     * 
     */
    function on_AfterInputEditForm($mvc, $form)
    {
        // Ако формата е изпратена
        if (($form->isSubmitted()) && (!$form->rec->id)) {
            
            // id от fileman_Data
            $dataId = fileman_Files::fetchByFh($form->rec->fileHnd, 'dataId');
            
            // Проверяваме да няма създаден документ за съответния запис
            if ($dRec = static::fetch("#dataId = '{$dataId}'")) {
                
                // Съобщение за грешка
                $error = "|Има създаден документ за файла|*";
                
                // Ако имаме права за single на документа
                if ($mvc->haveRightFor('single', $dRec)) {
                    
                    // Заглавието на документа
                    $title = static::getVerbal($dRec, 'title');
                    
                    // Създаваме линк към single'a на документа
                    $link = ht::createLink($title, array($mvc, 'single', $dRec->id));    
                    
                    // Добавяме към съобщението за грешка самия линк
                    $error .= ": {$link}";
                }
                
                // Задаваме съобщението за грешка
                $form->setError('fileHnd', $error);    
            }
        }
    }

    
    /**
     * 
     */
    function on_BeforeSave(&$invoker, &$id, &$rec)
    {
        // id от fileman_Data
        $dataId = fileman_Files::fetchByFh($rec->fileHnd, 'dataId');
        $rec->dataId = $dataId;
    }
    
    
    /**
     * Връща ключовите думи на документа
     * @todo Да се реализира
     * 
     * @return;
     */
    static function getKeywords($fileHnd)
    {
        
        return "test {$fileHnd}";
    }  
    
    
    /**
     * Създава документ от сканиран файл
     * 
     * @param fileHnd $fh - Манупулатора на файла, за който ще се създаде документ
     * @param integer $containerId - doc_Containers id' то на файла
     * 
     * @return integer $id - id' то на записания документ
     */
    static function createFromScannedFile($fh, $containerId)
    {
        // Записите за файла
        $fRec = fileman_Files::fetchByFh($fh);
        
        // id' то на данните на докуемента
        $dataId = $fRec->dataId;

        // Ако има документ със същото id
        if (doc_Incomings::fetch("#dataId = '{$dataId}'")) {

            return ;
        }
        
        // Вземаме записите на документа, от който е изпратен файла
        $docProxy = doc_Containers::getDocument($containerId);
        $docRow = $docProxy->getDocumentRow();
        
        // Вземаме данните законтейнера
        $cRec = doc_Containers::fetch($containerId);
        
        // Създаваме, записа който ще запишем
        $rec = new stdClass();
        $rec->title = "Сканиран \"{$docRow->title}\"";
        $rec->fileHnd = $fh;
        $rec->keywords = static::getKeywords($fh);
        $rec->dataId = $dataId;
        $rec->folderId = $cRec->folderId;
        $rec->threadId = $cRec->threadId;
        $rec->state = 'closed';
        
        // Създаваме документа
        $id = doc_Incomings::save($rec);
        
        return $id;
    }
    
    
    /**
     * Връща прикачения файл в документа
     * 
     * @param mixed $rec - id' то на записа или самия запис, в който ще се търси
     * 
     * @return arrray - Масив името на файла и манипулатора му (ключ на масива)
     */
    function getAttachments($rec)
    {
        // Ако не е обект, тогава вземаме записите за съответния документ
        if (!is_object($rec)) {
            $rec = static::fetch($rec);
        }
        
        // Маниппулатора на файла
        $fh = $rec->fileHnd;
        
        // Вземаме записа на файла
        $fRec = fileman_Files::fetchByFh($fh);
        
        // Масив с манипулатора и името на файла
        $file[$fh] = $fRec->name;
        
        return $file;
    }
    
    
	/**
     * Реализация  на интерфейсния метод ::getThreadState()
     */
    static function getThreadState($id)
    {
        return 'opened';
    }
    
    
    /**
     * 
     */
    function getDocumentRow($id)
    {
        // Вземаме записите
        $rec = $this->fetch($id);
        
        $row = new stdClass();
        
        $row->title = $this->getVerbal($rec, 'title');

        $row->author = $this->getVerbal($rec, 'createdBy');
        
        $row->authorId = $rec->createdBy;
        
        $row->state = $rec->state;

        return $row;
    }
    
    
    /**
     * Изпълнява се след създаването на модела
     */
    static function on_AfterSetupMVC($mvc, &$res)
    {
        // Инсталиране на кофата
        $Bucket = cls::get('fileman_Buckets');
        $res .= $Bucket->createBucket('Documents', 'Файлове във входящите документи', NULL, '300 MB', 'user', 'user');
    }
    
    
	/**
	 * Връща файла, който се използва в документа
     * 
     * @param object $rec - Запис
     */
     function getLinkedFiles($rec)
     {
         // Ако не е обект
         if (!is_object($rec)) {
             
             // Извличаваме записа
             $rec = $this->fetch($rec);    
         }
         
         // Вземаме записите за файла
         $fRec = fileman_Files::fetchByFh($rec->fileHnd);
         
         // Добавяме в масива манипулатора и името на файла
         $fhArr[$rec->fileHnd] = fileman_Files::getVerbal($fRec, 'name');
         
         return $fhArr;
     }
}