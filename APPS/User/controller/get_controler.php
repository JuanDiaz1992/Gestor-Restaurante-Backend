<?php


require_once "APPS/Model/ModelSentences.php";
require_once "APPS/Responses.php";


class GetController{
    static public function getAllUsers($table,$select){
        $response = new ModelSentences();
        $result = $response->getDataSimpleConsult($table,$select);
        if (!empty($result)) {
            $users = array();
            foreach($result as $key => $value){
                $user = array(
                    'id' => $value->id,
                    'username' => $value->username,
                    'name' => $value->name,
                    'photo' => $value->photo,
                    'type_user' => $value->type_user,
                );
                array_push($users, $user);
            }
            Responses::response($users);
        }else{
            Responses::response();
        }
    }


    static public function validateUSer($username){
        $json = array(
            'status' => 200,
            'logged_in' => true,
            'username' => $username
        );
    echo json_encode($json,http_response_code($json['status']));
    }

}


?>