<?php
require __DIR__ . '/../vendor/autoload.php';

use seregazhuk\PinterestBot\Factories\PinterestBot;

$blogUrl = 'http://awasome-blog-about-cats.com';
$keywords = ['cats', 'kittens', 'funny cats', 'cat pictures', 'cats art'];

$bot = PinterestBot::create();
$bot->getHttpClient()->useProxy('zproxy.lum-superproxy.io', '22225', 'lum-customer-hl_8ebb2ceb-zone-static:s2ny21hhmkq0',0);

$email = "michal.taylor94556@pictser.com";
$pass = "michaltaylor94556";

$ok = $bot->auth->register($email, $pass, $pass);
echo "ok:".$ok;

$bot->auth->login($email,$pass);

if ($bot->user->isBanned()) {
    echo "Account has been banned!\n";
    die();
}
$profile = new \seregazhuk\PinterestBot\Api\Forms\Profile();
$profile->setFirstName("Michal");
$profile->setLastName("Taylor");
$profile->setLocation("GB");
//$profile->setImage("")
$ok = $bot->user->profile($profile);
echo 'profile ok'.$ok;

$ok = $bot->boards->create('TIPS','TIPS');
echo 'boards ok'.$ok;

// get board id
$boards = $bot->boards->forUser($pass);
$boardId = $boards[0]['id'];

// select image for posting
$images = glob('C:\\Users\\Administrator\\IdeaProjects\\php-pinterest-bot(php)2\\images/*.*');
if (empty($images)) {
    echo "No images for posting\n";
    die();
}

$image = $images[0];

// select keyword
$keyword = $keywords[array_rand($keywords)];

// create a pin
$bot->pins->create($image, $boardId, $keyword, $blogUrl);

// remove image
unlink($image);
