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
$businessName = '';
$order = '';
$order1 = '';
$filter = '';
if(isset($_GET['order']) && $_GET['order'] != "")
{
    $order = ($_GET['order'] == 'ASC')?"DESC":"ASC";
    $order1 = $_GET['order'];
}
else
{
    $order = 'ASC';
}
if(isset($_GET['filter']) && $_GET['filter'] != "")
{
    $filter = $_GET['filter'];
}
if(isset($_GET['businessName']) && $_GET['businessName'] != "")
{
    $businessName = $_GET['businessName'];
}
?>
<script>
    function deleteFunction(id)
    {
        if (confirm("Er du sikker på at du vil slette business?") == true) {
            window.location = "<?php echo JRoute::_("index.php?option=com_business&task=adminselectbusiness.delete")?>" + "&businessId=" + id;
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
                                <h2 class="title"><i class="fa fa-home"></i>Select Business</h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="select_business">
                                        <form action="<?php echo JRoute::_('index.php?option=com_business&task=adminselectbusiness.search'); ?>" method="POST" class="form-inline frm_select_business" role="form">                                    
                                            <div class="form-group" style="width: 30%;">
                                                <input type="text" class="form-control" style="width: 100%;" id="" name='jform[businessName]' placeholder="Indtast forretningsnavn eller CVR-nr....">
                                            </div>
                                            <button type="submit" class="btn btn-warning">Søg</button>
                                            <a href="<?php echo JRoute::_('index.php?option=com_business&task=adminselectbusiness'); ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                                        </form>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-lg-2" ><strong><a href="<?php echo JRoute::_("index.php?option=com_business&task=adminbusiness&businessName=".$businessName."&filter=businessName&order=".$order);?>">Business Name</a></strong></th>
                                                    <th class="col-lg-2" style="text-align:center;"><strong><a href="<?php echo JRoute::_("index.php?option=com_business&task=adminbusiness&businessName=".$businessName."&filter=cvrNumber&order=".$order);?>">CVR-nr</a></strong></th>
                                                    <th class="col-lg-4" style="text-align:center;"><strong><a href="<?php echo JRoute::_("index.php?option=com_business&task=adminbusiness&businessName=".$businessName."&filter=address&order=".$order);?>">Address</a></strong></th>
                                                    <th class="col-lg-2" style="text-align:center;"><strong><a href="<?php echo JRoute::_("index.php?option=com_business&task=adminbusiness&businessName=".$businessName."&filter=countCheckin&order=".$order);?>">Check in</a></strong></th>
                                                    <th class="col-lg-2" style="text-align:center;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($this->listbusiness as $business){?>
                                                <tr>
                                                    <td>
                                                        <h3><?php echo $business['businessName'];?></h3>
                                                    </td>
                                                    <td class="text-center"><?php echo $business['cvrNumber'];?></td>
                                                    <td class="text-center"><?php echo $business['address'];?></td>
                                                    <td class="text-center"><?php echo $business['countCheckin'];?></td>
                                                    <td>
                                                        <a href="<?php echo JRoute::_("index.php?option=com_business&view=adminbusiness&businessId=".$business['id']);?>" class="btn btn-warning">Select</a>
                                                        <a onclick="deleteFunction(<?php echo $business['id']?>)" class="btn btn-warning">Delete</a>
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

