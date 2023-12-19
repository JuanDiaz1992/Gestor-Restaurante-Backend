<?php

require_once "APPS/Inventory/model/post_model.php";


class PostController{


    /************************Metodo para crear usuarios nuevos *********************/
    static public function postRecordInventoryController($table, $purchaseValue, $reason, $observations, $idProfile_user,$date){
            if ($purchaseValue && $reason &&  $idProfile_user) {
                if($observations ===""){
                    $observations = "No hay observaciones";
                }
                $response = PostModel::postRecordInventoryModel($table, $purchaseValue, $reason, $observations, $idProfile_user,$date);
                $return = new PostController();
                if ($response == 404){
                    $return -> fncResponse($response,404, "No se pudo realizar el registro, valide los datos e intentelo de nuevo");
                }elseif($response == 200){
                    $return -> fncResponse($response,200, "Registro realizado correctamente");
                }
            }else{
                $json = array(
                    'status' => 404,
                    'message' => 'Hay datos sin rellenar'
                );
                echo json_encode($json, http_response_code($json['status']));
                exit;
            }
    }


    static public function deleteItemInvetoryController($table,$id){
        $response = PostModel::deleteItemInvetoryModel($table,$id);
        $return = new PostController();
        if ($response == 404){
            $return -> fncResponse($response,404,"No se pudo eliminar el registro");
        }elseif($response == 200){
            $return -> fncResponse($response,200,"Registro eliminado correctamente");
        }
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
