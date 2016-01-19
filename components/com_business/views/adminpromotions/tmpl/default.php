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
<script>
    function deleteFunction(id)
    {
        if (confirm("Er du sikker på at du vil slette kampagnen?") == true) {
            window.location = "<?php echo JRoute::_("index.php?option=com_business&task=adminpromotions.delete&businessId=".$businessId."&id=")?>" + id;
        } else {
            
        }
    }
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
                                <h2 class="title"><i class="fa fa-list-alt"></i> Kampagner</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container-fluid">
                        <div class="myloyalty-programs">
                            <h2 class="text-center">Opret og rediger dine kampagner</h2>  
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><strong>Kampagne beskrivelse</strong></th>
                                        <th class="text-center"><strong>Gyldig Fra</strong></th>
                                        <th class="text-center"><strong>Gyldig Til</strong></th>
                                        <th class="text-center"><strong>Slet</strong></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($this->promotions as $key => $promotion) {
                                    ?>
                                    <tr>
                                        <td>
                                            <h3><?php echo $promotion['title']?></h3>
                                            <p><?php echo $promotion['content']?></p>
                                        </td>
                                        <td class="text-center"><?php echo date('d/m/Y',$promotion['startDate']);?></td>
                                        <td class="text-center"><?php echo date('d/m/Y',$promotion['endDate']);?></td>
                                        <td class="text-center"><a style="cursor: pointer;" onclick="deleteFunction(<?php echo $promotion['id']?>)" ><i class="fa fa-trash-o"></i></a></td>
                                        <!--<td class="text-center"><a onclick="deleteFunction(<?php echo $promotion['id']?>)" href="<?php echo JRoute::_("index.php?option=com_business&task=adminpromotions.delete&businessId=".$businessId."&id=").$promotion['id']?>"><i class="fa fa-trash-o"></i></a></td>-->
                                        <td><a href="<?php echo JRoute::_("index.php?option=com_business&view=adminpromotions&layout=edit&businessId=".$businessId."&id=").$promotion['id']?>" class="btn btnEdit">Rediger</a></td>
                                    </tr>
                                    <?php
                                    }?>

                                    <tr>
                                        <td colspan="5" class="text-center">
<!--                                                    <button class="btn btnCreatecampaign" type="submit">Create promotion</button>
                                            <input type="hidden" name="option" value="com_business" />
                                            <input type="hidden" name="task" value="business.save" />-->
                                            <?php if($this->numpromotion >= 4){?>
                                            <b style="color: red;">Du må max. have 4 kampagner. Slet en kampagne for at kunne oprette en ny.</b>
                                            <?php }else{?>
                                            <a href="<?php echo JRoute::_("index.php?option=com_business&view=adminpromotions&layout=new&businessId=".$businessId)?>" class="btn btnCreatecampaign">OPRET KAMPAGNE</a>
                                            <?php }?>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
