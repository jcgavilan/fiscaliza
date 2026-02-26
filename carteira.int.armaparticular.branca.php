<?php

// PRIMEIRO, RECEBE AS VARIÁVEIS
$nome  = $f->limpa_variavel($_POST['nome'], 190, $purifier);
$data_nascimento  = $f->limpa_variavel($_POST['data'], 190, $purifier);
$contato_policial  = $f->limpa_variavel($_POST['contato_policial'], 190, $purifier);
$unidade  = $f->limpa_variavel($_POST['unidade'], 190, $purifier);
$id_municipio = (int)$_POST['id_municipio'];
$cpf_print  = $f->limpa_variavel($_POST['cpf'], 15, $purifier);
$cpf = str_replace('.', '', $cpf_print);
$cpf = str_replace('-', '', $cpf);
$matricula  = $f->limpa_variavel($_POST['matricula'], 190, $purifier);
$cargo  = $f->limpa_variavel($_POST['cargo'], 190, $purifier);
$cep  = $f->limpa_variavel($_POST['cep'], 190, $purifier);
$email  = $f->limpa_variavel($_POST['email'], 190, $purifier);
$subordinado  = $f->limpa_variavel($_POST['subordinado'], 90, $purifier);



$sgpe  = $f->limpa_variavel($_POST['sgpe'], 190, $purifier);

$arma_especie  = $f->limpa_variavel($_POST['arma_especie'], 190, $purifier);
$arma_modelo  = $f->limpa_variavel($_POST['arma_modelo'], 190, $purifier);
$arma_registro  = $f->limpa_variavel($_POST['arma_registro'], 190, $purifier);
$arma_marca  = $f->limpa_variavel($_POST['arma_marca'], 190, $purifier);
$arma_calibre  = $f->limpa_variavel($_POST['arma_calibre'], 190, $purifier);
$arma_numero_serie  = $f->limpa_variavel($_POST['arma_numero_serie'], 190, $purifier);

$arma_numero_serie  = $f->limpa_variavel($_POST['arma_numero_serie'], 190, $purifier);
$arma_numero_sinarm  = $f->limpa_variavel($_POST['arma_numero_sinarm'], 190, $purifier);
$arma_numero_sigma  = $f->limpa_variavel($_POST['arma_numero_sigma'], 190, $purifier);

$data_expedicao = time();
// $validade_gape = 5; // a ser ajustado caso a caso. Aqui considerei validade de 5 anos
// $ano = 60*60*24*365;
// $gape = $validade_gape*$ano;
// $data_validade = $data_expedicao+$gape;

$data_validade_print = $_POST['data_validade'];
$dv = explode("/", $data_validade_print);
$dia = $dv[0];
$mes = $dv[1];
$ano = $dv[2];

$data_validade = mktime(0, 0, 0, $mes, $dia, $ano);

$validade_indeterminada = (int)$_POST['validade_indeterminada'];

if ($validade_indeterminada == 1) {
    $validade_indeterminada_inserir = 'INDETERMINADA'; // PARA GRAVAÇÃO NO BD
    $data_validade = 0;
    $data_validade_print = 'INDETERMINADA'; // PARA IMPRESSÃO DA CARTEIRA.
}

$query = "select numero from tb_carteiras where tipo = 'arma_particular' AND ano = ".date('Y')." order by numero desc"; // busca os dados do último cadastro para identificar este.
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    if ($num == 0) {
        $numero = 1;
    }else{
        $row = mysqli_fetch_array($result);
        $numero = $row[numero];
        $numero++;
    }


$query = "select ibge_reduzido, nome from tb_municipios_ibge where ibge_reduzido = $id_municipio";
$result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $ibge = $row[ibge_reduzido];
                if ($ibge == $id_municipio) {
                    $nome_municipio = stripslashes($row[nome]);
                }
            }



