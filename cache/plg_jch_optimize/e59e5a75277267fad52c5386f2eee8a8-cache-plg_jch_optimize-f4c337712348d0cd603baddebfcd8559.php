<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";s:0:"";s:6:"result";s:11185:"@import url("https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic");
body {font-family: 'Roboto', sans-serif; color: #333; background: #f5f5f5;}
h2 {font-family: "Helvetica Neue", Helvetica,Arial,sans-serif;font-weight: normal;}

/* Toggle Styles */

#wrapper {
    padding-left: 0;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

#wrapper.toggled {
    padding-left: 250px;
}

#sidebar-wrapper {
    z-index: 1000;
    position: fixed;
    left: 250px;
    width: 0;
    height: 100%;
    margin-left: -250px;
    overflow-y: auto;
    background: #3E2723;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

#wrapper.toggled #sidebar-wrapper {
    width: 250px;
}

#page-content-wrapper {
    width: 100%;
    position: absolute;
}

#wrapper.toggled #page-content-wrapper {
    position: absolute;
    margin-right: -250px;
}

/* Sidebar Styles */

.sidebar-nav {
    position: absolute;
    top: 0;
    width: 250px;
    margin: 0;
    padding: 0;
    list-style: none;
    padding-top: 110px;
}

.sidebar-nav li {
    text-indent: 10px;
    line-height: 40px;
}

.sidebar-nav li a {
    display: block;
    text-decoration: none;
    color: #fff;
    font-family: "Helvetica Neue", Helvetica,Arial,sans-serif;
    font-weight: normal;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}

