<?php

require_once "APPS/User/model/post_model.php";
require_once "APPS/Model/ModelPost.php";
require_once "APPS/Model/ModelGet.php";
require_once "APPS/Responses.php";


class PostController{


    /************************Metodo para crear usuarios nuevos *********************/
    static public function postControllerCreateUser($id_business,$userName, $password, $confirmPassword, $name, $photo, $type_user){
        if (!preg_match('/^[a-zA-Z0-9]+$/', $userName) || //En este if se validan caracteres especiales
            !preg_match('/^[a-zA-Z0-9]+$/', $password) ||
            !preg_match('/^[a-zA-Z\s]+$/', $name) ||
            !preg_match('/^[a-zA-Z0-9]+$/', $confirmPassword)) {
                Responses::responseNoDataWhitStatus(405);
                exit;
            }
            if ($password !== $confirmPassword) { //Aquí se valida que la contraseña sea correcta 
                Responses::responseNoDataWhitStatus(406);
                exit;
            }
            //Se valida primero si el usuario ya existe, si existe se finaliza la ejecución
            $table = "profile_user";
            $doesTheUserExist = ModelGet::getDataSimpleConsult($table,"*","username",$userName);
            if (count($doesTheUserExist)>=1) {
                Responses::responseNoDataWhitStatus(409);
                exit;
            }
            if(isset($photo['name'])){ //Si el formulario incluye una imagen, la agrega, sino se pone la img por defecto
                $carpetaDestino = "files/user_profile/" . $userName;
                $nombreArchivo = $photo['name'];
                $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }
                $rutaArchivoRelativa = 'files/user_profile/' . $userName .'/'. $nombreArchivo;
                move_uploaded_file($photo['tmp_name'], $rutaArchivo);
            }else{
                $rutaArchivoRelativa = "files/images/sin_imagen.webp";
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //Aquí se genera un hash para la contraseña
            $response = ModelPost::simplePost($table,array(
                "username",
                "password",
                "name",
                "photo",
                "type_user",
                "id_negocio"),
                array(
                    $userName,
                    $hashedPassword,
                    $name,
                    $rutaArchivoRelativa,
                    $type_user
                )
            );
            Responses::responseNoDataWhitStatus($response);



    }//***********************Este metodo es usado para el inicio de sessión***************/


    static public function postDataconsultUser($table,$userName,$password){
        if (!preg_match('/^[a-zA-Z0-9]+$/', $userName) || !preg_match('/^[a-zA-Z0-9]+$/', $password)) { //Si el usuario o contraseña incluyen caracteres, no permite continúar
            Responses::responseNoDataWhitStatus(404);
        }else{//Si no hay caracteres especiales se envía la información al modelo para validar si el usuario y la contraseña coinciden
            $table = "profile_user";
            $validateUser = ModelGet::getDataSimpleConsult($table,"*","username",$userName);
            if (count($validateUser)>=1) {
                $hashedPassword = $validateUser[0]->password;
                if (password_verify($password, $hashedPassword)){
                    $new_id = rand(3000, 11500); //Se genera un id para la sesión el cuál servirá como token en el front
                    session_id($new_id);
                    session_set_cookie_params(1800);
                    session_start();
                    $_SESSION["username"] = $validateUser[0]->username; //Se guardan las variables de sesión
                    $_SESSION["estatus"] = true;
                    $_SESSION["type_user"] = $validateUser[0]->type_user;
                    $response = array( //Se devuelve el json con la información necesaria para inicia la sesión en el front
                        'is_logged_in' => true,
                        'token' => $new_id,
                        'username'=> $_SESSION["username"],
                        "message"=> "Usuario correcto",
                        "name"=> $validateUser[0]->name,
                        "type_user" => $validateUser[0]->type_user,
                        "photo" => $validateUser[0]->photo,
                        "id_user"=>$validateUser[0]->id
                    );
                    Responses::response($response);
                }else{
                    Responses::responseNoDataWhitStatus(404);
                }
            }else{
                Responses::responseNoDataWhitStatus(404);
            }
        }
    }




    static public function postControllerModify($id, $name, $photo, $type_user, $userName){
        if (
            !preg_match('/^[a-zA-Z\s]+$/', $name) ||
            !preg_match('/^[a-zA-Z0-9]+$/', $type_user)) {
                $json = array(
                    'status' => 404,
                    'is_logged_in' => false,
                    'message' => 'Los datos no pueden contener caracteres especiales'
                );
                echo json_encode($json, http_response_code($json['status']));
                exit;
            }
            if(isset($photo['name'])){ //Si el formulario incluye una imagen, la agrega, sino se pone la img por defecto
                $carpetaDestino = "files/user_profile/" . $userName;
                $nombreArchivo = $photo['name'];
                $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }
                $rutaArchivoRelativa = 'files/user_profile/' . $userName .'/'. $nombreArchivo;
                move_uploaded_file($photo['tmp_name'], $rutaArchivo);
            }else{
                $rutaArchivoRelativa = false;
            }
            $response = PostModel::PostDataModify($id, $name, $rutaArchivoRelativa, $type_user);
            $return = new PostController();
            if ($response == 409){
                $return -> fncResponse($response,409,"No se pudo realizar el cambio");
            }elseif($response == 200){
                $return -> fncResponse($response,200,"Cambio éxitoso");
            }
    }


    static public function changePassword($id,$password,$confirmPassword){
        if (!preg_match('/^[a-zA-Z0-9]+$/', $password) ||
        !preg_match('/^[a-zA-Z0-9]+$/', $confirmPassword)) {
            $json = array(
                'status' => 404,
                'message' => 'Los datos no pueden contener caracteres especiales'
            );
            echo json_encode($json, http_response_code($json['status']));
            exit;
        }
        if ($password !== $confirmPassword) { //Aquí se valida que la contraseña sea correcta 
            $json = array(
                'status' => 404,
                'is_logged_in' => false,
                'message' => 'Las contraseñas no coinciden'
            );
            echo json_encode($json, http_response_code($json['status']));
            exit;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //Aquí se genera un hash para la contraseña
            $response = PostModel::PostChagePassword($id,$hashedPassword);
            $return = new PostController();
            if ($response == 404){
                $return -> fncResponse($response,404);
            }elseif($response == 200){
                $return -> fncResponse($response,200);
            }
    }


    //Solicitudes delete
    static public function deleteUserController($id){
        $response = PostModel::deleteUserModel($id);
        $return = new PostController();
        if($response === 200){
            $return -> fncResponse($response,200,"Usuario eliminado");
        }else if($response === 404){
            $return -> fncResponse($response,404,"No se pudo eliminar el usuario");
        }
    }


    static public function logout($id_session){
        session_id($id_session);
        session_start();
        session_destroy();
        session_unset();
        $json = array(
            'status' => 200,
            'is_logged_in' => false,
            'message'=>'Usuario deslogueado'
        );
        echo json_encode($json,http_response_code($json['status']));
    }
    /******************************************************/


    //Respuesta del controlador:
    public function fncResponse($response,$status,$message){ //Metodo usado para dar respuestas básicas
        if (!empty($response) && $status === 200) {
            $json = array(
                'status' => $status,
                'results' => 'success',
                'registered'=>true,
                'message' => $message
            );
        }else if($status === 409){
            $json = array(
                'registered'=>false,
                'status' => $status,
                'results' => 'Not Found',
                'message' => $message
            );
        }else{
            $json = array(
                'registered'=>false,
                'status' => $status,
                'results' => 'Not Found',
                'message' => $message
            );
        }
        echo json_encode($json,http_response_code($json['status']));
    }


}


?>