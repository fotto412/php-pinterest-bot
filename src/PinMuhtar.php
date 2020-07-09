<?php

require __DIR__. '/../vendor/autoload.php';


use seregazhuk\PinterestBot\Factories\PinterestBot;
use seregazhuk\PinterestBot\Rest\HTTPStatus;



$view = "";
if(isset($_REQUEST["method"])) {
    $view = $_REQUEST["method"];
}
$bot = PinterestBot::create();

if(isset($_REQUEST["proxy"])) {
    $proxy = $_REQUEST["proxy"];
    $proxyport = $_REQUEST["proxyport"];
    if (isset($_REQUEST["proxyuser"])){
        $proxyuser = $_REQUEST["proxyuser"];
        $bot->getHttpClient()->useProxy($proxy, $proxyport, $proxyuser);
    }else {
        $bot->getHttpClient()->useProxy($proxy, $proxyport);
    }
}


$status = new  HTTPStatus();
/*
controls the RESTful services
URL mapping
*/
switch($view){

    case "pincreate":


        $email = $_REQUEST["email"];
        $pass = $_REQUEST["pass"];
        $user   = $_REQUEST["user"];
//        $email = html_entity_decode($email,ENT_HTML401,"UTF-8");
//        $pass = html_entity_decode($pass,ENT_HTML401,"UTF-8");
//        $user = html_entity_decode($user,ENT_HTML401,"UTF-8");


        $res = $bot->auth->login($email, $pass);
        if(!$res){
            $status->setHttpHeaders('application/json', 307);
            echo $res;
            die();
        }

        if ($bot->user->isBanned()) {
            $status->setHttpHeaders('application/json', 302);
            echo "Account has been banned!\n";
            die();
//        }else{
//            $status->setHttpHeaders('application/json', 200);
//            echo "LOGIN SUCCESS";
        }


        $boards = $bot->boards->forUser($user);

        if (isset($boards[0])) {
            $boardId = $boards[0]['id'];
        }else{
            if ($bot->boards->create('TIPS','TIPS')) {
                $boards = $bot->boards->forUser($user);
                $boardId = $boards[0]['id'];
            }else{
                $status->setHttpHeaders('application/json', 305);
                echo 'BOARD CREATE FAIL';
                die();
            }
        }

        $image = $_REQUEST["image"];
        $keyword = $_REQUEST["pintitle"];
        $blogUrl = $_REQUEST["link"];

        $image = html_entity_decode($image,ENT_HTML401,"UTF-8");
        $keyword = html_entity_decode($keyword,ENT_HTML401,"UTF-8");
        $blogUrl = html_entity_decode($blogUrl,ENT_HTML401,"UTF-8");

        $res=$bot->pins->create($image, $boardId, $keyword, $blogUrl);
        if ($res){
            $status->setHttpHeaders('application/json', 200);
            echo json_encode($res);
            //echo $res;
        }else{
            $status->setHttpHeaders('application/json', 400);
            //echo $res;
            echo json_encode($res);
            echo "FAIL";
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