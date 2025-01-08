<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login</title>
  <link rel="icon" type="image/x-icon" href="<?=BASE_ASSET;?>img/logo.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= BASE_ASSET; ?>/admin-lte/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<style>
  .login-page {
  position: relative;
  background-image: url('<?= BASE_ASSET; ?>img/peti_kemas.jpg');
  background-size: cover;
  background-position: center;
  align-items: center;
  justify-content: center;
  margin-top:15em;
}
html::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
  z-index: -2; /* Ensure it's behind the content */
}


</style>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a style="color: #080404; font-weight:900;" href="<?= BASE_ASSET; ?>/admin-lte/index2.html"><b style="color: white;">Login</b> <?= get_option('site_name'); ?></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Login untuk memulai sesi</p>
    <?php if(isset($error) AND !empty($error)): ?>
         <div class="callout callout-error"  style="color:#C82626">
              <h4>Error!</h4>
              <p><?= $error; ?></p>
            </div>
    <?php endif; ?>
    <?php
    $message = $this->session->flashdata('f_message'); 
    $type = $this->session->flashdata('f_type'); 
    if ($message):
    ?>
   <div class="callout callout-<?= $type; ?>"  style="color:#C82626">
        <p><?= $message; ?></p>
      </div>
    <?php endif; ?>
     <?= form_open('', [
        'name'    => 'form_login', 
        'id'      => 'form_login', 
        'method'  => 'POST'
      ]); ?>
      <div class="form-group has-feedback <?= form_error('username') ? 'has-error' :''; ?>">
        <input type="text" class="form-control" placeholder="Username" name="username">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback <?= form_error('password') ? 'has-error' :''; ?>">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember" value="1"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    <?= form_close(); ?>

    <!-- /.social-auth-links -->

    <a href="<?= site_url('administrator/forgot-password'); ?>">Forget Password</a><br>
    <!--a href="<?= site_url('administrator/register'); ?>" class="text-center">Belum punya akun? Daftar</a-->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<div class="copyright text-center my-auto">
	<span style="color: white;">SIPTA - All Rights Reserved</span>
</div>

<!-- jQuery 2.2.3 -->
<script src="<?= BASE_ASSET; ?>/admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?= BASE_ASSET; ?>/admin-lte/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?= BASE_ASSET; ?>/admin-lte/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
