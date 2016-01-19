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
if(empty($this->dealsById))
{
    JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_business&view=deals&layout=fail&businessId='.$this->infomation["id"], false));
}

?>
<script>
    $(document).ready(function() {
        //datepicker .date_input
        $( ".date_input" ).datepicker({
            "option"    :$.datepicker.regional[ "da" ]
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
                                <h2 class="title"><i class="fa fa-home"></i> Mine Tilbud</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container-fluid">
                        <div class="myloyalty-programs">
                            <h2 class="text-center">Rediger Tilbud</h2>  
                            <div class="frm-promotions">
                                <form class="frm-business-setting" id="contact-form" action="<?php echo JRoute::_('index.php?option=com_business&task=admindeals.edit'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
                                    
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Title:</label>
                                                    <input type="text" value="<?php echo $this->dealsById['title'];?>" class="form-control" name="jform[title]">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Gyldig Til:</label>
                                                    <input type="text" class="form-control date_input" name="jform[endDate]" value="<?php echo date('d/m/Y',$this->dealsById['endDate']);?>">
                                                </div>
                                            </div>

                                        </div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="">Content:</label>
                                        <textarea id="input" class="form-control" required="required" rows="3" name="jform[content]"><?php echo $this->dealsById['content'];?></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btnSubmit">Gem</button>
                                    <input type="hidden" name="option" value="com_business" />
                                    <input type="hidden" name="jform[businessid]" value="<?php echo $this->infomation["id"]?>" />
                                    <input type="hidden" name="jform[dealsid]" value="<?php echo $this->dealsById['id']?>" />
                                    <input type="hidden" name="task" value="admindeals.edit" />
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
