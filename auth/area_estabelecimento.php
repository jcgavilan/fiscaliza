<?php
// require_once '../config/cors.php';
require_once '../config/database.php';
require_once '../config/jwt.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT id , area, valor FROM tb_area_vistoria ";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
// $user = mysqli_fetch_assoc($result);


// $token = gerarJWT([
//     'id' => $user['id'],
//     'email' => $email
// ]);


$areas = [];

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

        $areas[] = [
            'id'    => (int) $row['id'],
            'area'  => $row['area'],
            'valor' => (float) $row['valor'],
        ];



    }
}

echo json_encode($areas, JSON_UNESCAPED_UNICODE);

// echo json_encode([
//     'ok' => true,
//     'token' => $token
// ]);


// <select id="" name="" class="" onchange="" title=""> 


// Gerar as opções da classe com base no resultado da consulta
    // $options = '<option value="0">Selecione a Área de Vistoria</option>';
    
 
    // if($result->num_rows > 0){
    //     while ($row = $result->fetch_assoc()) {
    //         $id = $row['id'];
    //         $area = $row['area'];
    //         $valor = $row['valor'];
    //         $options .= '<option value="'.$id.'">'.$id.' - '.$area.' - '.$valor.'</option>';
    //     }
    // }
    // echo $options;


// <!-- </select> -->
