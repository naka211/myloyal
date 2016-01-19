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


<div id="wrapper">
        <?php require_once JPATH_SITE . "/components/com_business/views/slidebar.php";?>
        <div id="page-content-wrapper">
                <?php require_once JPATH_SITE . "/components/com_business/views/header.php";?>
            <div class="content">
                <section class="main-title">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title"><i class="fa fa-home"></i> Failed Page</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container">
                        <div class="business-setting mt50">
                            <h2 class="text-center">Your action was failed . We are sorry about that.</h2>
                            
                                <a style="width: 40% ; margin-left: 30%;margin-right: 30%;" href="<?php echo JRoute::_("index.php?option=com_business&view=customers")?>" class="btn btnCreatecampaign">Click here to go to old page.</a>
                            
                            
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
