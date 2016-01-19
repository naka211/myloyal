<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('formbehavior.chosen', 'select');
$listOrder     = $this->escape($this->filter_order);
$listDirn      = $this->escape($this->filter_order_Dir);
?>
<form action="index.php?option=com_business&view=points" method="post" id="adminForm" name="adminForm">
<div class="row-fluid">		
<!--    <div class="span6">			
        <?php //echo JText::_('COM_HELLOWORLD_HELLOWORLDS_FILTER'); ?>		
        <?php				
//            echo JLayoutHelper::render(					
//                    'joomla.searchtools.default',	
//                    array('view' => $this)				
//                    );			
        ?>		
    </div>-->
    <div id="filter-bar" class="btn-toolbar">
        <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_CONTACT_FILTER_SEARCH_DESC');?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_BUSINESS_SEARCH_IN_NAME'); ?>" />
        </div>
        <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span class="icon-search"></span></button>
                <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><span class="icon-remove"></span></button>
        </div>
        <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
        </div>
    </div>
</div>
<table class="table table-striped table-hover">
    <thead>
        <tr>
                <th width="1%"><?php echo JText::_('COM_HELLOWORLD_NUM'); ?></th>
                <th width="2%">
                        <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="35%">
                        <?php echo JHtml::_('grid.sort', 'COM_BUSINESS_BUSINESSES_NAME', 'businessName', $listDirn, $listOrder); ?>	
                </th>
                <th width="35%">
                        <?php echo JHtml::_('grid.sort', 'COM_BUSINESS_BUSINESSES_USERNAME', 'username', $listDirn, $listOrder); ?>	
                </th>
                <th width="20%">
                        <?php echo JHtml::_('grid.sort', 'COM_BUSINESS_BUSINESSES_PROMOTION_POINT', 'point', $listDirn, $listOrder); ?>	
                </th>
                <th width="7%">
                        <?php echo JHtml::_('grid.sort', 'COM_HELLOWORLD_ID', 'id', $listDirn, $listOrder); ?>		
                </th>
        </tr>
    </thead>
    <tfoot>
            <tr>
                    <td colspan="5">
                            <?php echo $this->pagination->getListFooter(); ?>
                    </td>
            </tr>
    </tfoot>
    <tbody>
            <?php if (!empty($this->items)) : ?>
                    <?php foreach ($this->items as $i => $row) :
//                            $link = JRoute::_('index.php?option=com_business&task=promotion.edit&id=' . $row->id);				?>
                            <tr>
                                    <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                                    <td>
                                            <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                                    </td>
                                    <td>			
                                            <?php echo $row->businessName; ?>  
                                    </td>
                                    <td>						
                                            <?php echo $row->username; ?>
                                    </td>
                                    <td>							
                                            <?php echo $row->point; ?>
                                    </td>
                                    
                                    <td align="center">
                                            <?php echo $row->id; ?>
                                    </td>
                            </tr>
                    <?php endforeach; ?>
            <?php endif; ?>
    </tbody>
</table>
<input type="hidden" name="task" value=""/>	
<input type="hidden" name="boxchecked" value="0"/>	
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>	
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
<?php echo JHtml::_('form.token'); ?></form>