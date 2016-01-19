<?php 
    $z = 2; 
    for($x = 0; $x <= 24; $x=$x+0.5)
    {
        if($z == 1)
        {
                        $y = $x - 0.5;
            $opt_value 	=  "".$y.':'. number_format(0.5 * 60,0);
                        $z = 2;
        }
        else
        {
            $opt_value 	=  "".$x.':00';
                        $z = 1;
        }
        $fullhours[] = $opt_value;
    }
?>
<p class="mt30"><strong>Opening hours:</strong></p>
<div class="form-group">
    <?php foreach($this->listDate as $key=>$date){
        $nameFrom = "jform[fromTime_".$key."]";
        $nameTo = "jform[toTime_".$key."]";
        $nameClose="jform[date_".$key."]";
        ?>
    <label for=""><?php echo $date;?></label>
    <div class="form-inline">
        <div class="row">
            <div class="col-md-1">
                <i class="fa fa-clock-o fa-2x"></i>
            </div>
            <div class="col-md-4">
                <select name="<?php echo $nameFrom;?>" id="input" class="form-control" required="required">
                    <?php 
                        foreach($fullhours as $time)
                        {
                            if($this->workingtime["$key"]['fromTime'] == $time)
                            { 
                    ?>
                    <option value="<?php echo $time;?>" selected="selected"><?php echo $time;?></option>
                    <?php
                            }
                            else
                            {
                    ?>
                    <option value="<?php echo $time;?>"><?php echo $time;?></option>        
                    <?php
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <div class="line2"></div>
                <select name="<?php echo $nameTo;?>" id="input" class="form-control" required="required">
                    <?php 
                        foreach($fullhours as $time)
                        {
                            if($this->workingtime["$key"]['toTime'] == $time)
                            { 
                    ?>
                    <option value="<?php echo $time;?>" selected="selected"><?php echo $time;?></option>
                    <?php
                            }
                            else
                            {
                    ?>
                    <option value="<?php echo $time;?>"><?php echo $time;?></option>        
                    <?php
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <div class="checkbox" style="width:100%;">
                    <select name="<?php echo $nameClose;?>" class="form-control">
                        <?php
                            foreach ($this->listState as $keyState=>$state){
                                if($this->workingtime["$key"]['close'] == $keyState)
                                { 
                        ?>
                        <option value="<?php echo $keyState;?>" selected="selected"><?php echo $state;?></option>
                        <?php
                                }
                                else
                                {
                        ?>
                        <option value="<?php echo $keyState;?>"><?php echo $state;?></option>
                        <?php
                                } 
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>
    