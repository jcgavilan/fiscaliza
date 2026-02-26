<?php

// Cabeçalhos CORS
header("Access-Control-Allow-Origin: http://localhost:9000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// Trata o preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}




// Conexão com o banco
include "conexao.php";



// Recebendo JSON (Axios)
$input = json_decode(file_get_contents("php://input"), true);

// Fallback para form-data
$nome = $input['fullName'] ?? $_POST['fullName'] ?? null;

echo json_encode([
    'status' => 'ok',
    'nome'   => $nome
]);


echo "nome = ".$nome;

?>