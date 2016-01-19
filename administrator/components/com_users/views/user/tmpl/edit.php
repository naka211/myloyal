<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.getElementById('user-form')))
		{
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	};

	Joomla.twoFactorMethodChange = function(e)
	{
		var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();

		jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function(i, el) {
			if (el.id != selectedPane)
			{
				jQuery('#' + el.id).hide(0);
			}
			else
			{
				jQuery('#' + el.id).show(0);
			}
		});
	};
");

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();

//get log customer
$db = JFactory::getDbo();
$query = $db->getQuery(true);
// Create the base select statement.
$query->select('*')
->from($db->quoteName('#__log_point','a'))
->where($db->quoteName('a.customerId') . ' = ' . (int) $this->item->id)
->order($db->quoteName('a.createdAt'). " DESC");              

$db->setQuery($query);
// Assign the message
$listLogpoint = $db->loadAssocList();
///////////////
$query = $db->getQuery(true);
$query->select('*')
->from($db->quoteName('#__log_stamp','a'))
->where($db->quoteName('a.customerId') . ' = ' . (int) $this->item->id)
->order($db->quoteName('a.createdAt'). " DESC"); 
$db->setQuery($query);
$listLogstamp = $db->loadAssocList();
?>

<form action="<?php echo JRoute::_('index.php?option=com_users&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" class="form-validate form-horizontal" enctype="multipart/form-data">

	<?php echo JLayoutHelper::render('joomla.edit.item_title', $this); ?>

	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_USERS_USER_ACCOUNT_DETAILS', true)); ?>
                                <div style="width:30%;float:left;">
				<?php foreach ($this->form->getFieldset('user_details') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php if ($field->fieldname == 'password') : ?>
								<?php // Disables autocomplete ?> <input type="text" style="display:none">
							<?php endif; ?>
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
                                </div>
                                <div style="width:33%;float:left;">
                                    <h2>Log Point</h2>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                    <th width="10%">
                                                          Num  
                                                    </th>
                                                    <th width="40%">
                                                          Type  
                                                    </th>
                                                    <th width="10%">
                                                          Point 
                                                    </th>
                                                    <th width="40%">
                                                          Date   
                                                    </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                               <?php 
                                               foreach ($listLogpoint as $key=>$log)
                                               {
                                                   echo "<tr>";
                                                   echo "<td>".($key + 1)."</td>";
                                                   if($log['type'] == "1")
                                                   {
                                                       echo "<td>"."Increase Point"."</td>";
                                                   }
                                                   else
                                                   {
                                                       echo "<td>"."Decrease Point"."</td>";
                                                   }
                                                   
                                                   echo "<td>".$log['point']."</td>";
                                                   echo "<td>".date('d-m-Y',$log['createdAt'])."</td>";
                                                   echo "</tr>";
                                               }
                                               ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="width:33%;float:right;">
                                    <h2>Log Stamp</h2>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                    <th width="10%">
                                                          Num  
                                                    </th>
                                                    <th width="40%">
                                                          Type  
                                                    </th>
                                                    <th width="10%">
                                                          Stamp 
                                                    </th>
                                                    <th width="40%">
                                                          Date   
                                                    </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                               <?php 
                                               foreach ($listLogstamp as $key=>$log)
                                               {
                                                   echo "<tr>";
                                                   echo "<td>".($key + 1)."</td>";
                                                   if($log['type'] == "1")
                                                   {
                                                       echo "<td>"."Increase Stamp"."</td>";
                                                   }
                                                   else
                                                   {
                                                       echo "<td>"."Decrease Stamp"."</td>";
                                                   }
                                                   echo "<td>".$log['numStamp']."</td>";
                                                   echo "<td>".date('d-m-Y',$log['createdAt'])."</td>";
                                                   echo "</tr>";
                                               }
                                               ?>
                                        </tbody>
                                    </table>
                                </div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php if ($this->grouplist) : ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'groups', JText::_('COM_USERS_ASSIGNED_GROUPS', true)); ?>
					<?php echo $this->loadTemplate('groups'); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>

			<?php
			foreach ($fieldsets as $fieldset) :
				if ($fieldset->name == 'user_details') :
					continue;
				endif;
			?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', $fieldset->name, JText::_($fieldset->label, true)); ?>
                                <div class="col-lg-6">
				<?php foreach ($this->form->getFieldset($fieldset->name) as $field) : ?>
					<?php if ($field->hidden) : ?>
						<div class="control-group">
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php else: ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
                                </div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endforeach; ?>

		<?php if (!empty($this->tfaform) && $this->item->id): ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'twofactorauth', JText::_('COM_USERS_USER_TWO_FACTOR_AUTH', true)); ?>
		<div class="control-group">
			<div class="control-label">
				<label id="jform_twofactor_method-lbl" for="jform_twofactor_method" class="hasTooltip"
					   title="<strong><?php echo JText::_('COM_USERS_USER_FIELD_TWOFACTOR_LABEL') ?></strong><br /><?php echo JText::_('COM_USERS_USER_FIELD_TWOFACTOR_DESC') ?>">
					<?php echo JText::_('COM_USERS_USER_FIELD_TWOFACTOR_LABEL'); ?>
				</label>
			</div>
			<div class="controls">
				<?php echo JHtml::_('select.genericlist', Usershelper::getTwoFactorMethods(), 'jform[twofactor][method]', array('onchange' => 'Joomla.twoFactorMethodChange()'), 'value', 'text', $this->otpConfig->method, 'jform_twofactor_method', false) ?>
			</div>
		</div>
		<div id="com_users_twofactor_forms_container">
			<?php foreach($this->tfaform as $form): ?>
			<?php $style = $form['method'] == $this->otpConfig->method ? 'display: block' : 'display: none'; ?>
			<div id="com_users_twofactor_<?php echo $form['method'] ?>" style="<?php echo $style; ?>">
				<?php echo $form['form'] ?>
			</div>
			<?php endforeach; ?>
		</div>

		<fieldset>
			<legend>
				<?php echo JText::_('COM_USERS_USER_OTEPS') ?>
			</legend>
			<div class="alert alert-info">
				<?php echo JText::_('COM_USERS_USER_OTEPS_DESC') ?>
			</div>
			<?php if (empty($this->otpConfig->otep)): ?>
			<div class="alert alert-warning">
				<?php echo JText::_('COM_USERS_USER_OTEPS_WAIT_DESC') ?>
			</div>
			<?php else: ?>
			<?php foreach ($this->otpConfig->otep as $otep): ?>
			<span class="span3">
				<?php echo substr($otep, 0, 4) ?>-<?php echo substr($otep, 4, 4) ?>-<?php echo substr($otep, 8, 4) ?>-<?php echo substr($otep, 12, 4) ?>
			</span>
			<?php endforeach; ?>
			<div class="clearfix"></div>
			<?php endif; ?>
		</fieldset>

		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
