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
<style>
    .yellow {color: #ffc100;margin-left: 15px; }
    .name2 {width:200px;}
</style>
<div id="wrapper">
        <?php require_once JPATH_SITE . "/components/com_business/views/slidebar.php";?>
        <div id="page-content-wrapper">
                <?php require_once JPATH_SITE . "/components/com_business/views/header.php";?>
            <div class="content">
                <section class="main-title">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title"><i class="fa fa-home"></i> Kunder</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container">
                        <div class="list-customer">
                            <h2 class="text-center">Mine Kunder</h2>  
                            <ul>
                                <?php foreach ($this->customers as $key=>$customer)
                                {
                                ?>
                                <li>
                                    <a href="<?php echo JRoute::_("index.php?option=com_business&view=customers&layout=view&customerid={$customer['id']}")?>">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php if($customer['facebookId']){?>
                                                    <img width="123px" src="<?php echo "http://graph.facebook.com/".$customer['facebookId']."/picture?type=large";?>" alt="">
                                                    <?php }else{?>
                                                    <img width="123px" src="<?php echo JUri::root() . "images/avatar/" . $customer['avatar'];?>" alt="">
                                                    <?php }?>
                                                <span class="name2"><?php echo $customer['firstName']?><br> 
                                                <?php echo $customer['lastName']?></span><?php if($customer['star'] == 1){?><i class="fa fa-star fa-3x yellow"></i><?php }?>
                                               
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
<!--                            <ul style="margin-top: 0;" class="pagination pull-right">
                                <?php if(isset($_GET['page']) && $_GET['page'] != 0){$page = $_GET['page'];}else{$page = 1;}?>
                                <?php for ($i=1 ; $i <= $this->page ; $i ++){?>
                                <li <?php echo ($page == $i)?"class='active'":""?>><a href="<?php echo JRoute::_("index.php?option=com_business&view=customers&page=").$i?>" ><?php echo $i ?></a></li>
                                <?php }?>
                                
                            </ul>-->
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
