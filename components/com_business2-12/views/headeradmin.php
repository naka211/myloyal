<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <a class="fl" href="index.php"><img src="media/image/logo.png" alt=""></a>
                <p class="name">Firmanavn kaffe</p>
            </div>
            <div class="col-md-6">
                <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post" class="">
                        <div class="control-group">
                                <div class="controls">
                                        <button type="submit" class="btn btnLogout"><i class="fa fa-arrow-circle-right fa-lg"></i> <span class="text">Log ud</span></button>
                                </div>
                        </div>
                    <input type="hidden" name="return" value="<?php echo base64_encode(JUri::base());?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </form>
                <!-- <a id="menu-toggle" class="menu-toggle"><i class="fa fa-bars fa-2x"></i></a> -->
            </div>
        </div>
    </div>
</header>