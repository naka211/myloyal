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
    $(document).ready(function() {
        //datepicker .date_input
        $( ".date_input" ).datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'mm/yy',
            onClose: function(dateText, inst) { 
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            }
        });
    });
</script>
<style>
    .ui-datepicker-calendar {
        display: none;
    }
    .ui-datepicker-month{
        color:#333;
        margin-right: 2px !important;
        float:left;
    }
    .ui-datepicker-year{
        color:#333;
        float:right;
    }
</style>

<div id="wrapper">
        <?php require_once JPATH_SITE . "/components/com_business/views/slidebar.php";?>
        <div id="page-content-wrapper">
                <?php require_once JPATH_SITE . "/components/com_business/views/header.php";?>
            <div class="content">
                <section class="main-title">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-10">
                                <h2 class="title"><i class="fa fa-home"></i> My Reports</h2>
                            </div>
                            <div class="col-lg-2">
                                <a class="fr" href="index.php"><img src="media/image/logo.png" alt=""></a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="main-content">
                    <div class="container">
                        <div class="myreport mt50">
                            <h2 class="text-center">Reports period</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fra</label>
                                        <input type="text" class="form-control date_input" id="fromTime" value="<?php echo (!isset($_GET['fromTime']) || $_GET['fromTime'] == "")? date('m/Y',time()) : $_GET['fromTime'];?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group line">
                                        <label for="">Til</label>
                                        <input type="text" class="form-control date_input" id="toTime" value="<?php echo (!isset($_GET['toTime']) || $_GET['toTime'] == "")?date('m/Y',time()) : $_GET['toTime'];?>">
                                    </div>
                                </div>
                            </div>                             
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btnSubmit" onclick="loadReport()">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <hr class="cd2d2d2">
                    </div>
                    <div class="container-fluid">
                        <div class="chart">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-title">No. of cutomer checkins during period.</h1>
                                        </div>
                                        <div class="panel-body">
                                            <div id="line-chart"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php if($this->infomation['type'] == "1"){?>
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-title">No. of points awarded during period. </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div id="line-chart2"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                <?php if($this->infomation['type'] == "2"){?>
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-title">No. of stamps awarded during period. </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div id="line-chart3"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <div class="row">
                                <?php if($this->infomation['type'] == "1"){?>
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-title">No. of rewards redeemed during period. (point) </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div id="line-chart4"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                <?php if($this->infomation['type'] == "2"){?>
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-title">No. of rewards redeemed during period. (stamp) </h1>
                                        </div>
                                        <div class="panel-body">
                                            <div id="line-chart5"></div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h1 class="panel-title">Total for period.</h1>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table checkins">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h1>Checkins</h1>
                                                            <p><?php echo $this->totalCheckin?></p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
<script>
    function loadReport()
    {
        var fromTime = $('#fromTime').val();
        var toTime = $('#toTime').val();
        if(fromTime >= toTime)
        {
            alert("From Time < To Time");
        }
        else
        {
            window.location = "<?php echo JRoute::_("index.php?option=com_business&view=reports")?>" + '&fromTime=' + fromTime + "&toTime=" + toTime;
        }
    }
</script>
<script>
var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
var arrayPHP = <?php echo json_encode($this->reports);?>;
var checkinmonth = [];
$.each(arrayPHP , function (key, item) {
    checkinmonth.push({
        m : key,
        a : item.numCheckin
    });
});
var numStampsawarded = [];
$.each(arrayPHP , function (key, item) {
    numStampsawarded.push({
        m : key,
        a : item.numStampsawarded
    });
});
var numRewardsredeemedstamp = [];
$.each(arrayPHP , function (key, item) {
    numRewardsredeemedstamp.push({
        m : key,
        a : item.numRewardsredeemedstamp
    });
});
var numPointawarded = [];
$.each(arrayPHP , function (key, item) {
    numPointawarded.push({
        m : key,
        a : item.numPointawarded
    });
});
var numRewardsredeemedpoint = [];
$.each(arrayPHP , function (key, item) {
    numRewardsredeemedpoint.push({
        m : key,
        a : item.numRewardsredeemedpoint
    });
});

Morris.Line({
  element: 'line-chart',
  data: checkinmonth,
  xkey: 'm',
  ykeys: ['a'],
  labels: ['2014', '2015'],
  resize: true,
  pointFillColors:['#ffffff'],
  pointStrokeColors: ['black'],
  lineColors:['gray'],
  xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
Morris.Line({
  element: 'line-chart3',
  data: numStampsawarded,
  xkey: 'm',
  ykeys: ['a'],
  labels: ['2014', '2015'],
  resize: true,
  pointFillColors:['#ffffff'],
  pointStrokeColors: ['black'],
  lineColors:['gray'],
  xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
Morris.Line({
  element: 'line-chart5',
  data: numRewardsredeemedstamp,
  xkey: 'm',
  ykeys: ['a'],
  labels: ['2014', '2015'],
  resize: true,
  pointFillColors:['#ffffff'],
  pointStrokeColors: ['black'],
  lineColors:['gray'],
  xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
Morris.Line({
  element: 'line-chart2',
  data: numPointawarded,
  xkey: 'm',
  ykeys: ['a'],
  labels: ['2014', '2015'],
  resize: true,
  pointFillColors:['#ffffff'],
  pointStrokeColors: ['black'],
  lineColors:['gray'],
  xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
Morris.Line({
  element: 'line-chart4',
  data: numRewardsredeemedpoint,
  xkey: 'm',
  ykeys: ['a'],
  labels: ['2014', '2015'],
  resize: true,
  pointFillColors:['#ffffff'],
  pointStrokeColors: ['black'],
  lineColors:['gray'],
  xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
    var month = months[x.getMonth()];
    return month;
  },
  dateFormat: function(x) {
    var month = months[new Date(x).getMonth()];
    return month;
  },
});
</script>