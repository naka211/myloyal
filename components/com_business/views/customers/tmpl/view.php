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
if(empty($this->customersById))
{
    JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_business&view=customers&layout=fail', false));
}
?>

<div id="wrapper">
        <?php require_once JPATH_SITE . "/components/com_business/views/slidebar.php";?>
        <div id="page-content-wrapper">
                <?php require_once JPATH_SITE . "/components/com_business/views/header.php";?>
            <div class="content">
                <section class="main-title">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title"><i class="fa fa-home"></i>Vis Kunde</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container">
                        <div class="list-customer">
                            <h2 class="text-center">Vis Kunde</h2>  
                            <div class="frm-promotions">
                                    <!--<div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="img-people">
                                                    <img src="<?php echo JUri::root() . "images/avatar/" . $this->customersById['avatar'];?>" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Fulde Navn</label>
                                                <input readonly type="text" value="<?php echo $this->customersById['name'];?>" class="form-control" name="">
                                            </div>
<!--                                            <div class="col-md-6">
                                                <label for="">E-Mail Adresse</label>
                                                <input readonly="readonly" type="text" value="<?php echo $this->customersById['email'];?>" class="form-control" name="">
                                            </div>-->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Fornavn</label>
                                                <input readonly type="text" value="<?php echo $this->customersById['firstName'];?>" class="form-control" name="">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Efternavn</label>
                                                <input readonly type="text" value="<?php echo $this->customersById['lastName'];?>" class="form-control" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Oprettelses Dato</label>
                                                <input readonly type="text" value="<?php echo $this->customersById['registerDate'];?>" class="form-control" name="">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Sidst Besøgt Dato</label>
                                                <input readonly type="text" value="<?php echo $this->customersById['lastvisitDate'];?>" class="form-control" name="">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
