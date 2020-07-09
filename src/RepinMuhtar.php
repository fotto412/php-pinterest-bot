<?php

require __DIR__. '/../vendor/autoload.php';


use seregazhuk\PinterestBot\Factories\PinterestBot;
use seregazhuk\PinterestBot\Rest\HTTPStatus;

/*TEST
$_REQUEST["method"] = "usercreate";
$_REQUEST["email"] = "muhtarfeza9988@pictser.com";
$_REQUEST["pass"] = "muhtarfeza9988";
*/

$view = "";
if(isset($_REQUEST["method"]))
    $view = $_REQUEST["method"];

$status = new  HTTPStatus();
$status->setHttpHeaders('application/json', 503);
/*
controls the RESTful services
URL mapping
*/

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

switch($view){

    case "repin":

        $email  = $_REQUEST["email"];
        $pass   = $_REQUEST["pass"];
        $user   = $_REQUEST["user"];
        $pinId  = $_REQUEST["pinid"];

        $login = $bot->auth->login($email, $pass);
        if (!$login){
            $status->setHttpHeaders('application/json', 310);
            echo $login;
            echo "LOGIN FAIL\n";
            die();
        }

        if ($bot->user->isBanned()) {
            $status->setHttpHeaders('application/json', 310);
            echo "Account has been banned!\n";
            die();
        }

        $boards = $bot->boards->forUser($user);

        if (isset($boards[0]['id'])) {
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

        $res = $bot->pins->repin($pinId, $boardId);
        if ($res){
            $status->setHttpHeaders('application/json', 200);
            echo json_encode($res);
        }else{
            $status->setHttpHeaders('application/json', 400);
            echo $res;
            echo "REPIN FAIL";
        }

        break;

    case "" :
        $status->setHttpHeaders('application/json', 404);
        //echo $res;
        echo "METHOD NOT FOUND";
        //404 - not found;
        break;
}
?>