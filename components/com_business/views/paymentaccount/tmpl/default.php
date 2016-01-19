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


<div id="page">
    <section class="main">
            <div class="container">
                    <div class="pack mb200">
                            <h2 class="text-center">Kom igang med MyLoyal i dag</h2>
                            <div class="check">
                                    <div class="slide-checkbox"><label for="test-check"> <input name="test-check" type="checkbox" id="test-check" /> <span class="dot"></span> <span class="value-text text-1">M&aring;nedligt</span> <span class="value-text text-2">&Aring;rligt</span> </label></div>
                            </div>
                            <div class="w-pack-item">
                                    <div id="boxMonthly" class="boxMonthly">
                                            <h3 class="text-center">Betal m&aring;nedligt, opsig n&aring;r som helst</h3>
                                            <ul class="clearfix">
                                                    <li>
                                                            <p><span class="f55">349 </span><span class="f35 cf7901e"> kr</span> <span class="f35">/</span> <small>m&aring;ned*</small></p>
                                                            <div class="box-highline">
                                                                    <p>2 notifikationstilbud om m&aring;neden</p>
                                                                    <p>4 kampagner</p>
                                                                    <p>Stempel- eller points baseret loyalitetsprogram</p>
                                                                    <p>Ingen binding eller startomkostninger.</p>
                                                                    <p class="f16">Gratis pr&oslash;vem&aring;ned</p>
                                                            </div>
                                                            <a href="index.php?option=com_users&amp;view=registration&amp;package=1" id="buttonMonth" class="btn btnGetstart" style="display: block;">kom gratis igang</a></li>
                                            </ul>
                                    </div>
                                    <div id="boxYearly" class="boxYearly">
                                            <h3 class="text-center">Betal &aring;rligt og f&aring; 2 m&aring;neder gratis</h3>
                                            <ul class="clearfix">
                                                    <li>
                                                            <p><span class="f55">3490 </span><span class="f35 cf7901e"> kr</span> <span class="f35">/</span> <small>&aring;r*</small></p>
                                                            <div class="box-highline">
                                                                    <p>2 notifikationstilbud om m&aring;neden</p>
                                                                    <p>4 kampagner</p>
                                                                    <p>Stempel- eller points baseret loyalitetsprogram</p>
                                                                    <p>Ingen binding eller startomkostninger.</p>
                                                                    <p class="f16">Gratis pr&oslash;vem&aring;ned</p>
                                                            </div>
                                                            <a href="index.php?option=com_users&amp;view=registration&amp;package=2" id="buttonYear" class="btn btnGetstart" style="display: none;">kom igang</a></li>
                                            </ul>
                                    </div>
                            </div>
                            <div class="row" style="margin-bottom: 33px;">
                                    <div class="col-lg-12 text-center">
                                            <p>*Pr. forretning - Alle priser er i DKK ekskl. moms</p>
                                    </div>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-lg-12 text-center">
                                    <p>Vi modtager f&oslash;lgende betalingskort:</p>
                                    <img src="images/build/card.png" alt="card" style="margin: 0 auto;" /></div>
                    </div>
            </div>
    </section>
</div>
