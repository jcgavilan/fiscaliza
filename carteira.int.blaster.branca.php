<?php

// PRIMEIRO, RECEBE AS VARIÁVEIS
$nome  = $f->limpa_variavel($_POST['nome'], 190, $purifier);
$filiacao_pai  = $f->limpa_variavel($_POST['filiacao_pai'], 190, $purifier);
$filiacao_mae  = $f->limpa_variavel($_POST['filiacao_mae'], 190, $purifier);
$data_nascimento  = $f->limpa_variavel($_POST['data'], 190, $purifier);
$cpf_print  = $f->limpa_variavel($_POST['cpf'], 15, $purifier);
$cpf = str_replace('.', '', $cpf_print);
$cpf = str_replace('-', '', $cpf);
$logradouro  = $f->limpa_variavel($_POST['logradouro'], 190, $purifier);
$bairro  = $f->limpa_variavel($_POST['bairro'], 190, $purifier);
$sgpe  = $f->limpa_variavel($_POST['sgpe'], 190, $purifier);
$numero  = $f->limpa_variavel($_POST['numero'], 190, $purifier);
$cep  = $f->limpa_variavel($_POST['cep'], 190, $purifier);
$id_municipio = (int)$_POST['id_municipio'];
$municipio_nome  = $f->limpa_variavel($_POST['municipio_nome'], 80, $purifier);
$uf  = $f->limpa_variavel($_POST['uf'], 3, $purifier);
$municipio_txt = $municipio_nome."/".$uf;

$contato  = $f->limpa_variavel($_POST['contato'], 190, $purifier);
$email  = $f->limpa_variavel($_POST['email'], 190, $purifier);
$registro_exercito  = $f->limpa_variavel($_POST['registro_exercito'], 190, $purifier);
$numero_registro  = $f->limpa_variavel($_POST['numero_registro'], 190, $purifier);
$nome_empresa_curso  = $f->limpa_variavel($_POST['nome_empresa_curso'], 190, $purifier);
$cnpj_empresa_curso  = $f->limpa_variavel($_POST['cnpj_empresa_curso'], 190, $purifier);
$categoria_curso  = $f->limpa_variavel($_POST['categoria_curso'], 190, $purifier);
$nome_empresa_empregadora  = $f->limpa_variavel($_POST['nome_empresa_empregadora'], 190, $purifier);
$cnpj_empresa_empregadora  = $f->limpa_variavel($_POST['cnpj_empresa_empregadora'], 190, $purifier);
$responsavel_legal  = $f->limpa_variavel($_POST['responsavel_legal'], 190, $purifier);
$logradouro_empresa_empregadora  = $f->limpa_variavel($_POST['logradouro_empresa_empregadora'], 190, $purifier);
$bairro_empresa_empregadora  = $f->limpa_variavel($_POST['bairro_empresa_empregadora'], 190, $purifier);
$numero_empresa_empregadora  = $f->limpa_variavel($_POST['numero_empresa_empregadora'], 190, $purifier);
$cep_empresa_empregadora  = $f->limpa_variavel($_POST['cep_empresa_empregadora'], 190, $purifier);
$id_municipio_empresa_empregadora = (int)$_POST['id_municipio_empresa_empregadora'];

$municipio_nome_empresa_empregadora  = $f->limpa_variavel($_POST['municipio_nome_empresa_empregadora'], 80, $purifier);
$uf_empresa_empregadora  = $f->limpa_variavel($_POST['uf_empresa_empregadora'], 3, $purifier);
$municipio_txt_empresa_empregadora = $municipio_nome_empresa_empregadora."/".$uf_empresa_empregadora;

$contato_empresa_empregadora  = $f->limpa_variavel($_POST['contato_empresa_empregadora'], 190, $purifier);
$email_empresa_empregadora  = $f->limpa_variavel($_POST['email_empresa_empregadora'], 190, $purifier);
$registro_exercito_empresa_empregadora  = $f->limpa_variavel($_POST['registro_exercito_empresa_empregadora'], 190, $purifier);
$numero_registro_empresa_empregadora  = $f->limpa_variavel($_POST['numero_registro_empresa_empregadora'], 190, $purifier);


