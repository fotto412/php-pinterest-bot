<?php
require __DIR__ . '/../vendor/autoload.php';

use seregazhuk\PinterestBot\Factories\PinterestBot;

$blogUrl = 'http://awasome-blog-about-cats.com';
$keywords = ['cats', 'kittens', 'funny cats', 'cat pictures', 'cats art'];

$bot = PinterestBot::create();

//$bot->getHttpClient()->useProxy('127.0.0.1', '8118');


$email = 'junefawlar@pictsor.com';
$pass = 'junefawlar';
$bot->auth->register('deneme99992@pictsor.com', 'deneme99992', 'deneme5588');


$bot->auth->login($email, $pass);

if ($bot->user->isBanned()) {
    echo "Account has been banned!\n";
    die();
}

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
