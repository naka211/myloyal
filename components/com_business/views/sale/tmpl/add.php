<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
?>
<script type="application/javascript">
jQuery( document ).ready(function() {
	jQuery("#saveBtn").click(function(e) {
		var firstname = jQuery("#jform_firstname").val();
		var lastname = jQuery("#jform_lastname").val();
		var address = jQuery("#jform_address").val();
		var email = jQuery("#jform_email").val();
		var phone = jQuery("#jform_phone").val();
		if(firstname == '' || lastname == '' || address == '' || email == '' || phone == ''){
			alert('Udfyld venligst alle påkrævede felter');
			return false;
		} else {
			jQuery("#submitBtn").click();
		}
	});
});
</script>
<div id="wrapper">
	<?php require_once JPATH_SITE . "/components/com_business/views/slidebaradmin.php";?>
	<div id="page-content-wrapper">
		<?php require_once JPATH_SITE . "/components/com_business/views/headeradmin.php";?>
		<div class="content">
			<section class="main-title">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<h2 class="title"><i class="fa fa-home"></i>Tilføj sælger</h2>
							<form class="frm-business-setting" id="contact-form" action="<?php echo JRoute::_('index.php?option=com_business&task=sale.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
							<div class="row" style="margin-top:10px;">
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Fornavn *</label>
										<input id="jform_firstname" name="firstName" value="" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label for="">Efternavn *</label>
										<input id="jform_lastname" name="lastName" value="" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label for="">Adresse *</label>
										<input id="jform_address" name="address" value="" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label for="">E-mail *</label>
										<input id="jform_email" name="email" value="" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label for="">Telefon *</label>
										<input id="jform_phone" name="phone" value="" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label for="">Om sælgeren</label>
										<textarea id="jform_about" name="about" class="form-control" style="height:150px;"></textarea>
									</div>
									<p>Alle felter markeret med * skal udfyldes</p>
									<input type="button" value="Gem" class="btn btn-warning" id="saveBtn" />
									<input type="submit" style="display:none" id="submitBtn" />
								</div>
							</div>
							</form>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
