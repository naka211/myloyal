<style>
    .menu-top {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 999999;
        color: #fff;
        display: none;
        background: #2d2824;
        width: 100%;
        border-left: 1px solid #fff;
    }
    .btnMenu {
        color: #fff !important;
    }
    @media (max-width: 768px) {
        .header {
            padding-top: 60px;
        }
        .menu-top {
            display: block;
        }
        .content {
            padding: 25px 0;
        }
    }
</style>
<header class="header">
    <div class="menu-top">
        <a href="#menu-toggle" class="btn btnMenu" id="menu-toggle"><i class="fa fa-bars fa-2x"></i></a>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                &nbsp;
            </div>
            <div class="col-md-6">
                <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post" class="">
                        <div class="control-group">
                                <div class="controls">
                                        <button type="submit" class="btn btnLogout" style="background: #3E2723; color: #FFF;"><i class="fa fa-arrow-circle-right fa-lg" style="vertical-align: -9%;"></i> <span class="text">Log ud</span></button>
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
<script>
$("#system-message-container").delay(5000).fadeOut(300);
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});
</script>
