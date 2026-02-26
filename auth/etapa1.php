<?php
require_once '../config/database.php';
require_once '../config/jwt.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT nome, mensagem, possui_ramo_atividade, requer_vistoria FROM tb_alvaras_tipo WHERE id = 1";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$dados = [];

if($result->num_rows > 0){
    while ($row = $result->fetch_assoc()) {

        $dados[] = [
            'nome'      => $row['nome'],
            'mensagem'  => $row['mensagem'],
            'ramo'      => $row['possui_ramo_atividade'],
            'vistoria'      => $row['requer_vistoria']
        ];



    }
}

echo json_encode($dados, JSON_UNESCAPED_UNICODE);


