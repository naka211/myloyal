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
$user = JFactory::getUser();
?>
<div class="pack mb200">
    <h2>Your account wasn't bussiness account , please chose and pay for it.</h2>
    <ul class="clearfix">
            <li>
                    <p><span class="f55">50</span> <span class="f35 cf7901e">kr</span> <span class="f35">/</span> <small>m&aring;ned</small></p>
                    <div class="box-highline">
                            <p>Ideal for retail chains or larger establishments</p>
                            <p>Points-based</p>
                            <p>4 push promotions per month</p>
                            <p>EPOS integrated</p>
                            <p>Advanced analytics</p>
                    </div>
                    <p>5-dages gratis pr&oslash;veversion</p>
                    <a href="<?php echo Juri::base().'index.php?option=com_business&view=paymentaccount&layout=payment&userid='.$user->id.'&package=1'?>" class="btn btnGetstart">Start</a></li>
            <li>
                    <p><span class="f55">250</span> <span class="f35 cf7901e">kr</span> <span class="f35">/</span> <small>6 m&aring;ned</small></p>
                    <div class="box-highline">
                            <p>Ideal for retail chains or larger establishments</p>
                            <p>Points-based</p>
                            <p>4 push promotions per month</p>
                            <p>EPOS integrated</p>
                            <p>Advanced analytics</p>
                    </div>
                    <p>Gratis en m&aring;ned</p>
                    <a href="<?php echo Juri::base().'index.php?option=com_business&view=paymentaccount&layout=payment&userid='.$user->id.'&package=2'?>" class="btn btnGetstart">Start</a></li>
            <li>
                    <p><span class="f55">500</span> <span class="f35 cf7901e">kr</span> <span class="f35">/</span> <small>12 m&aring;ned</small></p>
                    <div class="box-highline">
                            <p>Ideal for retail chains or larger establishments</p>
                            <p>Points-based</p>
                            <p>4 push promotions per month</p>
                            <p>EPOS integrated</p>
                            <p>Advanced analytics</p>
                    </div>
                    <p>Gratis 2 m&aring;neders</p>
                    <a href="<?php echo Juri::base().'index.php?option=com_business&view=paymentaccount&layout=payment&userid='.$user->id.'&package=3'?>" class="btn btnGetstart">Start</a></li>
    </ul>
</div>