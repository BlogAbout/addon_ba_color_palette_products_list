<?php
/**
 * @version 0.1.1
 * @author А.П.В.
 * @package ba_color_palette_products_list for Jshopping
 * @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
 * @license GNU/GPL
 **/
defined('_JEXEC') or die('Restricted access');

class plgJshoppingProductsBa_color_palette_products_list extends JPlugin
{
    private $_params;

    public function __construct($subject, $config)
    {
        \JSFactory::loadExtLanguageFile('ba_color_palette_products_list');
        parent::__construct($subject, $config);

        $addon = \JSFactory::getTable('addon', 'jshop');
        $addon->loadAlias('ba_color_palette_products_list');

        $jshopConfig = \JSFactory::getConfig();

        $this->_params = (object)$addon->getParams();
        $this->_image_product_live_path = $jshopConfig->image_product_live_path;
        $this->_image_attributes_live_path = $jshopConfig->image_attributes_live_path;

        if (isset($this->_params->enable) && $this->_params->enable == 1 && isset($this->_params->attr_id) && $this->_params->attr_id != 0) {
            $doc = \JFactory::getDocument();
            $doc->addStyleSheet(\JURI::root() . 'plugins/jshoppingproducts/ba_color_palette_products_list/ba_color_palette_products_list.css?ver=0.1.1');
            $doc->addScriptDeclaration("var color_palette_change_type = {$this->_params->change_type};");
            $doc->addScript(\JURI::root() . 'plugins/jshoppingproducts/ba_color_palette_products_list/ba_color_palette_products_list.js?ver=0.1.1');
        }

        $lang = \JSFactory::getLang();
        $field_name = $lang->get('name');

        if (isset($this->_params->enable) && $this->_params->enable == 1 && isset($this->_params->attr_id) && $this->_params->attr_id != 0) {
            $db = \JFactory::getDbo();

            $query = $db->getQuery(true)
                ->select("value_id, image, `{$field_name}` as name")
                ->from("#__jshopping_attr_values")
                ->where("attr_id = {$this->_params->attr_id}")
                ->order("value_ordering ASC");

            $db->setQuery($query);
            $list_colors = $db->loadObjectList();
            $color_palette = array();

            if ($list_colors) {
                foreach ($list_colors as $color) {
                    $color_palette[$color->value_id] = array(
                        'name' => $color->name,
                        'image' => $color->image
                    );
                }
            }

            $this->_color_palette = $color_palette;
        }
    }

    public function onBeforeDisplayProductList($products)
    {
        if (isset($this->_params->enable) && $this->_params->enable == 1 && isset($this->_params->attr_id) && $this->_params->attr_id != 0) {
            $db = \JFactory::getDbo();

            if (empty($this->_params->change_sort) || $this->_params->change_sort == 1) {
                $sorting = 'im.product_id ASC';
            } else if ($this->_params->change_sort == 2) {
                $sorting = 'im.product_id DESC';
            } else if ($this->_params->change_sort == 3) {
                $sorting = 'im.value_ordering ASC';
            } else if ($this->_params->change_sort == 4) {
                $sorting = 'im.value_ordering DESC';
            }

            foreach ($products as $product) {
                $query = '
					SELECT *
					FROM (
						SELECT i.product_id, p.image as image_name, a.attr_' . $this->_params->attr_id . ' as attr_value, v.value_ordering
						FROM #__jshopping_products_images AS i
						LEFT JOIN #__jshopping_products_attr AS a ON a.`ext_attribute_product_id` = i.`product_id`
						LEFT JOIN #__jshopping_products AS p ON a.`ext_attribute_product_id` = p.`product_id`
						LEFT JOIN #__jshopping_attr_values v ON v.`value_id` = a.attr_' . $this->_params->attr_id . '
						WHERE a.product_id = ' . intval($product->product_id) . '
						AND a.attr_' . $this->_params->attr_id . ' > 0
						GROUP BY attr_value
					) im
					GROUP BY im.product_id
					ORDER BY ' . $sorting . '
				';
                $db->setQuery($query);
                $prod_images = $db->loadObjectList();

                $count_images = 1;
                $product_name = htmlspecialchars($product->name);
                $prod_images_palette = '';

                $prod_images_content = '<div class="ba_color_palette" style="height: ' . $this->_params->height_images . 'px;">';
                if ($prod_images) {
                    $prod_images_palette .= '<div class="ba_color_palette_dots">';
                    foreach ($prod_images as $key => $val) {
                        if ($val->image_name != $product->product_name_image) {
                            $prod_images_content .= '<div class="color_palette_image' . ($count_images == 1 ? ' show' : '') . '" data-page="' . $val->attr_value . '">';
                            $image_url = $this->_image_product_live_path . '/' . \JSHelper::getPatchProductImage($val->image_name, 'thumb');
                            $prod_images_content .= '<a href="' . $product->product_link . '">';
                            $prod_images_content .= '<img class="jshop_img" src="' . $image_url . '" alt="' . $product_name . '" title="' . $product_name . '" style="height: ' . $this->_params->height_images . 'px;" />';
                            $prod_images_content .= '</a>';
                            $prod_images_content .= '</div>';

                            $prod_images_palette .= '<a href="#" class="dot_link' . ($count_images == 1 ? ' active' : '') . '" data-page="' . $val->attr_value . '"><img src="' . $this->_image_attributes_live_path . '/' . $this->_color_palette[$val->attr_value]['image'] . '" />';
                            if ($this->_params->show_info == 1) {
                                $prod_images_palette .= '<span class="dot_info">' . $this->_color_palette[$val->attr_value]['name'] . '</span>';
                            }
                            $prod_images_palette .= '</a>';
                            $count_images++;
                        }
                    }
                    $prod_images_palette .= '</div>';
                } else {
                    $prod_images_content .= '<div class="color_palette_image show" data-page="' . $count_images . '">';
                    $prod_images_content .= '<a href="' . $product->product_link . '">';
                    $prod_images_content .= '<img class="jshop_img" src="' . $product->image . '" alt="' . $product_name . '" title="' . $product_name . '" style="height: ' . $this->_params->height_images . 'px;" />';
                    $prod_images_content .= '</a>';
                    $prod_images_content .= '</div>';
                }
                $prod_images_content .= '</div>';

                $product->_tmp_var_image_block .= $prod_images_content . $prod_images_palette;
            }
        }
    }
}