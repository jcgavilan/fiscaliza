<?php
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Origin: http://localhost:9000");
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(200);
//     exit;
// }
// require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/jwt.php';

$input = json_decode(file_get_contents("php://input"), true);

$nome     = $input['fullName'] ?? null;
$cpf      = $input['cpf'] ?? null;
// $cpf      = preg_replace('/\D/', '', $input['cpf'] ?? '');
$dataNasc = $input['birthDate'] ?? null;
$email    = $input['email'] ?? null;
$address    = $input['address'] ?? null;
$phones    = $input['phones'] ?? null;
$senha    = password_hash($input['password'], PASSWORD_DEFAULT);
$flag     = !empty($input['isOwner']) ? 1 : 0;
$dtInsercao    = $input['dtInsercao'] ?? null;

$sql = "INSERT INTO tb_cidadao_cadastro
        (requerente_nome, requerente_cpf, requerente_data_nasc, requerente_email,  requerente_endereco, requerente_telefone, senha, flag,dtinsercao)
        VALUES (?, ?, ?, ?, ?, ? ,? ,? ,?)";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 'sssssssis', $nome, $cpf, $dataNasc, $email,  $address, $phones, $senha, $flag, $dtInsercao);

if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao cadastrar']);
    exit;
}

$id = mysqli_insert_id($conexao);

$token = gerarJWT([
    'id' => $id,
    'email' => $email
]);

// echo json_encode([
//     'ok' => true,
//     'token' => $token
// ]);


echo json_encode([
    'ok' => true,
    'id' => $id,
    'return' => http_response_code(201)
]);


// header("Access-Control-Allow-Origin: http://localhost:9000");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");
// header("Access-Control-Allow-Credentials: true");