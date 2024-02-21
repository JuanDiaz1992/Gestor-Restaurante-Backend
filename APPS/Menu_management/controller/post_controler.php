<?php

require_once "APPS/Menu_management/model/post_model.php";


class PostController{
    //Esta parte es la encargada de gestionar el menú
    static public function createMenuTemp($item){
        $return = new PostController();
        if (!isset($_SESSION["menu_temp"])){
            $_SESSION["menu_temp"] = array();
        }
        $itemExists = 0;
        foreach ($_SESSION["menu_temp"] as $existingItem) {
            if ($existingItem["id"] === $item["id"]) {
                $itemExists = 1;
                break;
            }
        }
        if ($itemExists === 0) {
            array_push($_SESSION["menu_temp"], $item);
            $response = $_SESSION["menu_temp"];
            $return -> fncResponse($response,200,"Ok");
        } else {
            $response = "";
            $return -> fncResponse($response,409,"Error");
        }
    }


    static public function createItemMenu($table,$name,$description,$price,$photo,$menu_item_type,$idProfile_user,$amount){
        if(isset($photo['name'])){ //Si el formulario incluye una imagen, la agrega, sino se pone la img por defecto
            $carpetaDestino = "files/images/MenuItems";
            $nombreArchivo = $photo['name'];
            $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }
            $rutaArchivoRelativa = 'files/images/MenuItems/' . $nombreArchivo;
            move_uploaded_file($photo['tmp_name'], $rutaArchivo);
        }else{//Si no hay una foto, se incluye la foto por defecto
            $rutaArchivoRelativa = "files/images/sin_imagen.webp";
        }
        if(!isset($price)){
            $price = 0;
        }
        $response = PostModel::createItemMenuModel($table,$name,$description,$price,$rutaArchivoRelativa,$menu_item_type,$idProfile_user,$amount);
        $return = new PostController();
        if ($response == 404){
            $return -> fncResponse($response,404,"Error al crear el item");
        }elseif($response == 200){
            $return -> fncResponse($response,200,"Item creado correctamente");
        }
    }


    static public function createMenu($date){
        $menuTemp = $_SESSION["menu_temp"];
        $response = PostModel::postRecordMenuyModel("menu",$date);
        $return = new PostController();
        $allElementsSaved = true; // Variable de registro
        if ($response["response"] === 200 ) {
            foreach ($menuTemp as $element){
                $responseItem = PostModel::postRecordAllMenusModel("all_menus",$response["id"],$element["id"],$date);
                if($responseItem !== 200){
                    $allElementsSaved = false;
                    break;
                }
            }
            if ($allElementsSaved) {
                $_SESSION["menu_temp"] = [];
                // Todos los elementos se guardaron correctamente, enviar respuesta exitosa
                $return->fncResponse("", 200,"Menú creado correctamente");
            } else {
                // Al menos un elemento falló, enviar respuesta de error
                $return->fncResponse("", 404,"Error al crear el menú");
            }
        }else{
            $return -> fncResponse($response,404);
        }
    }


    static public function editMenu($table,$id,$idMEnu,$dateTime){
        $return = new PostController();
        $responseItem = PostModel::postRecordAllMenusModel($table,$idMEnu,$id,$dateTime);
        if($responseItem === 200){
            $return->fncResponse("Menú creado correctamente", 200, "Elemento agregado correctamente");
        }else {
            $return->fncResponse("Error al crear el menú", 404, "Error al agregar el elemento");
        }
    }
    static public function editItemMenuController($POST,$FILES = ""){
        $return = new PostController();
        $rutaArchivoRelativa = "";
        if ($POST["name"] ==="" || $POST["amount"] ==="" || $POST["price"] ==="") {
            $return->fncResponse("",400,"Datos incompletos");
        }else{
            if (isset($POST["beforePicture"])) {
                if($POST["beforePicture"] !== "files/images/sin_imagen.webp"){
                    unlink($POST["beforePicture"]); //Elimina el archivo anterior de la imagen
                }
                $photo = $FILES['photo'];
                $carpetaDestino = "files/images/MenuItems";
                $nombreArchivo = $photo['name'];
                $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }
                $rutaArchivoRelativa = 'files/images/MenuItems/' . $nombreArchivo;
                move_uploaded_file($photo['tmp_name'], $rutaArchivo);
            }
            $table="items_menu";
            $response = PostModel::editItemMenuModel($POST,$table,$rutaArchivoRelativa);
            if ($response == 400){
                $return -> fncResponse($response,400,"Error");
            }elseif($response == 200){
                $return -> fncResponse($response,200,"Registro editado correctamente correctamente");
            }
        }
    }

    static public function changeState($table,$idMEnu,$id,$state){
        $response = PostModel::changeStateModel($table,$idMEnu,$id,$state);
        $return = new PostController();
        if ($response == 400){
            $return -> fncResponse($response,400,"Error");
        }elseif($response == 200){
            $return -> fncResponse($response,200,"Registro ingresado correctamente");
        }
    }


    //Solicitudes delete
    static public function deleteItemFromMenuBd($table,$id,$picture = ""){
        $response = PostModel::deleteItemFromMenuBdModel($table,$id);
        $return = new PostController();
        if($response === 200){
            if($picture !== "files/images/sin_imagen.webp" && $picture !=="" ){
                unlink($picture); //Elimina el archivo anterior de la imagen
            }
            $return -> fncResponse($response,200,"Item eliminado");
        }else{
            $return -> fncResponse("",200,"Error al eliminar el item");
        }


    }


    static public function deleteMenufromBd($table,$idMenu){
        $response = PostModel::deleteMenufromBdModel($table,$idMenu);
        $return = new PostController();
        if($response===200){
            $return -> fncResponse($response,200,"Menú eliminado correctamente");
        }else{
            $return -> fncResponse($response,404,"Error al eliminar el menú");
        }


    }


    static public function deleteItemTemporal($id){
        $tempArray = array();
        foreach($_SESSION["menu_temp"] as  $key => $existingItem){
            if($existingItem["id"]!=$id){
                array_push($tempArray, $existingItem);
            }
        }
        unset($_SESSION["menu_temp"]);
        $_SESSION["menu_temp"] = $tempArray;
        $return = new PostController();
        $return -> fncResponse("",200,"Eliminado correctamente");
    }


    //Respuesta del controlador:
    public function fncResponse($response,$status,$message){ //Metodo usado para dar respuestas básicas
        if (!empty($response) && $status === 200) {
            $json = array(
                'status' => $status,
                'results' => 'success',
                'registered'=>true,
                'response'=>$response,
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
