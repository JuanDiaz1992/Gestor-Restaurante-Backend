<?php
require_once "models/IPersona.php";
require_once "services/crudDbMysql/DAO.php";

class Users implements IPersona{
    private $id;
    private $name;
    private $userName;
    private $password;
    private $typeUser;
    private $photo;
    private $idNegocio;
    private $tokenSesion = null;
    public function __construct($id = null,$name,$userName,$password,$typeUser,$photo,$idNegocio) {
        $this->id=$id;
        $this->name = $name;
        $this->userName = $userName;
        $this->password = $password;
        $this->typeUser = $typeUser;
        $this->photo = $photo;
        $this->idNegocio = $idNegocio;
    }

    public static function getUser($identifier = null){
        if ($identifier !== null) {
            $userBD = null;
            if(is_numeric($identifier)){
                $userBD = DAO::get("profile_user","*","id",$identifier);
            } else {
                $userBD = DAO::get("profile_user","*","username",$identifier);
            }
            if (count($userBD)>=1) {
                $user = new Users(
                    $userBD[0]->id,
                    $userBD[0]->name,
                    $userBD[0]->username,
                    $userBD[0]->password,
                    $userBD[0]->type_user,
                    $userBD[0]->photo,
                    $userBD[0]->id_negocio,
                );
                return $user;
            }
        }
        return null;
    }



    public function save(){
        if ($this->id != null) {
            $userBd = DAO::update("profile_user",array(
                "name",
                "password",
                "photo",
                "type_user",
                "id"),array(
                $this->name,
                $this->password,
                $this->photo,
                $this->typeUser,
                $this->id));
        }else{
            $userBd = DAO::create("profile_user",array(
                "username",
                "password",
                "name",
                "photo",
                "type_user",
                "id_negocio"),
                array(
                    $this->userName,
                    $this->password,
                    $this->name,
                    $this->photo,
                    $this->typeUser,
                    $this->idNegocio
                ),true
            );
            $this->id = $userBd['id'];
            return true;
        }
    }

    public function delete(){
        $response = DAO::delete("profile_user","id",$this->id);
        if($response == 200){
            return true;
        }else{
            return false;
        }
    }

    //*Getters and setters */
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getTypeUser() {
        return $this->typeUser;
    }

    public function setTypeUser($typeUser) {
        $this->typeUser = $typeUser;
    }

    public function getIdNegocio() {
        return $this->idNegocio;
    }

    public function setIdNegocio($idNegocio) {
        $this->idNegocio = $idNegocio;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function getToken(){
        return $this->token;
    }
    public function setToken($token){
        $this->token = $token;
    }
}

?>