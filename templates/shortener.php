<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>0bs.de! The super fancy URL shortener!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1, user-scalable=no">
    <!-- CSS -->
    <link rel="stylesheet" href="/media/css/styles.css?rev=<?php echo $revision;?>">

    <?php include_once("templates/_tracking.php") ?>
  </head>
  <body>
    <div id="logo">
      <a href="https://0bs.de" alt="0bs.de! The super fancy URL shortener!" title="0bs.de! The super fancy URL shortener!"><img src="/media/gfx/ft_logo.png" border="0" alt="Logo"></a>
    </div>
    <div id="container">
      <div class="wrapper">
        <form action="" method="post">
          <input id="longUrl" type="text" name="url" placeholder="Paste your URL here." autofocus>
          <input type="submit" value="Go">
        </form>
      </div>
      <p class="url info-box" data-url-container></p>
      <p class="error info-box" data-error-container></p>
      <a href="/" title="Click shift+/ (?) to see help" class="info name">0bs</span>
      <a href="/api" title="API-Reference" class="info api" target="_blank">API</a>
    </div>
    <!--[if lt IE 9]>
      <script src="/media/js/html5shiv.js"></script>
    <![endif]-->
    <script src="/media/js/jquery-1.9.1.min.js"></script>
    <script src="/media/js/scripts.js?rev=<?php echo $revision;?>"></script>
  </body>
</html>
