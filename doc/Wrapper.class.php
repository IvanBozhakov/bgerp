<?php



/**
 * Клас 'doc_Wrapper'
 *
 * Поддържа системното меню и табове-те на пакета 'doc'
 *
 *
 * @category  bgerp
 * @package   doc
 * @author    Milen Georgiev <milen@download.bg>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 * @link
 */
class doc_Wrapper extends plg_ProtoWrapper
{
    
    /**
     * Описание на опаковката от табове
     */
    function description()
    {        
        $this->TAB('doc_Folders', 'Папки');
        
        // Зареждаме няколко променливи, определящи треда и папката от рекуеста
        $originId    = request::get('originId', 'int');
        $containerId = request::get('containerId', 'int');
        $threadId    = request::get('threadId', 'int');
        $folderId    = request::get('folderId', 'int');
        
        if(!$threadId) {
            $threadId = $invoker->threadId;
        }
        
        if($originId && !$threadId) {
            $threadId = doc_Containers::fetchField($originId, 'threadId');
        }
        
        // Ако е указан контейнера, опитваме се да определим нишката
        if($containerId && !$threadId) {
            $threadId = doc_Containers::fetchField($containerId, 'threadId');
        }
        
        // Определяме папката от треда
        if($threadId) {
            $folderId = doc_Threads::fetchField($threadId, 'folderId');
        }
        
        // Вадим или запомняме последния отворен тред в сесията
        if(!$threadId) {
            $threadId = Mode::get('lastThreadId');
        } else {
            Mode::setPermanent('lastThreadId', $threadId);
        }
        
        // Вадим или запомняме последната отворена папка в сесията
        if(!$folderId) {
            $folderId = Mode::get('lastfolderId');
        } else {
            Mode::setPermanent('lastfolderId', $folderId);
        }
        
        $threadsUrl = array();
        $filesUrl = array();
        
        if($folderId && (doc_Folders::haveRightFor('single', $folderId))) {
            $threadsUrl = array('doc_Threads', 'list', 'folderId' => $folderId);
            $filesUrl = array('doc_Files', 'list', 'folderId' => $folderId);    
        }
        
        $this->TAB($threadsUrl, 'Теми');
        
        $containersUrl = array();
        
        if($threadId) {
            if(doc_Threads::haveRightFor('single', $threadId)) {
                $folderId = request::get('folderId', 'int');
                $containersUrl = array('doc_Containers', 'list', 'threadId' => $threadId, 'folderId' => $folderId);
            }
        }
        
        $this->TAB($containersUrl, 'Нишка');
        
        $this->TAB('doc_Search', 'Търсене');
        
        $this->TAB($filesUrl, 'Файлове');
        
        $this->TAB('doc_UnsortedFolders', 'Проекти');
                
        // Показва таба за коментари, само ако имаме права за листване
        $this->TAB('doc_Comments', 'Коментари', 'admin');
        
        // Показва таба за входящите документи, само ако имаме права за листване
        $this->TAB('doc_Incomings', 'Входящи', 'admin');

        // Показва таба генерирани PDF файлове, ако имаме права
        $this->TAB('doc_PdfCreator', 'PDF файлове', 'admin');
        
        // Показва таба генерирани PDF файлове, ако имаме права
        $this->TAB('doc_ThreadUsers', 'Отношения', 'admin');
    }
}
