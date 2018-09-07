<?php
define('FACEBOOK_SDK_V4_SRC_DIR',  __DIR__ . '/src/Facebook/');
require_once 'autoload.php';
require_once 'config.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

FacebookSession::setDefaultApplication($fb_access['app_id'],$fb_access['app_secret']);
$session = new FacebookSession($fb_access['access_token']);
try {
    $me = (new FacebookRequest(
        $session, 'GET', '/'.$pageId.'/albums?fields=name&limit=25'
        ))->execute()->getGraphObject();
    $misalbums = $me->getProperty('data')->asArray();
} catch (FacebookRequestException $e) {
// The Graph API returned an error
} catch (\Exception $e) {
// Some other error occurred
}
if (isset($_GET['album_id'])) {
    $album_id = $_GET['album_id'];
}
else {
    $album_id = $misalbums[0]->id;
}
foreach ($misalbums as $key => $value) {
    if ($value->id == $album_id) {
        $existealbum = true;
    }
}

//obtener descripcion de album actual
try {
  $req = new FacebookRequest(
      $session,
      'GET',
      $album_id.'?fields=name,description'
      );
  $resp = $req->execute();
  $imgObj = $resp->getGraphObject();
  $albumDescription = $imgObj->getProperty('description');

} catch (FacebookRequestException $e) {
  // The Graph API returned an error
} catch (\Exception $e) {
  // Some other error occurred
}
$request = new FacebookRequest(
    $session,
    'GET',
    $album_id.'/photos?fields=name,source'
    );
$allimgs = array();
do {
        $response = $request->execute();
        $imgObj = $response->getGraphObject();
        if ($imgObj->getProperty('data') != NULL) {
            $imgs = $imgObj->getProperty('data')->asArray();
            $allimgs = array_merge($allimgs,$imgs);
        }
    } while ($request = $response->getRequestForNextPage());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show FB Albums</title>
</head>
<body>
<ul class="nav navbar-nav">
<?php
$tmp = 0;
foreach ($misalbums as $key => $value) if($tmp++ < 10) {
    ?>
    <li class="<?=($album_id == $value->id)? "active": "" ?>"><a href="index.php?album_id=<?=$value->id ?>" ><?=$value->name; ?></a></li>
    <?php 
}
?>
</ul>
<div class='blog-posts'>
    <div class="grid">
        <?php
        foreach ($allimgs as $key => $value) {
            if (!property_exists($value, "name")) {
                $value->name = '.';
            }
            ?>
            <div class="grid-item">
                <?=$value->name; ?><br />
                <a href="<?=$value->source; ?>" data-title="<?=$value->name ?>"><img src="<?=$value->source; ?>" alt="<?=$value->name?>" width=100 height=100 /></a>
            </div>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>
