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
                                <h2 class="title"><i class="fa fa-home"></i> My customers</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container">
                        <div class="list-customer">
                            <h2 class="text-center">My customers</h2>  
                            <ul>
                                <?php foreach ($this->customers as $key=>$customer)
                                {
                                ?>
                                <li>
                                    <a href="#">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="img-people">
                                                    <img src="<?php echo JUri::root() . "images/build/img01.jpg";?>" alt="">
                                                </div>
                                                <span class="name2"><?php echo $customer['firstName']?><br>
                                                <?php echo $customer['lastName']?></span>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="pull-right checkin"><?php echo "Check-in " . $customer["stringtime"]?></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
