<?php 
    $listIconSlide = array(
            "Bar" => "/images/business/beer.png",
            "Café" => "/images/business/coffee.png",
            "Sport" => "/images/business/fitness.png",
            "Frisør" => "/images/business/hairsalon.png",
            "Indkvartering" => "/images/business/hotel.png",
            "Spisested" => "/images/business/restaurant.png",
            "Butik" => "/images/business/shop.png"
        );
?>
<div id="sidebar-wrapper">
    <div class="row" style="line-height: 60px; margin-top: 20px; margin-right: 0; margin-left: -3px;">
        <div class="col-lg-2">
            <a style="line-height: 60px; margin-left: 10px;" href="index.php"><img src="media/image/loyalist.png" width="200" alt=""></a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li style="line-height: 60px; border-top: 1px solid #4E342E; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=promotions');?>"><i class="fa fa-list-alt fa-lg"></i> Kampagner</a>
        </li>
        <li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=deals');?>"><i class="fa fa-gift fa-lg"></i> Tilbud</a>
        </li>
        <li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=customers');?>"><i class="fa fa-users fa-lg"></i> Kunder</a>
        </li>
        <li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=reports');?>"><i class="fa fa-line-chart fa-lg"></i> Statistikker</a>
        </li>
        <li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=business');?>"><i class="fa fa-cog fa-lg"></i> Indstillinger</a>
        </li>
    </ul>
<div style="position: absolute !important; bottom: 0 !important; color: #FFF !important; border-top: 1px solid #4E342E !important; min-width: 100% !important; box-sizing: border-box !important; padding: 15px !important;">
    <?php foreach ($listIconSlide as $key=>$icon){
        if(strstr($icon,$this->data['icon']) != "")
            { 
    ?>
    <img width="40" class="dd-selected-image" src="<?php echo JUri::root().$listIconSlide["$key"];?>" align="left">
    <?php
            }
    } 
    ?> 
    <?php foreach ($listIconSlide as $key=>$icon){
        if(strstr($icon,$this->infomation['icon']) != "")
            { 
    ?>
    <img width="40" class="dd-selected-image" src="<?php echo JUri::root().$listIconSlide["$key"];?>" align="left">
    <?php
            }
    } 
    ?>  
    
    &nbsp;<b><?php echo (isset($this->data['businessName']))?$this->data['businessName'] : $this->infomation['businessName']?></b><br>
    &nbsp;<?php echo $this->userinfo['firstName'] ;?> <?php echo $this->userinfo['lastName'] ;?>
    &nbsp;<?php echo $this->infomation['firstName'] ;?> <?php echo $this->infomation['lastName'] ;?>
    &nbsp;<?php echo $this->data['firstName'] ;?> <?php echo $this->data['lastName'] ;?>
</div>
</div>