<style type="text/css">
   .widget-user-header {
      padding-left: 20px !important;
   }
</style>

<link rel="stylesheet" href="<?= BASE_ASSET; ?>admin-lte/plugins/morris/morris.css">

<section class="content-header">
    <h1>
        Informasi
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
            Informasi
        </li>
    </ol>
</section>

  <section class="content">
    <!-- /.box -->
    <div class="row">
      <!-- /.col (LEFT) -->
      <div class="col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Sistem</h3>
            <table class="table">
            <h5 scope="col"><?= get_option('site_name'); ?></h5>
              <tbody>
                <tr>
                  <td>Host</td>
                  <td><?= $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]"; ?></td>
                </tr>
                <tr>
                  <td>IP</td>
                  <td><?= $_SERVER['SERVER_ADDR']; ?></td>
                </tr>
                <tr>
                  <td>Port</td>
                  <td><?= $_SERVER['SERVER_PORT']; ?></td>
                </tr>
                <tr>
                  <td>CodeIgniter</td>
                  <td><?= print CI_VERSION;?></td>
                </tr>
                <tr>
                  <td>PHP</td>
                  <td><?= print phpversion();?></td>
                </tr>
                <tr>
                  <td>SQL</td>
                  <td><?= print mysqli_get_client_info();?></td>
                </tr>
                <tr>
                  <td>Database</td>
                  <td><?=get_database_config('database');?></td>
                </tr>
              </tbody>
            </table>
            <div class="box-tools pull-right">
              <!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
            </div>
          </div>
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col (RIGHT) -->

    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?= BASE_ASSET; ?>admin-lte/plugins/morris/morris.min.js"></script>
<script>
  // $(function () {
   // "use strict";

    // AREA CHART
    //var area = new Morris.Area({
   //   element: 'revenue-chart',
   //   resize: true,
    //  data: [
   //     {y: '2015 Q1', item1: 2666, item2: 2666},
   //     {y: '2015 Q2', item1: 2778, item2: 2294},
   //     {y: '2015 Q3', item1: 4912, item2: 1969},
   //     {y: '2015 Q4', item1: 3767, item2: 3597},
    //    {y: '2016 Q1', item1: 6810, item2: 1914},
   //     {y: '2016 Q2', item1: 5670, item2: 4293},
    //    {y: '2016 Q3', item1: 4820, item2: 3795},
    //    {y: '2016 Q4', item1: 15073, item2: 5967},
    //    {y: '2017 Q1', item1: 10687, item2: 4460},
    //    {y: '2017 Q2', item1: 8432, item2: 5713}
    //  ],
    //  xkey: 'y',
    //  ykeys: ['item1', 'item2'],
    //  labels: ['Item 1', 'Item 2'],
    //  lineColors: ['#a0d0e0', '#3c8dbc'],
    //  hideHover: 'auto'
   // });

    // LINE CHART
  //  var line = new Morris.Line({
   //   element: 'line-chart',
   //   resize: true,
   //   data: [
    //    {y: '2015 Q1', item1: 2666},
   //     {y: '2015 Q2', item1: 2778},
     //   {y: '2015 Q3', item1: 4912},
    //    {y: '2015 Q4', item1: 3767},
    //    {y: '2016 Q1', item1: 6810},
   //     {y: '2016 Q2', item1: 5670},
   //     {y: '2016 Q3', item1: 4820},
   //     {y: '2016 Q4', item1: 15073},
   //     {y: '2017 Q1', item1: 10687},
   //     {y: '2017 Q2', item1: 8432}
   //   ],
   //   xkey: 'y',
   //   ykeys: ['item1'],
   //   labels: ['Item 1'],
   //   lineColors: ['#3c8dbc'],
   //   hideHover: 'auto'
  //  });

    //DONUT CHART
  //  var donut = new Morris.Donut({
   //   element: 'sales-chart',
  //    resize: true,
 //     colors: ["#3c8dbc", "#f56954", "#00a65a"],
 //     data: [
 //       {label: "Download Sales", value: 12},
  //      {label: "In-Store Sales", value: 30},
  //      {label: "Mail-Order Sales", value: 20}
  //    ],
 //     hideHover: 'auto'
 //   });

    //BAR CHART
 //   var bar = new Morris.Bar({
 //     element: 'bar-chart',
  //    resize: true,
 //     data: [
 //       {y: '2013', a: 100, b: 90},
 //       {y: '2014', a: 75, b: 65},
 //       {y: '2015', a: 50, b: 40},
 ////       {y: '2017', a: 75, b: 65},
  //      {y: '2018', a: 50, b: 40},
  //      {y: '2019', a: 75, b: 65},
  //      {y: '2020', a: 100, b: 90}
 //     ],
  //    barColors: ['#00a65a', '#f56954'],
  //    xkey: 'y',
  ///    ykeys: ['a', 'b'],
  //    labels: ['CPU', 'DISK'],
   //   hideHover: 'auto'
  //  });
  //});
</script>