<?php

/**
 * Категории на статиите
 *
 *
 * @category  bgerp
 * @package   forum
 * @author    Ивелин Димов <ivelin_pdimov@abv.bg>
 * @copyright 2006 - 2012 Experta OOD
 * @license   GPL 3
 * @since     v 0.1
 */

class forum_Categories extends core_Manager {
	
	
	/**
	 * Заглавие на страницата
	 */
	var $title = 'Категории на дъските';
	
	
	/**
	 * Зареждане на необходимите плъгини
	 */
	var $loadList = 'plg_RowTools, forum_Wrapper';
	
	
	/**
	 * Полета за изглед
	 */
	var $listFields='id, title, order';
	
	
	/**
	 * Кой може да добавя 
	 */
	var $canAdd='cms, ceo, admin';
	
	
	/**
	 * Кой може да редактира
	 */
	var $canEdit='cms, ceo, admin';
	
	
	/**
	 * Кой може да изтрива
	 */
	var $canDelete='cms, ceo, admin';
	
	
	/**
	 * Кой може да преглежда списъка с коментари
	 */
	var $canList='cms, ceo, admin';
	
	
	/**
	 * Описание на модела
	 */
	function description()
	{
		$this->FLD('title', 'varchar(40)', 'caption=Заглавие,mandatory');
		
		$this->FLD('canSeeCategory', 'keylist(mvc=core_Roles,select=role,groupBy=type)', 'caption=Роли за достъп->Виждане');
		$this->FLD('order', 'int', 'caption=Подредба');

		$this->setDbUnique('title');
	}
	
	
	static function prepareCategories(&$data)
	{
		// Взимаме Заявката към Категориите
		$query = static::getQuery();
		if($data->category) {
			$query->where($data->category);
		}
		
		
		// За всеки запис създаваме клас, който натрупваме в масива $data
		while($rec = $query->fetch()) {
            
			// Добавяме категорията като нов елемент на $data
			$cat = new stdClass();
			$cat->id = $rec->id;
			$cat->title = static::getVerbal($rec, 'title');
			$url = array('forum_Boards', 'Forum','cat'=> $cat->id);
			$cat->title = ht::createLink($cat->title, $url);
			$data->categories[]= $cat;
		}
	}
}