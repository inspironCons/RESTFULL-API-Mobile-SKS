<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=title()?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=get_template_directory(dirname(__FILE__),'')?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=get_template_directory(dirname(__FILE__),'')?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=get_template_directory(dirname(__FILE__),'')?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- sweetalert 2 -->
  <link rel="stylesheet" href="<?=get_template_directory(dirname(__FILE__),'')?>plugins/sweetalert2/sweetalert2.min.css">
  <!-- chart js -->
  <link rel="stylesheet" href="<?=get_template_directory(dirname(__FILE__),'')?>plugins/ChartJs/css/Chart.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?=get_template_directory(dirname(__FILE__),'')?>dist/css/AdminLTE.min.css">
  <!-- data table -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?=get_template_directory(dirname(__FILE__),'')?>dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="<?=get_template_directory(dirname(__FILE__),'')?>https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="<?=get_template_directory(dirname(__FILE__),'')?>https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="<?=get_template_directory(dirname(__FILE__),'')?>index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="<?=get_template_directory(dirname(__FILE__),'')?>#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Fakultas</li>
        <li class="<?=is_active_page_print('Fakultas','2','active');?> treeview">
          <a href="#">
            <i class="fa fa-bars"></i>
            <span>Admin Fakultas</span>
            <span class="pull-right-container">
              <!-- <span class="label label-primary pull-right">4</span> -->
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?=is_active_page_print('Dashboard','3','active');?>"><a href="<?=set_url('Fakultas/Dashboard')?>"><i class="fa fa-line-chart"></i> Grapik</a></li>
            <li><a href="<?=is_active_page_print('Histori','3','active');?>"><a href="<?=set_url('Fakultas/Histori')?>"><i class="fa fa-history"></i> Histori Transaksi</a></li>
          </ul>
        </li>
        <li class="header">Super Admin</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-bars"></i>
            <span>User</span>
            <span class="pull-right-container">
              <!-- <span class="label label-primary pull-right">4</span> -->
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-line-chart"></i> grapik </a></li>
            <li><a href="#"><i class="fa fa-user"></i> user</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-bars"></i>
            <span>Finansial</span>
            <span class="pull-right-container">
              <!-- <span class="label label-primary pull-right">4</span> -->
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-line-chart"></i> grapik </a></li>
            <li><a href="#"><i class="fa fa-usd"></i> Finansial</a></li>
          </ul>
        </li>
        <li class="<?=is_active_page_print('Voucher','2','active');?> treeview">
          <a href="#">
            <i class="fa fa-bars"></i>
            <span>Voucher</span>
            <span class="pull-right-container">
              <!-- <span class="label label-primary pull-right">4</span> -->
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?=set_url('Voucher#tambah')?>"><i class="fa fa-plus"></i> Buat Voucher</a></li>
            <li class="<?=is_active_page_print('Voucher','2','active');?>"><a href="<?=set_url('Voucher')?>"><i class="fa fa-credit-card"></i> Voucher</a></li>
          </ul>
        </li>
        <li class="header">Setting</li>
        <li>
          <a href="#">
            <i class="fa fa-sign-out"></i>
            <span>Log out</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
