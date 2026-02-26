<?php
define('JWT_SECRET', 'chave_super_secreta_123');
define('JWT_EXP', 3600); // 1 hora de tempo de duração do

function gerarJWT(array $payload): string {
    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload['exp'] = time() + JWT_EXP;
    $payload = base64_encode(json_encode($payload));

    $signature = hash_hmac(
        'sha256',
        "$header.$payload",
        JWT_SECRET,
        true
    );

    return "$header.$payload." . base64_encode($signature);
}

function validarJWT(string $jwt) {
    [$header, $payload, $signature] = explode('.', $jwt);

    $valid = base64_encode(hash_hmac(
        'sha256',
        "$header.$payload",
        JWT_SECRET,
        true
    ));

    if ($valid !== $signature) return false;

    $data = json_decode(base64_decode($payload), true);
    if ($data['exp'] < time()) return false;

    return $data;
}
