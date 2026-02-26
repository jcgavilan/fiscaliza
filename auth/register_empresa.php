<?php
require_once '../config/database.php';
require_once '../config/jwt.php';

$input = json_decode(file_get_contents("php://input"), true);


//do requerente

$cpfRequerente  = $input['fullName'] ?? null; 
$nomeRequerente = $input['cpf'] ?? null; 

//do formulario

$email          = $input['emailEmpresa'] ?? null; 
$reemail        = $input['confirmEmailEmpresa'] ?? null; 
$fantasia       = $input['fantasyName'] ?? null;
$razao          = $input['legalName'] ?? null;
$cnpj           = $input['cnpj'] ?? null;
$recnpj         = $input['confirmCnpj'] ?? null;
$ramoAtividade  = $input['activity'] ?? null;
$endereco       = $input['addressStreet'] ?? null; 
$numero         = $input['addressNumber'] ?? null; 
$bairro         = $input['addressNeighborhood'] ?? null;
$cep            = $input['addressZip'] ?? null;
$municipio      = $input['addressCity'] ?? null; //este precisa ser o id do municipio
$telefoneFixo   = $input['phone'] ?? null;
$telefoneCelular = $input['mobile'] ?? null;
$responsavel    = $input['legalResponsible'] ?? null;  //nome_proprietario
$area           = $input['area'] ?? null; // este tem q ser o id area de vistoria


//vou obter para cadastrar com os demais dados:

//1 data do pedido inteiro
$timestamp = time();

//2 tipo pedido (tipo de alvará solicitado)

//3 status ver a regra de dados

//4 status do cidaddão

//5 data da ultima modificação 
$timestamp = time();



echo "<input type='hidden' name='nome_proprietario' value='$email'>";
echo "<input type='hidden' name='obs_cidadao' value='$reemail'>";
echo "<input type='hidden' name='regras_txt' value='$fantasia'>";
echo "<input type='hidden' name='id_documento' value='$razao'>";
echo "<input type='hidden' name='id_area_vistoria' value='$cnpj'>";
echo "<input type='hidden' name='nome_proprietario' value='$ramoAtividade'>";
echo "<input type='hidden' name='obs_cidadao' value='$endereco'>";
echo "<input type='hidden' name='regras_txt' value='$numero'>";
echo "<input type='hidden' name='id_documento' value='$bairro'>";
echo "<input type='hidden' name='id_area_vistoria' value='$municipio'>";
echo "<input type='hidden' name='obs_cidadao' value='$telefoneFixo'>";
echo "<input type='hidden' name='regras_txt' value='$telefoneCelular'>";
echo "<input type='hidden' name='id_documento' value='$responsavel'>";
echo "<input type='hidden' name='id_area_vistoria' value='$area'>";



// $sql = "INSERT INTO tb_cidadao_cadastro
//         (requerente_nome, requerente_cpf, requerente_data_nasc, requerente_email,  requerente_endereco, requerente_telefone, senha, flag,dtinsercao)
//         VALUES (?, ?, ?, ?, ?, ? ,? ,? ,?)";

// $stmt = mysqli_prepare($conexao, $sql);
// mysqli_stmt_bind_param($stmt, 'sssssssis', $nome, $cpf, $dataNasc, $email,  $address, $phones, $senha, $flag, $dtInsercao);

// if (!mysqli_stmt_execute($stmt)) {
//     http_response_code(500);
//     echo json_encode(['error' => 'Erro ao cadastrar']);
//     exit;
// }

// $id = mysqli_insert_id($conexao);

// $token = gerarJWT([
//     'id' => $id,
//     'email' => $email
// ]);

// // echo json_encode([
// //     'ok' => true,
// //     'token' => $token
// // ]);


// echo json_encode([
//     'ok' => true,
//     'id' => $id,
//     'return' => http_response_code(201)
// ]);


// header("Access-Control-Allow-Origin: http://localhost:9000");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");
// header("Access-Control-Allow-Credentials: true");