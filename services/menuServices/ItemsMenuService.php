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
        $itemMenu = new ItemMenu($name,$description,$price,$rutaArchivoRelativa,$menu_item_type,$amount);
        $menuItem->save();
        return $menuItem;
    }


    static public function updateItemMenuService($data){
        try {
            $photo = $data["photo"];
            $rutaArchivoRelativa = "";
            $menuItem = ItemMenu::get($data['idItem'] ,"id");
            if ($data["name"] ==="" || $data["amount"] ==="" || $data["price"] ==="") {
                return false;
            }else{
                if (isset($photo['name'])) {
                    if($menuItem->getPicture() !== "files/images/sin_imagen.webp" && $menuItem->getPicture() != null){
                        unlink($data["beforePicture"]); //Elimina el archivo anterior de la imagen
                    }
                    $carpetaDestino = "files/images/MenuItems";
                    $nombreArchivo = $photo['name'];
                    $rutaArchivo = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;
                    if (!is_dir($carpetaDestino)) {
                        mkdir($carpetaDestino, 0777, true);
                    }
                    $rutaArchivoRelativa = 'files/images/MenuItems/' . $nombreArchivo;
                    move_uploaded_file($photo['tmp_name'], $rutaArchivo);
                }
                $menuItem->setName($data["name"]);
                $menuItem->setDescription($data["description"]);
                $menuItem->setPrice($data["price"]);
                $menuItem->setMenuItemType($data["menu_item_type"]);
                $menuItem->setAmount($data["amount"]);
                if ($rutaArchivoRelativa!=="") {
                    $menuItem->setPicture($rutaArchivoRelativa);
                }
                $menuItem->save();
                return true;
            }
        } catch (\Throwable $th) {
            return false;
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