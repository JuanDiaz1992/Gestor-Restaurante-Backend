<?php
require_once "services/userServices/UserServices.php";
require_once "utils/Responses.php";

class UserController{
    public static function login($userName, $password){
        $user = UserServices::loginService($userName, $password);
        if ($user != null) {
            Responses::response($user);
        } else {
            Responses::responseNoDataWhitStatus(404);
        }
    }
    public static function createUser($data){
        $user = UserServices::createUserService($data);
        if ($user!=null) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }
    public static function deleteUser($data){
        $response = UserServices::deleteUserService($data);
        if($response){
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }
    public static function logout($token){
        $user = UserServices::logoutService($token);
        if($user){
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }
    public static function updateUser($data){
        $response = UserServices::updateUserService($data);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }
    public static function updatePassword($data){
        $response = UserServices::updatePasswordService($data);
        if ($response) {
            Responses::responseNoDataWhitStatus(200);
        }else{
            Responses::responseNoDataWhitStatus(404);
        }
    }
    public static function getAllUsers(){
        $result = UserServices::getAllUsers();
        if ($result != null) {
            Responses::response($result);
        }else{
            Responses::responseNoDataWhitStatus(400);
        }
    }
}





?>