$data_expedicao = time();
$validade_gape = 2; // a ser ajustado caso a caso. Aqui considerei validade de 5 anos
$ano = 60*60*24*365;
$gape = $validade_gape*$ano;
$data_validade = $data_expedicao+$gape;

$query = "select numero from tb_carteiras where tipo = 'blaster' AND ano = ".date('Y')." order by numero desc"; // busca os dados do último cadastro para identificar este.
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    if ($num == 0) {
        $numero = 1;
    }else{
        $row = mysqli_fetch_array($result);
        $numero = $row[numero];
        $numero++;
    }

$query = "select ibge_reduzido, nome from tb_municipios_ibge where ibge_reduzido = $id_municipio or ibge_reduzido = $id_municipio_empresa_empregadora";
$result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $ibge = $row[ibge_reduzido];
                if ($ibge == $id_municipio) {
                    $nome_municipio = stripslashes($row[nome]);
                }
                if ($ibge == $id_municipio_empresa_empregadora) {
                    $nome_municipio_empresa_empregadora = stripslashes($row[nome]);
                }           
            }

//   primeiro, faz a geração do documento do requerimento:
if ($registro_exercito == 1) {
    $registro_exercito = "SIM";
}else{
    $registro_exercito = "NÃO";
}

if ($registro_exercito_empresa_empregadora == 1) {
    $registro_exercito_empresa_empregadora = "SIM";
}else{
    $registro_exercito_empresa_empregadora = "NÃO";
}

include "html_base/requerimento.carteira.blaster.php";

