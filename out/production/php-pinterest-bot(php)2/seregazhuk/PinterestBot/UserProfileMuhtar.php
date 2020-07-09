<?php

require __DIR__.'/vendor/autoload.php';


use seregazhuk\PinterestBot\Factories\PinterestBot;
use seregazhuk\PinterestBot\Rest\HTTPStatus;



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
$bot->getHttpClient()->useProxy('127.0.0.1', '8118');

switch($view){

    case "profileupdate":
//        echo 'all';




        $email = $_REQUEST["email"];
        $pass = $_REQUEST["pass"];

//        $bot->auth->register('deneme99992@pictsor.com', 'deneme99992', 'deneme5588');

        $bot->auth->login($email, $pass);

        if ($bot->user->isBanned()) {
            $status->setHttpHeaders('application/json', 300);
            echo "Account has been banned!\n";
            die();
//        }else{
//            $status->setHttpHeaders('application/json', 200);
//            echo "LOGIN SUCCESS";
        }

        $profile = new \seregazhuk\PinterestBot\Api\Forms\Profile();
        $profile->setFirstName($_REQUEST["first_name"]);
        $profile->setLastName($_REQUEST["last_name"]);
        $profile->setCountry("US");
        $profile->setLocation("US");

        $res = $bot->user->profile($profile);
        //$res=$bot->pins->create($image, $boardId, $keyword, $blogUrl);
        if ($res){
            $status->setHttpHeaders('application/json', 200);
            echo json_encode($res);
            //echo $res;
        }else{
            $status->setHttpHeaders('application/json', 400);
            //echo $res;
            echo "FAIL";
        }

        break;


    case "usercreate":

        $email = $_REQUEST["email"];
        $pass = $_REQUEST["pass"];

        $ok = $bot->auth->register($email, $pass, $pass);
        if (!$ok) {
            $status->setHttpHeaders('application/json', 301);
            echo json_encode($ok);
            break;
        }

//        if ($bot->user->isBanned()) {
//            $status->setHttpHeaders('application/json', 302);
//            echo "Account has been banned!\n";
//            break;
//        }
        $profile = new \seregazhuk\PinterestBot\Api\Forms\Profile();
        $profile->setFirstName($_REQUEST["first_name"]);
        $profile->setLastName($_REQUEST["last_name"]);
        $profile->setCountry("US");
        $profile->setLocation("US");

        $res = $bot->user->profile($profile);
        if (!$res){
            $status->setHttpHeaders('application/json', 400);
            echo "PROFILE FAIL";
            break;
        }

        $bot->boards->create('TIPS','TIPS');
        $status->setHttpHeaders('application/json', 200);
        echo json_encode($status);
        break;

    case "" :
        $status->setHttpHeaders('application/json', 404);
        //echo $res;
        echo "METHOD NOT FOUND";
        //404 - not found;
        break;
}
?>