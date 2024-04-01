<?php

require_once "APPS/Model/DAO.php";
require_once "Funciones/Responses.php";
use Firebase\JWT\JWT;

class PostController{


    /************************Metodo para crear usuarios nuevos *********************/
    static public function postControllerCreateUser($table,$POST,$photo){

        $idNegocio = $POST['id_business'];
        $userName = $POST['userName'];
        $password = $POST['password'];
        $confirmPassword = $POST['confirmPassword'];
        $name = $POST['name'];
        $type_user = $POST['type_user'];

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
            $doesTheUserExist = DAO::get($table,"*","username",$userName);
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
            $response = DAO::create($table,array(
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
                    $type_user,
                    $idNegocio
                )
            );
            Responses::responseNoDataWhitStatus($response);



    }


    //***********************Este metodo es usado para el inicio de sessión***************/
    static public function postDataconsultUser($table,$data){
        if (!preg_match('/^[a-zA-Z0-9]+$/', $data["username"]) || !preg_match('/^[a-zA-Z0-9]+$/', $data["password"])) { //Si el usuario o contraseña incluyen caracteres, no permite continúar
            Responses::responseNoDataWhitStatus(404);
        }else{//Si no hay caracteres especiales se envía la información al modelo para validar si el usuario y la contraseña coinciden
            $validateUser = DAO::get($table,"*","username",$data["username"]);
            if (count($validateUser)>=1) {
                $hashedPassword = $validateUser[0]->password;
                if (password_verify($data["password"], $hashedPassword)){
                    require_once "Funciones/TokenGenerate.php";
                    $tokenGerator = Token::generateToken($validateUser[0]->id, $validateUser[0]->username);
                    $jwt = JWT::encode($tokenGerator,'3aw58420', 'HS256'); //Generación de token con los datos de usuario y codigo alfanumerico
                    session_id($tokenGerator["id"]);
                    session_set_cookie_params(1800);
                    session_start();
                    $_SESSION["username"] = $validateUser[0]->username; //Se guardan las variables de sesión
                    $_SESSION["estatus"] = true;
                    $_SESSION["type_user"] = $validateUser[0]->type_user;
                    $response = array( //Se devuelve el json con la información necesaria para inicia la sesión en el front
                        'is_logged_in' => true,
                        'token' => $jwt,
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



    //***********************Este metodo es usado para modificar un usuarion***************/
    static public function postControllerModify($table,$POST,$photo){
        $id = $POST['id'];
        $name = $POST['name'];
        $type_user = $POST['type_user'];
        $userName = $POST['username'];
        $beforePicture = $POST['beforePicture'];
        if (
            !preg_match('/^[a-zA-Z\s]+$/', $name) ||
            !preg_match('/^[a-zA-Z0-9]+$/', $type_user)) {
                Responses::responseNoDataWhitStatus(404);
            }
            if(isset($photo['name'])){ //Si el formulario incluye una imagen, la agrega, sino se pone la img por defecto
                if (isset($beforePicture)) {
                    if($beforePicture !== "files/images/sin_imagen.webp"){
                        unlink($beforePicture); //Elimina el archivo anterior de la imagen
                    }}
                $carpetaDestino = "files/user_profile/" . $userName;
                $nombreArchivo = $photo['name'];
                $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }
                $rutaArchivoRelativa = 'files/user_profile/' . $userName .'/'. $nombreArchivo;
                move_uploaded_file($photo['tmp_name'], $rutaArchivo);
                $response = DAO::update($table,array("name","photo","type_user","id"),array($name, $rutaArchivoRelativa, $type_user,$id),2);
            }else{
                $response = DAO::update($table,array("name","type_user","id"),array($name,$type_user,$id) );
            }
            Responses::responseNoDataWhitStatus($response);
    }

    //***********************Cambiar una contraseña***************/
    static public function changePassword($table,$data){
        $id = $data['id'];
        $password = $data['password'];
        $confirmPassword = $data['confirmPassword'];
        if (!preg_match('/^[a-zA-Z0-9]+$/', $password) ||
        !preg_match('/^[a-zA-Z0-9]+$/', $confirmPassword)) {
            Responses::responseNoDataWhitStatus(404);
            exit;
        }
        if ($password !== $confirmPassword) { //Aquí se valida que la contraseña sea correcta
            Responses::responseNoDataWhitStatus(409);
            exit;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //Aquí se genera un hash para la contraseña
            $response = DAO::update($table,array("password","id"),array($hashedPassword,$id) );
            Responses::responseNoDataWhitStatus($response);
    }


    //Solicitudes delete

    //***********************Este metodo es usado para borrar un usuario***************/
    static public function deleteUserController($table,$data){
        $id = $data["id"];
        $username = $data["username"];
        $response = DAO::delete($table,"id",$id);
        if ($response == 200) {
            $directoryPath = "files/user_profile/" . $username;
            if (is_dir($directoryPath)) {
                require_once "Funciones/DeleteDirectory.php";
                deleteDirectory($directoryPath);
            }
        }
        Responses::responseNoDataWhitStatus($response);
    }


    static public function logout($id_session){
        session_id($id_session);
        session_start();
        session_destroy();
        session_unset();
        Responses::responseNoDataWhitStatus(200);
    }


}


?>