$str_base = "abcdefghijklmnopqrstuvxywz";
    $str_meio = "";
    for($i=0;$i<4;$i++)
    {
        $str_prov = str_shuffle($str_base);
        $str_meio .= substr($str_prov, 0, 1);
    }

    $data_hora=time();

    $prov = $str_meio.$data_hora;

    $nome_arquivo = $prov.".html";
    $myfile = fopen("html_descartavel/$nome_arquivo", "w") or die("erro na abertura do arquivo");
    
    fwrite($myfile, $html);
    fclose($myfile);

    $nome_final = $prov.".pdf";

    $output = exec("python3 html2pdf.py html_descartavel/$nome_arquivo carteiras/$nome_final 2>&1");

    if(!$output)
    {
        echo "FALHA  python3 html_descartavel/$nome_arquivo carteiras/$nome_final";
    }

   // echo "<br><a href = 'carteiras/$nome_final' target = '_blank'>BAIXAR REQUERIMENTO</a>";

    // faz a inclusão na tabela principal

    $query_array = array();

    $cnpj_empresa_empregadora  = str_replace(".", "", $cnpj_empresa_empregadora);
    $cnpj_empresa_empregadora  = str_replace("-", "", $cnpj_empresa_empregadora);
    $cnpj_empresa_empregadora  = str_replace("/", "", $cnpj_empresa_empregadora);

    $query_array = [
        'tipo' => 'blaster',
        'numero' => $numero,
        'ano' => date('Y'),
        'sgpe' => $sgpe,
        'nome' => $nome,
        'cpf' => $cpf,
        'rg' => $rg,
        'filiacao_pai' => $filiacao_pai,
        'filiacao_mae' => $filiacao_mae,
        'empresa' => $nome_empresa_empregadora,
        'capacitacao' => '',
        'categoria' => $categoria_curso,
        'data_expedicao' => $data_expedicao,
        'data_validade' => $data_validade,
        'policial_cpf' => $_SESSION['usuario_fis_cpf'],
        'policial_nome' => $_SESSION['usuario_fis_nome'],
        'municipio_txt' => $municipio_txt,
       // 'id_municipio' => $id_municipio,
        'blaster_empresa_nome' => $nome_empresa_empregadora,
        'blaster_empresa_cnpj' => $cnpj_empresa_empregadora,
        'delegado_rejeicao_justificativa' => ' '

    ];
   
 
    function query_insere($tb, $v){

        $k = array_keys($v);
        $str_keys = '';
        $str_values = '';
        
            for ($i=0; $i < count($k); $i++) { 
                if($i != 0)
                    {$str_keys .= ", ";}
                $str_keys .= $k[$i];
                $chave = $k[$i];
        
                if($i != 0)
                    {$str_values .= ", ";}
                $str_values .= "'".$v[$chave]."'";
            }
        
             $query = "insert into $tb ($str_keys) values ($str_values)";
             return $query;
        
        }

        $query = query_insere('tb_carteiras', $query_array);
        $result=mysqli_query($link, $query);
        if(!$result){
            echo "<h1>ERRO -> $query</h1>".mysqli_error($link);
        }else{
            $id_carteira = mysqli_insert_id($link);
        }

        // FAZ A INCLUSÃO DOS DADOS COMPLEMENTARES

        $query_array = [
            'id_carteira' => $id_carteira,
            'data_nascimento' => $data_nascimento,
            'logradouro' => $logradouro,
            'bairro' => $bairro,
            'numero' => $numero,
            'cep' => $cep,
            'contato' => $contato,
            'email' => $email,
            'registro_exercito' => $registro_exercito,
            'numero_registro' => $numero_registro,
            'nome_empresa_curso' => $nome_empresa_curso,
            'cnpj_empresa_curso' => $cnpj_empresa_curso,
            'nome_empresa_empregadora' => $nome_empresa_empregadora,
            'cnpj_empresa_empregadora' => $cnpj_empresa_empregadora,
            'responsavel_legal' => $responsavel_legal,
            'logradouro_empresa_empregadora' => $logradouro_empresa_empregadora,
            'bairro_empresa_empregadora' => $bairro_empresa_empregadora,
            'numero_empresa_empregadora' => $numero_empresa_empregadora,
            'cep_empresa_empregadora' => $cep_empresa_empregadora,
           // 'id_municipio_empresa_empregadora' => $id_municipio_empresa_empregadora,
            'municipio_txt_empresa_empregadora' => $municipio_txt_empresa_empregadora,
            'contato_empresa_empregadora' => $contato_empresa_empregadora,
            'email_empresa_empregadora' => $email_empresa_empregadora,
            'registro_exercito_empresa_empregadora' => $registro_exercito_empresa_empregadora,
            'numero_registro_empresa_empregadora' => $numero_registro_empresa_empregadora
 
        ];

        $query2 = query_insere('tb_carteiras_complemento', $query_array);
        $result2 = mysqli_query($link, $query2);
        if(!$result2){
            echo "<h1>ERRO -> $query2</h1>";
        }



        // FAZ A INCLUSÃO DO REQUERIMENTO, PARA APRESENTAR NO PAINEL
        $query_array = [
            'id_carteira' => $id_carteira,
            'cpf_policial' => $_SESSION['usuario_fis_cpf'],
            'data_carregamento' => time(),
            'id_documento_tipo' => 8,
            'arquivo' => $nome_final
        ];

        
        $query2 = query_insere('tb_carteiras_arquivos', $query_array);
        $result2 = mysqli_query($link, $query2);
        if(!$result2){
            echo "<h1>ERRO -> $query2</h1>";
        }

        // após o recebimento do requerimento, procede com carregamento do arquivo
        $id_documento = 17; // PARA CARTEIRA DE BLASTER

        $query = "select id_documento_tipo from tb_documentos_requeridos where id_alvara = $id_documento";
        $array_documento_tipo_base = array();
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        //echo "<h2>$query --> $num</h2>";
        for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $id_documento_tipo = $row[id_documento_tipo];
           

            if (isset($_FILES["arquivo_$id_documento_tipo"]["name"])) {
                // echo " ==>".$id_documento_tipo;
                 $arquivo = $_FILES["arquivo_$id_documento_tipo"]["tmp_name"];
                 $arquivo_nome = $documentos_nome[$id_documento_tipo];//$_FILES["arquivo_$i"]["name"];
                 //   echo "<hr>2";
                 $d1 = $_FILES["arquivo_$id_documento_tipo"]["type"];
                 $d2 = explode("/", $d1);
                 $extensao = $d2[1];
         
                     if($extensao == 'pdf'){
                        $base = "abcdefghijklmnopqrstuvwxyz23456789abcdefghijklmnopqrstuvwxyz675423289abcdefghijklmnopqrstuvwxyz9587694abcdefghijklmnopqrstuvwxyz";
                        $a =  str_shuffle($base);
                        $str = substr($a, 0, 40);
                        $nome_final = $str.".pdf";
         
                         $destino = "carteiras/".$nome_final;
         
                         if(!move_uploaded_file($arquivo, $destino)){
                         echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["arquivo"]["tmp_name"];
                         }else{
         
                          //   $id_documento_tipo_insere = (int)$_POST["id_documento_tipo_ref_$i"];
                          $query_array = [
                            'id_carteira' => $id_carteira,
                            'cpf_policial' => $_SESSION['usuario_fis_cpf'],
                            'data_carregamento' => time(),
                            'id_documento_tipo' => $id_documento_tipo,
                            'arquivo' => $nome_final
                        ];
         
                        
                        $query2 = query_insere('tb_carteiras_arquivos', $query_array);
                           //  $query2 = "insert into tb_carteira_arquivos (id_carteira, cpf_policial, data_carregamento, id_documento_tipo, arquivo) values ($id_pedido, '".$_SESSION['usuario_fis_cpf']."', ".time().", '$id_documento_tipo', '$nome_final')";
                          //   echo "<hr>$i -> $query2";
                             $result2 = mysqli_query($link, $query2);
                             if(!$result2){
                                 echo "<h1>ERRO -> $query2</h1>";
                             }
                         }
                     }else{
                         echo " <div class='alert alert-danger' role='alert'>";
                         echo "<h2>DOCUMENTO NÃO ESTÁ EM FORMATO PDF</h2>";
                         echo "<h1>INCLUSÃO NEGADA</h1>";
                         echo "</div>"; 
                     }
                 }
            }

