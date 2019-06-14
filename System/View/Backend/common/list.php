<!doctype html>
<html lang="zh-cn">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="<?=URL_BOOT?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=URL_BOOT?>css/swiper.min.css" rel="stylesheet">
  <link href="<?=URL_BOOT?>font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=URL_CSS?>backend.css" rel="stylesheet">

  <title>商户后台</title>
</head>
<body>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <? 
        if($this->BuildObj->IsAdd):        
        ?>
        <span class="float-right"><a href="<?=empty($AddUrl) ? url(array($this->BuildObj->Module, \Router::$s_controller, 'add')).'?'.http_build_query($_GET) : $AddUrl?>" class="btn btn-primary btn-sm">添加</a></span>
      <? endif ?>
        <h5><?=$Title?></h5>
      </div>

      
      <div class="card-body">
        <?=$this->HeadHtml?>
        <?=$table?>
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
</html>