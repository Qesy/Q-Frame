<!doctype html>
<html lang="zh-cn">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="<?=URL_BOOT?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=URL_BOOT?>css/swiper.min.css" rel="stylesheet">
    <link href="<?=URL_CSS?>backend.css" rel="stylesheet">

    <title>商户后台</title>
  </head>
  <body>
<div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h5><?=$Title?></h5>
        </div>
        <div class="card-body">
    <?=$this->BuildObj->Html?>
  </div>
</div>

</div>
</body>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="<?=URL_BOOT?>js/jquery-3.3.1.slim.min.js"></script>
<script src="<?=URL_BOOT?>js/popper.min.js"></script>
<script src="<?=URL_BOOT?>js/bootstrap.min.js"></script>
<script src="<?=URL_BOOT?>js/swiper.min.js"></script>
<script src="<?=URL_BOOT?>js/ajaxupload.js"></script>
<script src="<?=URL_EDITOR?>kindeditor-all-min.js"></script>
<script src="<?=URL_EDITOR?>lang/zh-CN.js"></script>
  <script type="text/javascript">
    <?=$this->BuildObj->Js?>
  </script>

</html>