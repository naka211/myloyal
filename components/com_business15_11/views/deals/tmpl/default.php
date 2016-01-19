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
    function deleteFunction(id)
    {
        if (confirm("Do you want delete this deals ?") == true) {
            window.location = "<?php echo JRoute::_("index.php?option=com_business&task=deals.delete&id=")?>" + id;
        } else {
            
        }
    }
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
                                <h2 class="title"><i class="fa fa-home"></i> My deals</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container-fluid">
                        <div class="myloyalty-programs">
                            <h2 class="text-center">Create and edit my deals</h2>  
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><strong>My deals descripton</strong></th>
                                        <th class="text-center"><strong>Vnde dag</strong></th>
                                        <th class="text-center"><strong>Delete</strong></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($this->deals as $key => $deals) {
                                    ?>
                                    <tr>
                                        <td>
                                            <h3><?php echo $deals['title']?></h3>
                                            <p><?php echo $deals['content']?></p>
                                        </td>
                                        <td class="text-center"><?php echo date('d/m/Y',$deals['endDate']);?></td>
                                        <td class="text-center"><a style="cursor: pointer;" onclick="deleteFunction(<?php echo $deals['id']?>)" ><i class="fa fa-trash-o"></i></a></td>
                                        <!--<td class="text-center"><a onclick="deleteFunction(<?php echo $promotion['id']?>)" href="<?php echo JRoute::_("index.php?option=com_business&task=deals.delete&id=").$promotion['id']?>"><i class="fa fa-trash-o"></i></a></td>-->
                                        <td><a href="<?php echo JRoute::_("index.php?option=com_business&view=deals&layout=edit&id=").$deals['id']?>" class="btn btnEdit">Edit</a></td>
                                    </tr>
                                    <?php
                                    }?>

                                    <tr>
                                        <td colspan="5" class="text-center">
<!--                                                    <button class="btn btnCreatecampaign" type="submit">Create promotion</button>
                                            <input type="hidden" name="option" value="com_business" />
                                            <input type="hidden" name="task" value="business.save" />-->
                                            <a href="<?php echo JRoute::_("index.php?option=com_business&view=deals&layout=new")?>" class="btn btnCreatecampaign">Create deals</a>
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
