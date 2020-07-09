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

    case "createboard":

        $email = $_REQUEST["email"];
        $pass = $_REQUEST["pass"];
        $user = $_REQUEST["user"];
        $boardname = $_REQUEST["boardname"];

        $bot->auth->login($email, $pass);

        if ($bot->user->isBanned()) {
            $status->setHttpHeaders('application/json', 300);
            echo "Account has been banned!\n";
            die();
        }


        $res = $bot->boards->create($boardname,$boardname);
        echo $res;

    case "profileupdate":

        $email = $_REQUEST["email"];
        $pass = $_REQUEST["pass"];
        $user = $_REQUEST["user"];

        $bot->auth->login($email, $pass);

        if ($bot->user->isBanned()) {
            $status->setHttpHeaders('application/json', 300);
            echo "Account has been banned!\n";
            die();
        }

        $profile = new \seregazhuk\PinterestBot\Api\Forms\Profile();
        $profile->setFirstName($_REQUEST["first_name"]);
        $profile->setLastName($_REQUEST["last_name"]);
        $profile->setCountry("US");
        $profile->setLocation("US");

        $res = $bot->boards->create('TIPS','TIPS');
        echo 'boards res'.$res;
        if ($res){
            $status->setHttpHeaders('application/json', 200);
            $res = $bot->user->profile($profile);
            echo "profile res".$res;
        }else{
            $status->setHttpHeaders('application/json', 400);
            echo "profile res".$res;
            echo "FAIL";
        }

        break;

    case "usercreate":

        $email = $_REQUEST["email"];
        $pass = $_REQUEST["pass"];
        $user = $_REQUEST["user"];

        $ok = $bot->auth->register($email, $pass, $pass);
        if (!$ok) {
            $status->setHttpHeaders('application/json', 401);
            echo $ok;
            break;
        }else{
            $status->setHttpHeaders('application/json', 200);
            echo $ok;
        }
        /*

            $bot->auth->login($email, $pass);

            if ($bot->user->isBanned()) {
                $status->setHttpHeaders('application/json', 302);
                echo "Account has been banned!\n";
                break;
            }
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
        $res = $bot->boards->create('TIPS','TIPS');
        $status->setHttpHeaders('application/json', 200);
        echo $res;
        */

        break;

    case "login" :
        $email  = $_REQUEST["email"];
        $pass   = $_REQUEST["pass"];
        $user   = $_REQUEST["user"];

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
        break;

    case "" :
        $status->setHttpHeaders('application/json', 404);
        //echo $res;
        echo "METHOD NOT FOUND";
        //404 - not found;
        break;
}
?>