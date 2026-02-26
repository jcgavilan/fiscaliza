<?php
// Dados a serem enviados no corpo da requisição
$data = json_encode([
    "login" => "sistemaCarteirinhas",
    "senha" => "016da00846b7ac38"
]);

// Inicializa o cURL
$ch = curl_init();

// URL do servidor
$url = "http://10.121.23.194:3375/login"; // Substitua pela URL correta

// Configurações do cURL
curl_setopt($ch, CURLOPT_URL, $url); // Define a URL da requisição
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna a resposta como string
curl_setopt($ch, CURLOPT_POST, true); // Indica que é uma requisição POST
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Define o tipo de conteúdo
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Define o corpo da requisição
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Executa a requisição
$response = curl_exec($ch);

$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 //   echo "<br> 5 HTTP Status Code: " . $http_status ."<br>";


// Verifica se houve algum erro na requisição
if (curl_errno($ch)) {
    echo 'Erro: ' . curl_error($ch);
} else {
    // Converte a resposta JSON em um array PHP
    $responseData = json_decode($response, true);
   
    // Exibe a resposta
    echo "token: " . $responseData['token'] . "\n";
 //   echo "Token: " . $responseData['cookies']['token'] . "\n";
}

$token = $responseData['token'];
// Fecha a conexão cURL
curl_close($ch);

// Inicializa o cURL
$ch = curl_init();

// URL do servidor
$url = "http://10.121.23.194:3375/carteirinhas/gera-carteirinha-blaster"; // Substitua pela URL correta

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_HEADER, 0); // Evita que os cabeçalhos HTTP sejam incluídos na saída
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,  // Send token in Authorization header
    'Content-Type: application/json'    // Set content type as JSON
]);

echo "<h1>$token</h1>";

$nome_carteira = 'carteira-blaster'.time().'.pdf';//'carteira-blaster-'.$id_carteira.'-'.$data_expedicao.'.pdf';

$data_expedicao_print = date("d/m/Y", $data_expedicao);
$data_validade_print = date("d/m/Y", $data_validade);

$post = [
    'numero' => '3453/2024',//$numero."/".date('Y'),
    'nome' => 'MARCUS TESTE',//$nome,
    'cpf' => '000.999.999-54',// $cpf,
    'empresa' => 'Empresa teste',//$nome_empresa_empregadora,
    'capacitacao' => 'Capacita LTDA',//$nome_empresa_curso,
    'categoria' => 'Máster Bláster',//$categoria_curso,
    'validade' => '30/09/2029',//"$data_validade_print",
    'link_qr' => "https://sistemas.pc.sc.gov.br/fiscaliza/carteiras/$nome_carteira"
];
    //'link_qr' => "https://pc.sc.gov.br/wp-content/uploads/2024/02/Taxas-2024-2.pdf"
 // $data = json_encode($post);

//curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post));  // Send the data as JSON

$response = curl_exec($curl);

// Verifique se houve um erro durante a execução da requisição
if (curl_errno($curl)) {
    echo 'Erro ao enviar requisição: ' . curl_error($curl);
    exit;
}

// Verifique se a resposta contém um código de sucesso (200)
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($http_code != 200) {
    echo 'Erro na requisição. Código de status: ' . $http_code;
    exit;
}

// Salve a resposta como um arquivo PDF
$file_path = 'carteiras/'.$nome_carteira;
file_put_contents($file_path, $response);

curl_close($curl);

echo "<br><br><a href = 'carteiras/$nome_carteira' style = 'width:100%;' class = 'btn btn-primary' target= '_blank'>VER ARQUIVO</a>";

?>