<?php
// require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/jwt.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT id,nome FROM tb_municipios_ibge";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
// $user = mysqli_fetch_assoc($result);


// $token = gerarJWT([
//     'id' => $user['id'],
//     'email' => $email
// ]);


$municipio = [];

if($result->num_rows > 0){
    while ($row = $result->fetch_assoc()) {
        // $id = $row['id'];
        // $area = $row['area'];
        // $valor = $row['valor'];

        // echo json_encode([
        //     'id'    => $id,
        //     'area'  => $area,
        //     'valor' => $valor
        // ]);

        $municipio[] = [
            'id'    => (int) $row['id'],
            'municipio'  => $row['nome'],
        ];



    }
}

echo json_encode($municipio, JSON_UNESCAPED_UNICODE);


