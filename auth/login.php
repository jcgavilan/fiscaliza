<?php
// require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/jwt.php';

$input = json_decode(file_get_contents("php://input"), true);

$email = $input['login'] ?? null;
$senha = $input['password'] ?? null;

$sql = "SELECT id, senha FROM tb_cidadao_cadastro WHERE requerente_email = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);


// echo json_encode([
//     'volta' => $email,
//     'senha' => $senha,
//     'sql' => $sql,
//     'usuario' => $user['senha']
// ]);



if (!$user || !password_verify($senha, $user['senha'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Credenciais inválidas']);
    exit;
}

$token = gerarJWT([
    'id' => $user['id'],
    'email' => $email
]);

echo json_encode([
    'ok' => true,
    'token' => $token
]);
