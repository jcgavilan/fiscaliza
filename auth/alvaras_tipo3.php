<?php
// require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/jwt.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT id , nome , id_categoria , mensagem  FROM tb_alvaras_tipo WHERE id_categoria = 3 ORDER BY nome";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
// $user = mysqli_fetch_assoc($result);


// $token = gerarJWT([
//     'id' => $user['id'],
//     'email' => $email
// ]);


$cnae = [];

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

        $cnae[] = [
            'id'    => (int) $row['id'],
            'nome'  => $row['nome'],
            'idcategoria'    => (int) $row['id_categoria'],
            'mensagem' => $row['mensagem']
        ];



    }
}

echo json_encode($cnae, JSON_UNESCAPED_UNICODE);


