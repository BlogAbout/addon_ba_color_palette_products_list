<?php
/**
 * @version 0.1.1
 * @author А.П.В.
 * @package ba_color_palette_products_list for Jshopping
 * @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
 * @license GNU/GPL
 **/
defined('_JEXEC') or die('Restricted access');

$name = 'JoomShopping addon - Color Palette in Products List';
$type = 'plugin';
$element = 'ba_color_palette_products_list';
$folders = array('jshoppingproducts');
$version = '0.1.1';
$cache = '{"creationDate":"28.08.2019","author":"Blog About","authorEmail":"info@blog-about.ru","authorUrl":"https://blog-about.ru","version":"' . $version . '"}';
$params = '{}';

$db = \JFactory::getDbo();

foreach ($folders as $folder) {
    $db->setQuery("
		SELECT `extension_id`
		FROM `#__extensions`
		WHERE `element` = '$element' AND `folder` = '$folder'
	");

    $id = $db->loadResult();

    if (!$id) {
        $query = "
			INSERT
				INTO `#__extensions` (`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`)
				VALUES ('$name', '$type', '$element', '$folder', 0, 1, 1, 0, 'addslashes($cache)', '" . addslashes($params) . "')
		";
    } else {
        $query = "
			UPDATE `#__extensions`
			SET
				`name` = '$name',
				`manifest_cache` = '" . addslashes($cache) . "',
				`params` = '" . addslashes($params) . "'
			WHERE `extension_id` = $id
		";
    }

    $db->setQuery($query);
    $db->execute();
}

$addon = \JSFactory::getTable('addon', 'jshop');
$addon->loadAlias($element);
$addon->set('name', $name);
$addon->set('version', $version);
$addon->set('uninstall', '/components/com_jshopping/addons/' . $element . '/uninstall.php');
$addon->store();