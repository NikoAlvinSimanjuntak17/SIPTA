<style type="text/css">
   .widget-user-header {
      padding-left: 20px !important;
   }
</style>

<link rel="stylesheet" href="<?= BASE_ASSET; ?>admin-lte/plugins/morris/morris.css">

<section class="content-header">
    <h1>
        Dashboard
        <small>
            <?= get_option('site_name'); ?>
        </small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="#">
                <i class="fa fa-dashboard">
                </i>
                Home
            </a>
        </li>
        <li class="active">
            Dashboard
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        
        <div class="col-md-12" >
            <div class="box box-widget widget-user-2">
                <div class="widget-user-header" style="background-color:#282c34; color:white;">
                    <div class="widget-user-image">
                        <img alt="User Avatar" class="img-circle" src="<?=BASE_ASSET;?>/img/logo.png">
                        </img>
                    </div>
                    <h1 class="widget-user-username">
                        Selamat Datang
                    </h1>
                    <h3 class="widget-user-desc">
                        <?= _ent(ucwords(clean_snake_case($this->aauth->get_user()->full_name))); ?>,
                    </h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-12"  >
            <div class="box box-info widget-user-2" style="background-color:#282c34; color:white;">
                <div class="widget-user-header">
                    <h1 class="text-center">
                        <b><?= get_option('site_name'); ?></b>
                    </h1>
                    <h4 class="text-center">
                        <?= get_option('site_description'); ?>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?= BASE_ASSET; ?>admin-lte/plugins/morris/morris.min.js"></script>