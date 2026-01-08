<?php

require_once './config/config.php';

function base64url_encode($data){
    return rtrim(strtr(base64_encode($data), "+/", "-_"), "=");
}

class AuthHelper {
    function getAuthHeaders(){
        $header = "";
        if(isset($_SERVER['HTTP_AUTHORIZATION'])){
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])){
            $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        return $header;
    }

    function createToken($payload){
        $header = array(
            "alg" => "HS256", 
            "typ" => "JWT" //token tipo json
        );

        # Determina el tiempo de vencimiento del token
        $payload ['exp'] = time() + JWT_EXP;

        # Se codifica en base 64 y luego en JSON string el pay y el head
        $header = base64url_encode(json_encode($header));
        $payload = base64url_encode(json_encode($payload));

        # Se crea el secreto con el que se firma el token
        $signature = hash_hmac('SHA256', "$header.$payload", JWT_KEY, true);
        $signature = base64url_encode($signature);

        # Creacion del token concatenando header, payload y firma
        $token = "$header.$payload.$signature";

        return $token;
    }

    function verify($token){

        $tokenParts = explode(".", $token);

        if (count($tokenParts) !== 3) {
            // Error si el token no tiene el formato correcto
            return false;
        }
    
        list($header, $payload, $signature) = $tokenParts;


        
        $newSignature = hash_hmac("SHA256", "$header.$payload", JWT_KEY, true);
        $newSignature = base64url_encode($newSignature);
    
        if (!hash_equals($newSignature, $signature)) {
            // Error si las firmas no coinciden
            return false;
        }


        $decodedPayload = json_decode(base64_decode($payload));

        if ($decodedPayload === null) {
            // Error al decodificar el payload
            return false;
        }
    
        if ($decodedPayload->exp < time()) {
            // Error si el token ha expirado
            return false;
        }
        
        return $decodedPayload;
    }
    
    function currentUser(){

        # Devuelve bearer $token
        $auth = $this->getAuthHeaders();

        # Guarda en un arreglo la palabra clave y el token
        $auth = explode(" ", $auth);

        # Verificacion del modo de autenticacion
        if($auth[0] !== "Bearer") {
            return false;
        }

        $auth[1] = trim($auth[1], '"'); 

        # Se verifica el token y en caso correcto se devuelve el payload
        return $this->verify($auth[1]);
    }
}