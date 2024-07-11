<?php

    $secret_key="helloworld";

    function base64_URL_encode($arg) {
        return rtrim(
            strtr(
                base64_encode($arg),
                '+/', '-_'
            ), '='
        );
    }
    function base64_URL_decode($arg) {
        $arg = str_pad($arg, strlen($arg) % 4, '=', STR_PAD_RIGHT);
        $arg = strtr($arg, '-_', '+/');
        return base64_decode($arg);
    }
    

    
    function Create_JWT_Token($header, $payload) {
        global $secret_key;
        $h = json_encode($header);
        $p = json_encode($payload);
        $HEADER = base64_URL_encode($h);
        $PAYLOAD = base64_URL_encode($p);
        $signature = base64_URL_encode(
            hash_hmac(
                "sha256",
                "$HEADER.$PAYLOAD",
                $secret_key,
                true
            )
        );
        return "$HEADER.$PAYLOAD.$signature";
    }

    function CheckSignature($jwt) {
        global $secret_key; 
        list($header, $payload, $signature) = explode('.', $jwt);
        $expectedSignature = hash_hmac(
            'sha256',
            "$header.$payload",
            $secret_key,
            true
        );
        $expectedSignature = base64_URL_encode($expectedSignature);
        return hash_equals($signature, $expectedSignature);
    }
?>
