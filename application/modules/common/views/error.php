<!doctype html>
<html>
<head>
<title>Error</title>
<style>body{text-align:center;padding:150px;}h1{font-size:50px;}body{font:20px Helvetica,sans-serif;color:#333;}article{display:block;text-align:left;width:650px;margin:0 auto;}a{color:#dc8100;text-decoration:none;}a:hover{color:#333;text-decoration:none;}.small{font-size:11px;}</style>
</head>
<body>
<article>
    <h1><?=$code?>: <?=$message?></h1>
    <div>
        <?php if($code==404){ ?>
            <p>Sorry for the inconvenience, but the page you are looking for does not exist.</p>
            <p>You may go back to the <a href="<?=GLBL_URL?>">home page</a>.</p>
        <?php }else{ ?>
            <p>We'll get this fixed as soon as possible. If you need to you can always contact us or else try going back to the <a href="<?=GLBL_URL?>">home page</a>.</p>
            <p class="small">More info: <?=$real_message?></p>
        <?php } ?>
    </div>
</article>
</body>
</html>