include "html_base/requerimento.carteira.armaparticular.php";

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

    //echo "<br><a href = 'carteiras/$nome_final' target = '_blank'>BAIXAR REQUERIMENTO</a>";

    // faz a inclusão na tabela principal

    $query_array = array();

    
    $query_array = [
        'tipo' => 'arma_particular',
        'numero' => $numero,
        'ano' => date('Y'),
        'sgpe' => $sgpe,
        'nome' => $nome,
        'cpf' => $cpf,
        'rg' => $rg,
        'matricula' => $matricula,
        'cargo' => $cargo,
        'empresa' => $nome_empresa_empregadora,
        'data_expedicao' => $data_expedicao,
        'data_validade' => $data_validade,
        'validade_txt' => $validade_indeterminada_inserir,
        'policial_cpf' => $_SESSION['usuario_fis_cpf'],
        'policial_nome' => $_SESSION['usuario_fis_nome'],
        'id_municipio' => $id_municipio,
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
            'subordinado' => $subordinado,            
            'arma_especie' => $arma_especie,
            'arma_modelo' => $arma_modelo,
            'arma_registro' => $arma_registro,
            'arma_marca' => $arma_marca,
            'arma_calibre' => $arma_calibre,
            'arma_numero_serie' => $arma_numero_serie,
            'arma_numero_sinarm' => $arma_numero_sinarm,
            'arma_numero_sigma' => $arma_numero_sigma
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
           //  $query2 = "insert into tb_carteira_arquivos (id_carteira, cpf_policial, data_carregamento, id_documento_tipo, arquivo) values ($id_pedido, '".$_SESSION['usuario_fis_cpf']."', ".time().", '$id_documento_tipo', '$nome_final')";
          //   echo "<hr>$i -> $query2";
             $result2 = mysqli_query($link, $query2);
             if(!$result2){
                 echo "<h1>ERRO -> $query2</h1>";
             }

        // após o recebimento do requerimento, procede com carregamento do arquivo
        $id_documento = 19; // PARA CARTEIRA DE BLASTER

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
$url = "https://sistemas.pc.sc.gov.br/carteirinhas_fiscaliza/carteirinhas/gera-carteirinha-uso-porte"; // Substitua pela URL correta

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_HEADER, 0); // Evita que os cabeçalhos HTTP sejam incluídos na saída
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,  // Send token in Authorization header
    'Content-Type: application/json'    // Set content type as JSON
]);


    $nome_carteira = 'carteira-armaparticular-'.$id_carteira.'-'.$data_expedicao.'.pdf';

    $data_expedicao_print = date("d/m/Y", $data_expedicao);
    //$data_validade_print = date("d/m/Y", $data_validade);

    $c = $cpf;

    $cpf_print = $c[0].$c[1].$c[2].".".$c[3].$c[4].$c[5].".".$c[6].$c[7].$c[8]."-".$c[9].$c[10];

    $post = [
        'nome' => $nome,
        'numero' => $numero."/".date('Y'),
        'cargo' => $cargo,
        'matricula' => $matricula,
        'cpf' => $cpf_print,
   //     'sinarm' => $arma_numero_sinarm,
        'especie' => $arma_especie,
        'marca' => $arma_marca,
        'modelo' => $arma_modelo,
    //    'numero_do_registro' => $arma_registro,
        'numero_da_arma' => $arma_numero_serie,
        'calibre' => $arma_calibre,
        'validade' => "$data_validade_print",
        'link_qr' => "https://sistemas.pc.sc.gov.br/fiscaliza/carteiras/$nome_carteira"
    ];
        //'link_qr' => "https://pc.sc.gov.br/wp-content/uploads/2024/02/Taxas-2024-2.pdf"
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
    $file_path = 'carteiras/'.$nome_carteira;
    file_put_contents($file_path, $response);

    // Feche a sessão cURL
    curl_close($curl);

    $query = "update tb_carteiras set arquivo = '$nome_carteira' where id = $id_carteira";
    $result=mysqli_query($link, $query);

    //  echo "Arquivo PDF recebido e salvo com sucesso em: $file_path";
   // echo "<br><br><a href = 'carteiras/$nome_carteira' style = 'width:100%;' class = 'btn btn-primary' target= '_blank'>VER ARQUIVO</a>";

    //echo "<br><br><a href = 'carteira.painel.php?id=$id_carteira&data_expedicao=$data_expedicao' style = 'width:100%;' class = 'btn btn-primary' target= '_blank'>IR PARA O PAINEL DA CARTEIRA</a>";
    
    echo " <div class='alert alert-info' role='alert'>";
    echo " <h4 class='alert-heading' align = 'center'>SEU PEDIDO FOI GERADO COM SUCESSO<BR><BR>E ESTÁ NA FILA DO DELEGADO PARA APROVAÇÃO</h4>";
    echo "</div>";

?>