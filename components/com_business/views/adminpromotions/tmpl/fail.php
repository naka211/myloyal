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
if(isset($_GET['businessId']) && $_GET['businessId'] != "")
{
    $businessId = $_GET['businessId'];
}
?>


<div id="wrapper">
        <?php require_once JPATH_SITE . "/components/com_business/views/slidebaradmin.php";?>
        <div id="page-content-wrapper">
                <?php require_once JPATH_SITE . "/components/com_business/views/headeradmin.php";?>
            <div class="content">
                <section class="main-title">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title"><i class="fa fa-home"></i> Begrænsning</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container">
                        <div class="business-setting mt50">
                            <h2 class="text-center">Du kan desværre ikke oprette mere end 4 kampagner.</h2>
                            
                                <a style="width: 40% ; margin-left: 30%;margin-right: 30%;" href="<?php echo JRoute::_("index.php?option=com_business&view=adminpromotions&businessId=").$businessId?>" class="btn btnCreatecampaign">Klik her for at gå tilbage</a>
                            
                            
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
