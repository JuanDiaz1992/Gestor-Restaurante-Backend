<?php
    require_once 'vendor/autoload.php';
    use \Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class TokenService{
        static public function generateToken($id,$typeUser){
            $time = time();
            // Generar un número aleatorio de 4 bytes (32 bits)
            $random_bytes = random_bytes(4);
            // Convertir los bytes en un número entero
            $new_id = unpack('N', $random_bytes)[1];
            // Asegurarse de que el número esté dentro del rango deseado (3000 a 11500)
            $new_id = ($new_id % 8501) + 3000;
            $token= array(
                "iat"=> $time,
                "exp" => $time + (60*60*24), //Esto dice que a la fecha actual le sume 24 horas
                "idUser"=>$id,
                "typeUser"=>$typeUser,
                "idSesion"=>$new_id
            );
            return  $token;
        }

        static public function decodeToken($token){
            $secretCode = $_ENV["SECRET_CODE"];
            $classDecode = $_ENV["CLASS_DECODE"];
            try {
                $decoded = JWT::decode($token,new Key($secretCode, 'HS256'));
                $time = time();
                $expiration = $decoded->exp;
                if ($time  > $expiration) {
                    return 401;
                }
                return $decoded;
            } catch (Exception $e) {
                throw new Exception("Invalid token: " . $e->getMessage());
            }
        }
    }


?>