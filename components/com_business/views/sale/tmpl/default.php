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
        if (confirm("Er du sikker på, at du vil slette denne sælger?") == true) {
            window.location = "<?php echo JRoute::_("index.php?option=com_business&task=sale.delete")?>" + "&id=" + id;
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
                                <h2 class="title"><i class="fa fa-home"></i>Vælg sælger</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="select_business">
                                        <!--<form action="<?php echo JRoute::_('index.php?option=com_business&task=sale.search'); ?>" method="POST" class="form-inline frm_select_business" role="form">                                    
                                            <div class="form-group" style="width: 30%;">
                                                <input type="text" class="form-control" style="width: 100%;" id="" name='jform[businessName]' placeholder="Indtast forretningsnavn eller CVR-nr....">
                                            </div>
                                            <button type="submit" class="btn btn-warning">Søg</button>
                                            <a href="<?php echo JRoute::_('index.php?option=com_business&task=adminselectbusiness'); ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                                        </form>-->
                                        <a href="<?php echo JRoute::_("index.php?option=com_business&view=sale&layout=add");?>" class="btn btn-warning" style="margin:10px 0">Tilføj sælger</a>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-lg-2" ><strong>Sælgerens ID</strong></th>
                                                    <th class="col-lg-2" style="text-align:center;"><strong>Navn</strong></th>
                                                    <th class="col-lg-4" style="text-align:center;"><strong>E-mail</strong></th>
                                                    <th class="col-lg-2" style="text-align:center;"><strong>Telefon</strong></th>
                                                    <th class="col-lg-2" style="text-align:center;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($this->listSale as $sale){?>
                                                <tr>
                                                    <td>
                                                        <h3><?php echo $sale['newId'];?></h3>
                                                    </td>
                                                    <td class="text-center"><?php echo $sale['firstName'].' '.$sale['lastName'];?></td>
                                                    <td class="text-center"><?php echo $sale['email'];?></td>
                                                    <td class="text-center"><?php echo $sale['phone'];?></td>
                                                    <td>
                                                        <a href="<?php echo JRoute::_("index.php?option=com_business&view=sale&layout=edit&id=".$sale['id']);?>" class="btn btn-warning">Vælg</a>
                                                        <a onclick="deleteFunction(<?php echo $sale['id']?>)" class="btn btn-warning">Slet</a>
                                                    </td>
                                                    
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="row">
                                <div class="col-lg-12">
                                    <ul class="pagination pull-right">
                                        <?php if(isset($_GET['page']) && $_GET['page'] != 0){$page = $_GET['page'];}else{$page = 1;}?>
                                        <?php for ($i=1 ; $i <= $this->page ; $i ++){?>
                                        <li <?php echo ($page == $i)?"class='active'":""?>><a href="<?php echo JRoute::_("index.php?option=com_business&view=adminselectbusiness&businessName=".$businessName."&filter=".$filter."&order=".$order1."&page=").$i?>" ><?php echo $i ?></a></li>
                                        <?php }?>
                                    </ul>
                                </div>
                            </div>-->
                        </div>
                </section>
            </div>
        </div>
</div>

