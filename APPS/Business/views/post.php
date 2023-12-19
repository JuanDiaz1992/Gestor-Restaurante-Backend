<?php
require_once "APPS/Business/controller/post_controler.php";
$response = new PostController();

if(isset($_POST["business_create_info"])){
    $table = "business";
    $img = isset($_FILES['photo'])? $_FILES['photo'] : '';
    $response -> postControllerCreateinfoBusiness(
        $table,
        $_POST["nameBusiness"],
        $_POST["documentBusiness"],
        $_POST["description"],
        $_POST["address"],
        $_POST["numberPhone"],
        $_POST["officeHours"],
        $img,
    );

}

?>