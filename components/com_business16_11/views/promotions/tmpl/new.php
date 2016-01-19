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
<script>
    $(document).ready(function() {
        //datepicker .date_input
        $( ".date_input" ).datepicker({
            "option"    :$.datepicker.regional[ "da" ]
        });
    });
</script>
<div id="wrapper">
        <?php require_once JPATH_SITE . "/components/com_business/views/slidebar.php";?>
        <div id="page-content-wrapper">
                <?php require_once JPATH_SITE . "/components/com_business/views/header.php";?>
            <div class="content">
                <section class="main-title">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title"><i class="fa fa-home"></i> My promotions</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container-fluid">
                        <div class="myloyalty-programs">
                            <h2 class="text-center">Edit promotion</h2>  
                            <div class="frm-promotions">
                                <form class="frm-business-setting" id="contact-form" action="<?php echo JRoute::_('index.php?option=com_business&task=promotions.newPromotion'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Type:</label>
                                                <select class="form-control" name="jform[type]" disabled>
                                                    <?php foreach($this->listTypePromotion as $key=>$promotion){
                                                    ?>
                                                    <option value="<?php echo $key;?>" 
                                                            <?php echo ($this->infomation["type"] == $key)?"selected='selected'":"";?>><?php echo $promotion;?></option>    
                                                    <?php      
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Number point (stamp):</label>
                                                <input type="text" class="form-control" name="jform[pointorstamp]">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="">Title:</label>
                                        <input type="text" value="<?php echo $this->promotionById['title'];?>" class="form-control" name="jform[title]">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Description:</label>
                                        <textarea id="input" class="form-control" required="required" rows="3" name="jform[content]"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Vnde dag:</label>
                                                <input type="text" class="form-control date_input" name="jform[startDate]" value="<?php echo date('d/m/Y',time());?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Valid until:</label>
                                                <input type="text" class="form-control date_input" name="jform[endDate]" value="<?php echo date('d/m/Y',time());?>">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <button type="submit" class="btn btnSubmit">Submit</button>
                                    <input type="hidden" name="option" value="com_business" />
                                    <input type="hidden" name="jform[promotionid]" value="<?php echo $this->promotionById['id']?>" />
                                    <input type="hidden" name="jform[businessid]" value="<?php echo $this->infomation['id']?>" />
                                    <input type="hidden" name="jform[promotion_type]" value="<?php echo $this->infomation["type"]?>" />
                                    <input type="hidden" name="task" value="promotions.newPromotion" />
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
