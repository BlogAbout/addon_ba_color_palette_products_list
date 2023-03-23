<?php
/**
 * @version 0.1.1
 * @author А.П.В.
 * @package ba_color_palette_products_list for Jshopping
 * @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
 * @license GNU/GPL
 **/
defined('_JEXEC') or die('Restricted access');

$db = \JFactory::getDbo();

$db->setQuery("
	DELETE FROM `#__extensions`
	WHERE `element` = 'ba_color_palette_products_list' AND `folder` = 'jshoppingproducts' AND `type` = 'plugin'
");
$db->execute();

\JFolder::delete(JPATH_ROOT . '/components/com_jshopping/addons/ba_color_palette_products_list/');
\JFolder::delete(JPATH_ROOT . '/components/com_jshopping/lang/ba_color_palette_products_list/');
\JFolder::delete(JPATH_ROOT . '/plugins/jshoppingproducts/ba_color_palette_products_list/');