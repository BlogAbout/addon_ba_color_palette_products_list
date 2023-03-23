<?php
/**
 * @version 0.1.1
 * @author А.П.В.
 * @package ba_color_palette_products_list for Jshopping
 * @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
 * @license GNU/GPL
 **/
defined('_JEXEC') or die('Restricted access');

\JSFactory::loadExtLanguageFile('ba_color_palette_products_list');
\JFactory::getDocument()->addStyleDeclaration('.jshop_edit .controls { display: block; }');

$params = (object)$this->params;

$lang = \JSFactory::getLang();
$db = \JFactory::getDbo();
$query = $db->getQuery(true)
    ->select('`attr_id`, `' . $lang->get('name') . '` as name')
    ->from('#__jshopping_attr')
    ->where('`independent` = 0')
    ->order('`attr_ordering` ASC');
$db->setQuery($query);
$attr_list = $db->loadObjectList();

$yes_no_options = array();
$yes_no_options[] = \JHTML::_('select.option', '1', \JText::_('JYES'));
$yes_no_options[] = \JHTML::_('select.option', '0', \JText::_('JNO'));

$change_type = array(
    1 => _JSHOP_BACPPL_CHANGE_TYPE_HOVER,
    2 => _JSHOP_BACPPL_CHANGE_TYPE_ARROWS
);

$change_sort = array(
    1 => _JSHOP_BACPPL_TYPE_SORT_PROD_ASC,
    2 => _JSHOP_BACPPL_TYPE_SORT_PROD_DESC,
    3 => _JSHOP_BACPPL_TYPE_SORT_ATTR_ASC,
    4 => _JSHOP_BACPPL_TYPE_SORT_ATTR_DESC
);
?>
<fieldset class="form-horizontal">
    <legend><?php echo _JSHOP_BACPPL_NAME; ?></legend>

    <div class="control-group">
        <div class="control-label">
            <label class="hasTooltip"
                   title="<?php echo _JSHOP_BACPPL_ENABLE_DESC; ?>"><?php echo _JSHOP_BACPPL_ENABLE; ?></label>
        </div>
        <div class="controls">
            <?php echo \JHTML::_('select.genericlist', $yes_no_options, 'params[enable]', 'class="inputbox form-control form-select form-select-color-state"', 'value', 'text', (isset($params->enable) ? $params->enable : 1)); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <label class="hasTooltip"
                   title="<?php echo _JSHOP_BACPPL_ATTR_DESC; ?>"><?php echo _JSHOP_BACPPL_ATTR; ?></label>
        </div>
        <div class="controls">
            <?php if ($attr_list) { ?>
                <?php echo \JHTML::_('select.genericlist', $attr_list, 'params[attr_id]', 'class = "inputbox form-control form-select"', 'attr_id', 'name', (isset($params->attr_id) ? $params->attr_id : 0)); ?>
            <?php } else { ?>
                <p><?php echo _JSHOP_BACPPL_ATTR_ERROR; ?></p>
            <?php } ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <label class="hasTooltip"
                   title="<?php echo _JSHOP_BACPPL_HEIGHT_IMAGES_DESC; ?>"><?php echo _JSHOP_BACPPL_HEIGHT_IMAGES; ?></label>
        </div>
        <div class="controls">
            <input type="number"
                   min="1"
                   step="1"
                   name="params[height_images]"
                   value="<?php echo(isset($params->height_images) ? $params->height_images : 300); ?>"
                   class="inputbox form-control form-input"/>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <label class="hasTooltip"
                   title="<?php echo _JSHOP_BACPPL_CHANGE_TYPE_DESC; ?>"><?php echo _JSHOP_BACPPL_CHANGE_TYPE; ?></label>
        </div>
        <div class="controls">
            <?php echo \JHTML::_('select.genericlist', $change_type, 'params[change_type]', 'class = "inputbox form-control form-select"', 'value', 'text', (isset($params->change_type) ? $params->change_type : 1)); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <label class="hasTooltip"
                   title="<?php echo _JSHOP_BACPPL_SHOW_INFO_DESC; ?>"><?php echo _JSHOP_BACPPL_SHOW_INFO; ?></label>
        </div>
        <div class="controls">
            <?php echo \JHTML::_('select.genericlist', $yes_no_options, 'params[show_info]', 'class = "inputbox form-control form-select"', 'value', 'text', (isset($params->show_info) ? $params->show_info : 1)); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <label class="hasTooltip"
                   title="<?php echo _JSHOP_BACPPL_TYPE_SORT_DESC; ?>"><?php echo _JSHOP_BACPPL_TYPE_SORT; ?></label>
        </div>
        <div class="controls">
            <?php echo \JHTML::_('select.genericlist', $change_sort, 'params[change_sort]', 'class = "inputbox form-control form-select"', 'value', 'text', (isset($params->change_sort) ? $params->change_sort : 1)); ?>
        </div>
    </div>
</fieldset>