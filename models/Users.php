<?php
require_once "models/Bases/BaseITem.php";
require_once "services/crudDbMysql/DAO.php";

class Users extends BaseITem{
    protected $table = "profile_user";
    protected static $tableStatic = "profile_user";
    protected $columnBdNoStatic = [
        "username",
        "password",
        "name",
        "photo",
        "type_user",
        "id_negocio",
        "id"
    ];
    protected static $columnBd = [
        "username",
        "password",
        "name",
        "photo",
        "type_user",
        "id_negocio",
        "id"
    ];
    protected $userName;
    protected $password;
    protected $name;
    protected $photo;
    protected $typeUser;
    protected $idNegocio;
    protected $id;
    private $tokenSesion = null;
    public function __construct($userName,$password,$name,$photo,$typeUser,$idNegocio,$id = null) {
        $this->userName = $userName;
        $this->password = $password;
        $this->name = $name;
        $this->photo = $photo;
        $this->typeUser = $typeUser;
        $this->idNegocio = $idNegocio;
        $this->id=$id;
    }

    public static function all(){
        $response = new DAO();
        $result = $response->get(self::$tableStatic,"*");
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
            return $users;
        }else{
            return null;
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