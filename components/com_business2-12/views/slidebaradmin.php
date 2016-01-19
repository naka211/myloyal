<?php 
    $jinput = JFactory::getApplication()->input;
    $businessId     = $jinput->get('businessid', 0, 'INT');
    if($businessId == 0)
    {
        
    }   
?>
<style></style>

<div id="sidebar-wrapper">
    <div class="row">
        <div>
            <select name="" id="input" class="form-control select-custom" required="required">
                <?php 
                    foreach ($this->listbusiness as $key=>$business)
                    {
                ?>
                <option value="<?php echo $business['id']?>" <?php echo ($businessId == $business['id']) ? "selected='selected'" : "";?>>
                    <?php echo $business['businessName']?>
                </option>
                <?php
                    }
                ?>
            </select>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li>
            <a href="<?php echo JRoute::_('');?>"><i class="fa fa-home fa-lg"></i> Back to home</a>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_business&view=business');?>"><i class="fa fa-map-marker fa-lg"></i> Business setting</a>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_business&view=promotions');?>"><i class="fa fa-gift fa-lg"></i> My promotions</a>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_business&view=customers');?>"><i class="fa fa-users fa-lg"></i> My customers</a>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_business&view=reports');?>"><i class="fa fa-line-chart fa-lg"></i> My Reports</a>
        </li>
    </ul>
</div>