<?php
require_once '../config/database.php';
require_once '../config/jwt.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT id , id_alvara , cnae, descricao , taxa_valor FROM tb_cnaes where id_alvara = 1";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$dados = [];

if($result->num_rows > 0){
    while ($row = $result->fetch_assoc()) {

        $dados[] = [
            'id'        => $row['id'],
            'id_alvara' => $row['id_alvara'],
            'cnae'      => $row['cnae'],
            'descricao' => $row['descricao'],
            'taxa'      => $row['taxa_valor']
        ];



    }
}

echo json_encode($dados, JSON_UNESCAPED_UNICODE);


