<?php
class ItemsMenuService {
    static public function createItemMenuService($table,$name,$description,$price,$photo,$menu_item_type,$idProfile_user,$amount){
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
        $itemMenu = new ItemMenu(null,$name,$description,$price,$rutaArchivoRelativa,$menu_item_type,$amount);
        return $menuItem->save();
    }


    static public function updateItemMenuService($POST,$FILES = ""){
        $rutaArchivoRelativa = "";
        if ($POST["name"] ==="" || $POST["amount"] ==="" || $POST["price"] ==="") {
            return false;
        }else{
            $menuItem = ItemMenu::get($POST["idItem"]);
            if ($menuItem->setPicture() != null || $menuItem->setPicture() != "") {
                if($menuItem->setPicture() !== "files/images/sin_imagen.webp"){
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
            $menuItem->setName($POST["name"]);
            $menuItem->setDescription($POST["description"]);
            $menuItem->setPrice($POST["price"]);
            $menuItem->setMenuItemType($POST["menu_item_type"]);
            $menuItem->setAmount($POST["amount"]);
            if ($rutaArchivoRelativa!=="") {
                $menuItem->setPicture($rutaArchivoRelativa);
            }
            return $menuItem->save();
        }
    }


    static public function deleteItemMenuService($id){
        try {
            $menuItem = ItemMenu::get($id);
            if($menuItem->setPicture() !== "files/images/sin_imagen.webp" && $menuItem->setPicture() !== null ){
                unlink($menuItem->setPicture()); //Elimina el archivo anterior de la imagen
            }
            return $menuItem->delete();
        } catch (Exception $e) {
            return false;
        }
    }


    static public function getAllItemsMenuService(){
        return ItemMenu::all();
    }
}


?>