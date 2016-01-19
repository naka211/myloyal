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
    $businessId = 0;
    if(isset($_GET['businessId']) && $_GET['businessId'] != "")
    {
        $businessId = $_GET['businessId'];
    }
	if($this->userinfo['timeExpired']){
		$expired = $this->userinfo['timeExpired'];
	}
	if($this->infomation['timeExpired']){
		$expired = $this->infomation['timeExpired'];
	}
	if($this->data['timeExpired']){
		$expired = $this->data['timeExpired'];
	}
?>
<div id="sidebar-wrapper">
    <div class="row" style="line-height: 60px; margin-top: 20px; margin-right: 0; margin-left: -3px;">
        <div class="col-lg-2">
            <a style="line-height: 60px; margin-left: 10px;" href="index.php"><img src="media/image/loyalist.png" width="200" alt=""></a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li style="line-height: 60px; border-top: 1px solid #4E342E; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=adminselectbusiness');?>"><i class="fa fa-list-alt fa-lg"></i> Se alle forretninger</a>
        </li>
        <li style="line-height: 60px; border-top: 1px solid #4E342E; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=sale');?>"><i class="fa fa-list-alt fa-lg"></i> Sælgere</a>
        </li>
        <?php if($businessId > 0){?>
        <li style="line-height: 60px; border-top: 1px solid #4E342E; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=adminpromotions&businessId').$businessId;?>"><i class="fa fa-list-alt fa-lg"></i> Kampagner</a>
        </li>
        <li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=admindeals&businessId').$businessId;?>"><i class="fa fa-gift fa-lg"></i> Tilbud</a>
        </li>
        <li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=admincustomers&businessId').$businessId;?>"><i class="fa fa-users fa-lg"></i> Kunder</a>
        </li>
        <li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=adminreports&businessId').$businessId;?>"><i class="fa fa-line-chart fa-lg"></i> Statistikker</a>
        </li>
        <li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <a style="line-height: 60px; height: 60px;" href="<?php echo JRoute::_('index.php?option=com_business&view=adminbusiness&businessId').$businessId;?>"><i class="fa fa-cog fa-lg"></i> Indstillinger</a>
        </li>
        <?php }?>
        <!--<li style="line-height: 60px; border-bottom: 1px solid #4E342E; box-sizing: border-box; font-size: 15px;">
            <span style="color:#fff;">Brugerstatus: <?php echo date("d/m/Y", $expired)?></span>
        </li>-->
    </ul>

<div style="position: absolute !important; bottom: 0 !important; color: #FFF !important; border-top: 1px solid #4E342E !important; min-width: 100% !important; box-sizing: border-box !important; padding: 15px !important;">
    <?php //echo (isset($this->userinfo['newId']))? "</br> <b>Forretning-ID : ".$this->userinfo['newId']."</b>" : "" ;?>
    <?php //echo (isset($this->infomation['newId']))? "</br> <b>Forretning-ID : ".$this->infomation['newId']."</b>" : "" ;?>
    <?php //echo (isset($this->data['newId']))? "</br> <b>Forretning-ID : ".$this->data['newId']."</b>" : "" ;?>
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