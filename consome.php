<?php

// Cabeçalhos CORS
// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json");



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

// recuperar dados enviado por POST
// $fullName =  $_POST['fullName'];     //
// $birthDate =  $_POST['birthDate'];          //
// $cpf =  $_POST['cpf'];
// $address =  $_POST['address'];
// $phones =  $_POST['phones'];
// $email =  $_POST['email'];    //
// $isOwner =  $_POST['isOwner'];        //
// $notes =  $_POST['notes'];
// $password =  $_POST['password'];


// echo "nome".$fullName;
// echo "<br>niver".$birthDate;
// echo "<br>cpf".$cpf;
// echo "<br>address".$address;
// echo "<br>phones".$phones;


// $nome = $_POST['name'];

// Recebendo JSON (Axios)
$input = json_decode(file_get_contents("php://input"), true);


// Fallback para form-data
$fullName = $input['fullName'] ?? $_POST['fullName'] ?? null;
$birthDate = $input['birthDate'] ?? $_POST['birthDate'] ?? null;

//conversão da data par número inteiro (este processo não é necessário para esta variável)
// $timestamp =  strtotime($birthDate);

//converte de volta
// echo date('d/m/Y', $data_int);

$cpf = $input['cpf'] ?? $_POST['cpf'] ?? null;

//retirar máscara do cpf (não é necessário retirar a máscara)
// $cpfLimpo = preg_replace('/\D/', '', $cpf);

$address = $input['address'] ?? $_POST['address'] ?? null;
$phones = $input['phones'] ?? $_POST['phones'] ?? null;
$email = $input['email'] ?? $_POST['email'] ?? null;
$isOwner = $input['isOwner'] ?? $_POST['isOwner'] ?? null;

//tratamento da variável isOwner

if ($isOwner){
    $flag = 1;
}else{
    $flag = 0;
}
$notes = $input['notes'] ?? $_POST['notes'] ?? null;
$password = $input['password'] ?? $_POST['password'] ?? null;


// echo json_encode([
//     'status' => 'ok'
// ]);


// echo "nome = ".$fullName;
// echo "<br> data = ".$birthDate;
// echo "<br>cpf = ".$cpf;
// echo "<br>endereço = ".$address;
// echo "<br>celular = ".$phones;
// echo "<br>email = ".$email;
// echo "<br>proprietário = ".$isOwner;
// echo "<br> obs = ".$notes;
// echo "<br> senha = ".$password;

$sqlInsert = "INSERT INTO tb_cidadao_cadastro(id, requerente_nome, requerente_cpf, requerente_data_nasc, requerente_endereco, requerente_telefone, requerente_email, senha, flag, obs) 
                VALUES (NULL,'$fullName','$cpf','$birthDate','$address','$phones','$email','$password','$flag','$notes')";



$result = mysqli_query($conexao, $sqlInsert);

if(!$result){
    echo json_encode([
                        'sucesso' => '1'
                    ]);
}else{
    echo json_encode([
                        'sucesso' => '0'
                    ]);
 
}
?>