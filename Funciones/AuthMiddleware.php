<?php

class AuthMiddleware {
    public function handle($request) {
        // Verificar si se ha proporcionado un token de autenticación en la cabecera de la solicitud
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(array("message" => "Acceso denegado. Token de autenticación no proporcionado."));
            exit();
        }

        // Verificar si el token de autenticación es válido (aquí deberías implementar tu lógica de validación de tokens)
        $token = $headers['Authorization'];
        if (!$this->isValidToken($token)) {
            http_response_code(401);
            echo json_encode(array("message" => "Acceso denegado. Token de autenticación inválido."));
            exit();
        }

        // Si el token es válido, puedes continuar con la solicitud
    }

    private function isValidToken($token) {

        return !empty($token);
    }
}


?>