.sidebar-nav li a .fa {
    display: inline-block;
    width: 40px;
}
.sidebar-nav li a:hover {
    text-decoration: none;
    color: #fff;
    background: #594a44;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}

.sidebar-nav li a:active,
.sidebar-nav li a:focus {
    text-decoration: none;
}

.fl {float: left;} .fr {float: right;} .text-right {text-align: right;} .text-left {text-align: left;} .mt50 {margin-top: 50px;}

.name {
    float: left;
    margin-top: 30px;
    margin-left: 75px;
}

.header {
    padding: 25px;
    background: #fff;
}
.content {
    background: #f5f5f5;
    padding: 25px;
}
.title {
    font-size: 16px;
    margin: 0;
    color: #424242;
    border-bottom: 1px solid #ccc;
    padding-bottom: 15px;
}

.title .fa {
    margin-right: 20px;
}

.list-customer {
    background: #fff;
    border: 1px solid #e2e2e2;
    margin: 30px 0;
}
.list-customer h2 {
    font-size: 14px;
    margin: 0;
    padding: 20px;
    background: #ede6e4;
}
.list-customer ul {
    list-style: none;
    padding-left: 0;
}
.list-customer ul li {
    font-size: 16px;
}
.list-customer ul li a {
    padding: 15px;
    color: #333;
    display: block;
    border-bottom: 1px solid #e2e2e2;
}
.list-customer ul li:hover a {
    background: #f5f5f5;
}
.list-customer ul li a .name2 {
    display: inline-block;
    margin-top: 20px;
}
.img-people {
    float: left;
    margin-right: 15px;
}
.img-people img {
    border: 1px solid #8c6e64;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    padding: 3px;
}
.checkin {
    color: #ff9800;
    font-size: 12.26px;
    margin-top: 35px;
}
.btnLogout {
    margin-top: 10px;
    color: #424242;
    float: right;
    border: 1px solid #d3d3d3;
    border-radius: 0;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.btnLogout .text {
    font-size: 16px;
    margin-left: 5px;
}
.btnLogout:hover {
    background: #f5f5f5;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.myloyalty-programs h2 {
    font-size: 25px;
    text-align: center;
    margin-bottom: 15px;
    margin-top: 50px;
}

.myloyalty-programs .table {
    border: 1px solid #e2e2e2;
}
.myloyalty-programs .table thead tr th {
    background: #ede6e4;
    padding: 15px 30px;
    font-weight: normal;
    border-bottom: 1px solid #ddd;
}

.myloyalty-programs .table tr td {
    border-bottom: 1px solid #e2e2e2;
    vertical-align: middle;
    padding: 15px 30px;
    background: #fff;
}
.myloyalty-programs .table tr td h3 {
    font-size: 14px;
    margin-top: 0;
}
.myloyalty-programs .table tr td p
{
    color: #888;
}
.myloyalty-programs .table tr td:first-child {
    width: 60%;
}
.btnEdit {
    border-radius: 0;
    -webkit-border-radius: 0;
    background: #fff;
    padding: 8px 20px;
    border: 1px solid #e2e2e2;
    color: #424242;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.btnEdit:hover {
    background: #f5f5f5;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.btnCreatecampaign {
    color: #fff;
    background: #f7901e;
    padding: 8px 60px;
    text-transform: uppercase;
    margin: 30px 0;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.btnCreatecampaign:hover {
    background: #c36e10;
    color: #fff;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.btnSubmit {
    color: #fff;
    background: #f7901e;
    text-transform: uppercase;
    padding: 8px 20px;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.frm-createcampaign, .frm-promotions, .business-setting {
    padding: 40px;
    background: #fff;
    border: 1px solid #e2e2e2;
}
.frm-createcampaign label, .frm-promotions label {
    font-weight: normal;
}
.frm-createcampaign .form-control {
    height: 45px;
}
.frm-promotions .form-control {
    height: 45px;
}
.frm-promotions textarea {
    height: 165px !important;
}
.btnCoffee {
    background: #f3f3f3;
    border: 1px solid #ccc;
    padding: 8px 15px 8px 15px;
    text-align: left;
    width: 320px;
}
.btnCoffee .caret {
    float: right;
    margin-top: 20px;
}
.w320 {width: 320px;}
.dropdown-menu {
    min-width: 320px;
}

.frm-business-setting .form-inline {
    margin-bottom: 10px;
}
.frm-business-setting label {
    font-weight: inherit;
}
.frm-business-setting .fa {
    color: #999999;
    margin-right: 5px;
}
.frm-business-setting .form-control {
    height: 47px;
}
.mt30 {margin-top: 30px;}
.frm-business-setting .form-inline .form-control {
    width: 100%;
}
.frm-business-setting .form-inline label {
    margin-top: 10px;
}
.frm-business-setting .form-inline select {
    margin-bottom: 10px;
}
.frm-business-setting .form-inline .fa {
    margin-left: 10px;
    margin-top: 8px;
}
.map img {
    width: 100%;
}
.business-setting {

}
.frmPeriod label {
    font-weight: inherit;
}
.frmPeriod .form-control {
    height: 45px;
    background: url(/myloyal/templates/beez3/img/i-calendar.png) no-repeat 97% 50%;
}
.myreport {
    width: 745px;
    padding: 40px;
    background: #fff;
    border: 1px solid #e2e2e2;
    margin: 0 auto;
    margin-top: 50px;
}
.cd2d2d2 {border-color: #d2d2d2; margin: 30px 0;}
.panel-default>.panel-heading {
    background-image: none;
    background: #ede6e4;
}
.panel {
    box-shadow: none;
    border-radius: 0;
}
.panel-title {
    font-size: 21px;
}
.panel-heading {
    padding: 15px 20px;
}
.panel-body {
    padding: 20px;
    color: #808080;
    min-height: 390px;
}

.menu-toggle {
    display: none;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}

.date_input {
    cursor: pointer;
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.line::before {
    font-family: 'FontAwesome';
    font-size: 0.5em;
    content: "\f068";
    position: relative;
    top: 38px;
    left: -18px;
}
.line2::before {
    font-family: 'FontAwesome';
    font-size: 0.5em;
    content: "\f068";
    position: absolute;
    top: 18px;
    left: -4px;
}
.date_input:hover {
    opacity: 0.8;
    filter: alpha(opacity=80);
    -webkit-transition: all 400ms linear;
    -moz-transition: all 400ms linear;
    -ms-transition: all 400ms linear;
    -o-transition: all 400ms linear;
    transition: all 400ms linear;
}
.checkins {
    min-height: 330px;
}
.checkins tr td {
    vertical-align: middle !important;
    border-top: none !important;
    text-align: center;
    display: table-cell;
}
.checkins tr td h1 {
    text-transform: uppercase;
    font-size: 60px;
}
.checkins tr td p {
    font-size: 40px;
}



@media (max-width: 768px) {
    .myreport {
        width: 100%;
    }
    .menu-toggle {
        display: block;
        -webkit-transition: all 400ms linear;
        -moz-transition: all 400ms linear;
        -ms-transition: all 400ms linear;
        -o-transition: all 400ms linear;
        transition: all 400ms linear;
    }
}
@media(min-width:768px) {
    #wrapper {
        padding-left: 250px;
    }

    #wrapper.toggled {
        padding-left: 0;
    }

    #sidebar-wrapper {
        width: 250px;
    }

    #wrapper.toggled #sidebar-wrapper {
        width: 0;
    }

    #page-content-wrapper {
        position: relative;
    }

    #wrapper.toggled #page-content-wrapper {
        position: relative;
        margin-right: 0;
    }
}
.select_business .table {
    border: 1px solid #e2e2e2;
}
.select_business .table thead tr th {
    background: #ede6e4;
    padding: 15px 30px;
    font-weight: normal;
    border-bottom: 1px solid #ddd;
}
.select_business .table tr td h3 {
    margin: 0;
    font-size: 14px;
}
.select_business .table tr td {
    border-bottom: 1px solid #e2e2e2;
    vertical-align: middle;
    padding: 15px 30px;
    background: #fff;
}
.frm_select_business {
    margin: 20px 0;

}";}