<?php
/*
$url = "http://10.121.23.194:3000/gera-carteirinha";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$post = [
    'nome' => "Marcus Vinicius Lopes", 
    'cargo' => "Escrivão de Polícia Civil", 
    'matricula' => "666.666-1.6", 
    'cpf' => "000.111.222-33", 
    'sinarm' => "3300/23232323-33",
    'especie' => "Pistola",
    'marca' => "Glock", 
    'modelo' => "g-19",
    'numero_do_registro' => "333666",
    'numero_da_arma' => "94494949",
    'calibre' => "9 mm",
    'data_expedicao' => "01/01/2020",
    'validade' => "01/01/2030",
    'link_qr' => "https://pc.sc.gov.br/wp-content/uploads/2024/02/Taxas-2024-2.pdf"
];

$data =  json_encode($post);
  
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

$resp = curl_exec($curl);
curl_close($curl);
echo "so far, so good.";
*/

$url = "http://10.121.23.194:3000/gera-carteirinha";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, 0); // Evita que os cabeçalhos HTTP sejam incluídos na saída

$headers = array(
    "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$post = [
    'nome' => "Marcus Vinicius Lopes",
    'cargo' => "Escrivão de Polícia Civil",
    'matricula' => "666.666-1.6",
    'cpf' => "000.111.222-33",
    'sinarm' => "3300/23232323-33",
    'especie' => "Pistola",
    'marca' => "Glock",
    'modelo' => "g-19",
    'numero_do_registro' => "333666",
    'numero_da_arma' => "94494949",
    'calibre' => "9 mm",
    'data_expedicao' => "01/01/2020",
    'validade' => "01/01/2030",
    'link_qr' => "https://pc.sc.gov.br/wp-content/uploads/2024/02/Taxas-2024-2.pdf"
];

$data = json_encode($post);

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

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
$file_path = 'carteiras/arquivo.pdf';
file_put_contents($file_path, $response);

// Feche a sessão cURL
curl_close($curl);

echo "Arquivo PDF recebido e salvo com sucesso em: <a href = '$file_path'>$file_path</a>";
?>
