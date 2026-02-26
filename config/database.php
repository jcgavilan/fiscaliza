<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// conexao.php
//
// conexao com a base de dados FISCALIZA2
// OBS. sem tratamento para UTF-8
//
// data: 21/01/2026
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// header('Content-Type: text/html; charset=utf-8');


// $conexao = mysqli_connect("localhost","root","") or die ("Erro na conexão com a base de dados");


// //$conexao = mysqli_connect("julio-mysql:3306", "demandas", "MRZaia50*") or die("Erro na conexão com a base de dados");




// $bd = mysqli_select_db($conexao,"fiscaliza2") or trigger_error(mysqli_error($conexao));

// if (mysqli_connect_errno()){
  
//   echo "falha em conectar com " .mysqli_connect_error();
 
// }


$conexao = mysqli_connect("localhost","root","","fiscaliza2");

if (!$conexao) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro de conexão com banco']);
    exit;
}

mysqli_set_charset($conexao, 'utf8mb4');
