<?php
/**
 * Клас 'doc_DocumentPlg'
 *
 * Плъгин за мениджърите на документи
 *
 * @category   Experta Framework
 * @package    doc
 * @author     Milen Georgiev <milen@download.bg>
 * @copyright  2006-2011 Experta OOD
 * @license    GPL 3
 * @version    CVS: $Id: $
 */
class doc_ActivatePlg extends core_Plugin
{

    /**
     * Подготвя полетата threadId и folderId, ако има originId и threadId
     */
	function on_AfterPrepareEditForm($mvc, $data)
	{   
        // В записа на формата "тихо" трябва да са въведени от Request originId, threadId или folderId
        $rec = $data->form->rec;

        if($rec->id) {
            $exRec = $mvc->fetch($rec->id);
            $mvc->threadId = $exRec->threadId;
        }
        
        if($exRec) {
            $state = $exRec->state;
        } else {
            $state = 'draft';
        }
        
        if($state == 'draft') {

            // TODO: Да се провери дали потребителя има права за активиране
            $data->form->toolbar->addSbBtn('Активиране', 'active', 'class=btn-activation,order=10.00015');

        }
	}


    /**
     * Ако е натиснат бутона 'Активиране" добавя състоянието 'active' в $form->rec
     */
    function on_AfterInputEditForm($mvc, $form)
    {
        if($form->isSubmitted()) {
            if($form->cmd == 'active') {
                $form->rec->state = 'active';
            }
        }
    }
 
 

}