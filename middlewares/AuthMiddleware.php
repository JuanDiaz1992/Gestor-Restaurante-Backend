<?php
require_once "utils/Responses.php";
require_once "services/UserServices/TokenService.php";
class AuthMiddleware {
    public function handle($token,$levelAuth) {
        if (!isset($token)) {
            $this->sendUnauthorizedResponse();
            return;
        }
        try {
            $tokenDecode = TokenService::decodeToken($token);
            if ($tokenDecode->typeUser == $levelAuth || $tokenDecode->typeUser == 1) {
                session_id($tokenDecode->idSesion);
                session_start();
            }else if(!$levelAuth){
                session_id($tokenDecode->idSesion);
                session_start();
            }else{
                $this->sendForbiddenResponse();
                return;
            }
        } catch (Exception $e) {
            $this->sendUnauthorizedResponse();
        }
    }

    private function sendUnauthorizedResponse() {
        Responses::responseNoDataWhitStatus(401);
    }

    private function sendForbiddenResponse() {
        Responses::responseNoDataWhitStatus(403);
    }
}



?>