// Dados a serem enviados no corpo da requisição
$data = json_encode([
    "login" => "sistemaCarteirinhas",
    "senha" => "016da00846b7ac38"
]);

// Inicializa o cURL
$ch = curl_init();

// URL do servidor
$url = "https://sistemas.pc.sc.gov.br/carteirinhas_fiscaliza/login"; // Substitua pela URL correta

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
  //  echo "token: " . $responseData['token'] . "\n";
 //   echo "Token: " . $responseData['cookies']['token'] . "\n";
}

$token = $responseData['token'];
// Fecha a conexão cURL
curl_close($ch);

// Inicializa o cURL
//$ch = curl_init();

// URL do servidor
$url = "https://sistemas.pc.sc.gov.br/carteirinhas_fiscaliza/carteirinhas/gera-carteirinha-blaster"; // Substitua pela URL correta

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_HEADER, 0); // Evita que os cabeçalhos HTTP sejam incluídos na saída
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,  // Send token in Authorization header
    'Content-Type: application/json'    // Set content type as JSON
]);

//echo "<h1>$token</h1>";

$nome_carteira = 'carteira-blaster'.time().'.pdf';//'carteira-blaster-'.$id_carteira.'-'.$data_expedicao.'.pdf';

$data_expedicao_print = date("d/m/Y", $data_expedicao);
$data_validade_print = date("d/m/Y", $data_validade);

// prepara a formatacao do cpf:
    $c = $cpf;

    $cpf_print = $c[0].$c[1].$c[2].".".$c[3].$c[4].$c[5].".".$c[6].$c[7].$c[8]."-".$c[9].$c[10];

$post = [
    'numero' => $numero."/".date('Y'),
    'nome' => $nome,
    'cpf' =>  $cpf_print,
    'empresa' => $nome_empresa_empregadora,
    'capacitacao' => $nome_empresa_curso,
    'categoria' => $categoria_curso,
    'validade' => "$data_validade_print",
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


$query = "update tb_carteiras set arquivo = '$nome_carteira' where id = $id_carteira";
$result=mysqli_query($link, $query);

//echo "<br><br><a href = 'carteiras/$nome_carteira' style = 'width:100%;' class = 'btn btn-primary' target= '_blank'>VER ARQUIVO</a>";

//echo "<br><br><a href = 'carteira.painel.php?id=$id_carteira&data_expedicao=$data_expedicao' style = 'width:100%;' class = 'btn btn-primary' target= '_blank'>IR PARA O PAINEL DA CARTEIRA</a>";


echo " <div class='alert alert-info' role='alert'>";
echo " <h4 class='alert-heading' align = 'center'>SEU PEDIDO FOI GERADO COM SUCESSO<BR><BR>E ESTÁ NA FILA DO DELEGADO PARA APROVAÇÃO</h4>";
echo "</div>";
?>