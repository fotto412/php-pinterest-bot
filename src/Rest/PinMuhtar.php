<?php

require 'php-pinterest-bot(php)2' . '/vendor/autoload.php';

require_once("HTTPStatus.php");

use seregazhuk\PinterestBot\Factories\PinterestBot;
use seregazhuk\PinterestBot\Rest\HTTPStatus;



$view = "";
if(isset($_REQUEST["method"]))
    $view = $_REQUEST["method"];

$status = new  HTTPStatus();
/*
controls the RESTful services
URL mapping
*/
switch($view){

    case "pincreate":
//        echo 'all';

        $blogUrl = 'http://awasome-blog-about-cats.com';
        $keywords = ['cats', 'kittens', 'funny cats', 'cat pictures', 'cats art'];

        $bot = PinterestBot::create();

        //$bot->getHttpClient()->useProxy('127.0.0.1', '8118');


        $email = $_REQUEST["email"];
        $pass = $_REQUEST["pass"];

//        $bot->auth->register('deneme99992@pictsor.com', 'deneme99992', 'deneme5588');

        $bot->auth->login($email, $pass);

        if ($bot->user->isBanned()) {
            $status->setHttpHeaders('application/json', 300);
            echo "Account has been banned!\n";
            die();
        }else{
            $status->setHttpHeaders('application/json', 200);
            echo "LOGIN SUCCESS";
        }
        $boards = $bot->boards->forUser($pass);
        $boardId = $boards[0]['id'];

        if(!$boardId){
            $bot->boards->create('TIPS','TIPS');
            $boards = $bot->boards->forUser($pass);
            $boardId = $boards[0]['id'];
        }

        $image = $_REQUEST["image"];
        $keyword = $_REQUEST["pintitle"];
        $blogUrl = $_REQUEST["link"];

        $res=$bot->pins->create($image, $boardId, $keyword, $blogUrl);
        if ($res){
            $status->setHttpHeaders('application/json', 200);
            echo "PINNING SUCCESS";
        }else{
            $status->setHttpHeaders('application/json', 400);
            echo "PINNING FAIL";
        }

        break;

    case "single":
        echo 'single';
        break;

    case "" :
        //404 - not found;
        break;
}
?>