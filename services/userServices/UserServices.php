<?php
require_once "models/Users.php";
require_once "services/userServices/TokenService.php";
use \Firebase\JWT\JWT;

class UserServices {
    public static function loginService($userName, $password) {
        // Verificar si el usuario y la contraseña son válidos
        if (!preg_match('/^[a-zA-Z0-9]+$/', $userName) || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            return null;
        }

        $user = Users::get($userName,"username");
        if ($user == null) {
            return null;
        }

        $hashedPassword = $user->getPassword();
        if (!password_verify($password, $hashedPassword)) {
            return null;
        }


        $tokenGerator = TokenService::generateToken($user->getId(),$user->getTypeUser()); //se genera un token
        $user->setToken(JWT::encode($tokenGerator,'3aw58420', 'HS256')); //se codifica ese token y se envia al front
        session_id($tokenGerator["idSesion"]); //se inicia sesión con el id de sesión generado en el token
        session_set_cookie_params(1800);
        session_start();
        $response = array(
            'is_logged_in' => true,
            'token' => $user->getToken(),
            'username' => $user->getUserName(),
            'name' => $user->getName(),
            'type_user' => $user->getTypeUser(),
            'photo' => $user->getPhoto(),
            'id_user' => $user->getId(),
            'id_negocio'=>$user->getIdNegocio()
        );
        return $response;
    }
    public static function logoutService($tokenCodifiqued) {
        try{
            $token = TokenService::decodeToken($tokenCodifiqued);
            session_destroy();
            session_unset();
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    public static function createUserService($data){
        $idNegocio = $data['id_business'];
        $userName = $data['userName'];
        $password = $data['password'];
        $confirmPassword = $data['confirmPassword'];
        $name = $data['name'];
        $type_user = $data['type_user'];
        $photo =  $data['photo'];

        if (!preg_match('/^[a-zA-Z0-9]+$/', $userName) || //En este if se validan caracteres especiales
            !preg_match('/^[a-zA-Z0-9]+$/', $password) ||
            !preg_match('/^[a-zA-Z\s]+$/', $name) ||
            !preg_match('/^[a-zA-Z0-9]+$/', $confirmPassword)) {
            return null;
        }
        if ($password !== $confirmPassword) { //Aquí se valida que la contraseña sea correcta
            return null;
        }
        //Se valida primero si el usuario ya existe, si existe se finaliza la ejecución
        $user = Users::get($userName,"username");
        if ($user!=null) {
            return null;
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
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = new Users($userName,$password,$name,$rutaArchivoRelativa,$type_user,1);
        $user->save();
        return $user;
    }
    public static function deleteUserService($data){
        $id = $data["id"];
        $user = Users::get($id,"id");
        $response = $user->delete();
        if($response){
                $directoryPath = "files/user_profile/" . $user->getUserName();
                if (is_dir($directoryPath)) {
                    require_once "utils/DeleteDirectory.php";
                    deleteDirectory($directoryPath);
                    }
            return true;
        }else{
            return false;
        }
    }
    public static function updateUserService($data){
        try {
            $id = $data['id'];
            $name = $data['name'];
            $type_user = $data['type_user'];
            $userName = $data['username'];
            $beforePicture = $data['beforePicture'];
            $user = Users::get($id,"id");
            if (!preg_match('/^[a-zA-Z\s]+$/', $name) || !preg_match('/^[a-zA-Z0-9]+$/', $type_user)) {
                    return false;
                }
            if(isset($data["photo"]['name'])){ //Si el formulario incluye una imagen, la agrega, sino se pone la img por defecto
                $photo = $data["photo"];
                if($user->getPhoto() !== "files/images/sin_imagen.webp"){
                    unlink($user->getPhoto()); //Elimina el archivo anterior de la imagen
                }
                $carpetaDestino = "files/user_profile/" . $userName;
                $nombreArchivo = $photo['name'];
                $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }
                $beforePicture= 'files/user_profile/' . $userName .'/'. $nombreArchivo;
                move_uploaded_file($photo['tmp_name'], $rutaArchivo);
                $user->setPhoto($beforePicture);
            }
            $user->setName($name);
            $user->setTypeUser($type_user);
            $user->setUserName($userName);
            $user->save();
            return true;
        } catch (Exeption $e) {
            return false;
        }
    }
    public static function updatePasswordService($data){
        try{
            $password = $data['password'];
            $confirmPassword = $data['confirmPassword'];
            if (!preg_match('/^[a-zA-Z0-9]+$/', $password) ||
            !preg_match('/^[a-zA-Z0-9]+$/', $confirmPassword)) {
                return false;
            }
            if ($password !== $confirmPassword) { //Aquí se valida que la contraseña sea correcta
                return false;
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $user = Users::get($data["id"],"id");
            $user->setPassword($hashedPassword);
            $user->save();
            return true;
        }catch(Exception $e){
            return false;
        }

    }
    public static function getAllUsers(){
        $users = Users::all();
        $data = array_map(function($user) {
            return $user->toArray();
        }, $users);
        return $data;
    }
}

?>
