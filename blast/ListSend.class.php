<?php 


/**
 * Лог на изпращаните писма
 */
class blast_ListSend extends core_Detail
{
	

    /**
     *  Заглавие на таблицата
     */
    var $title = "Лог на изпращаните писма";
    
    
    /**
     * Права
     */
    var $canRead = 'admin, blast';
    
    
    /**
     *  
     */
    var $canEdit = 'no_one';
    
    
    /**
     *  
     */
    var $canAdd = 'no_one';
    
    
    /**
     *  
     */
    var $canView = 'admin, blast';
    
    
    /**
     *  
     */
    var $canList = 'admin, blast';
    
    /**
     *  
     */
    var $canDelete = 'no_one';
    
	
	/**
	 * 
	 */
	var $canBlast = 'admin, blast';
	
    
    /**
     * 
     */
	var $loadList = 'blast_Wrapper';
       	
	
	/**
	 * 
	 */
	var $masterKey = 'emailId';
	
	
	/**
	 * Описание на модела
	 */
	function description()
	{
		$this->FLD('mail', 'key(mvc=blast_ListDetails, select=key)', 'caption=Е-мейл');
		$this->FLD('emailId', 'key(mvc=blast_Emails, select=subject)', 'caption=Бласт');
		$this->FLD('sended', 'datetime', 'caption=Дата, input=none');
		
		$this->setDbUnique('mail,emailId');
	}
}