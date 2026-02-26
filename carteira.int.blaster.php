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
$numero  = $f->limpa_variavel($_POST['numero'], 190, $purifier);
$cep  = $f->limpa_variavel($_POST['cep'], 190, $purifier);
$id_municipio = (int)$_POST['id_municipio'];
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
$número_empresa_empregadora  = $f->limpa_variavel($_POST['número_empresa_empregadora'], 190, $purifier);
$cep_empresa_empregadora  = $f->limpa_variavel($_POST['cep_empresa_empregadora'], 190, $purifier);
$id_municipio_empresa_empregadora = (int)$_POST['id_municipio_empresa_empregadora'];
$contato_empresa_empregadora  = $f->limpa_variavel($_POST['contato_empresa_empregadora'], 190, $purifier);
$email_empresa_empregadora  = $f->limpa_variavel($_POST['email_empresa_empregadora'], 190, $purifier);
$registro_exercito_empresa_empregadora  = $f->limpa_variavel($_POST['registro_exercito_empresa_empregadora'], 190, $purifier);
$numero_registro_empresa_empregadora  = $f->limpa_variavel($_POST['numero_registro_empresa_empregadora'], 190, $purifier);


$data_expedicao = time();
$validade_gape = 5; // a ser ajustado caso a caso. Aqui considerei validade de 5 anos
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
        'id_municipio' => $id_municipio,
        'blaster_empresa_nome' => $nome_empresa_empregadora,
        'blaster_empresa_cnpj' => $cnpj_empresa_empregadora

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

            // AGORA GERA A CARTEIRA, PARA VALER
            
            $url = "http://10.121.23.194:3000/gera-carteirinha-blaster";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0); // Evita que os cabeçalhos HTTP sejam incluídos na saída

            $headers = array(
                "Content-Type: application/json",
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          

            $nome_carteira = 'carteira-blaster-'.$id_carteira.'-'.$data_expedicao.'.pdf';

            $data_expedicao_print = date("d/m/Y", $data_expedicao);
            $data_validade_print = date("d/m/Y", $data_validade);

            $post = [
                'numero' => $numero."/".date('Y'),
                'nome' => $nome,
                'cpf' => $cpf,
                'rg' => $rg,
                'pai' => $filiacao_pai,
                'mae' => $filiacao_mae,
                'empresa' => $nome_empresa_empregadora,
                'capacitacao' => $nome_empresa_curso,
                'categoria' => $categoria_curso,
                'calibre' => "9 mm",
                'data_expedicao' => $data_expedicao_print,
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

            // FAZ O UPDATE DO NOME DO ARQUIVO NA TABELA DA CARTEIRA.

            $query = "update tb_carteiras set arquivo = '$nome_carteira' where id = $id_carteira";
            $result=mysqli_query($link, $query);

            // echo "Arquivo PDF recebido e salvo com sucesso em: $file_path";
            // echo "<br><br><a href = 'carteiras/$nome_carteira'>ver  arquivo</a>";
            echo "<br><br><a href = 'carteiras/$nome_carteira' style = 'width:100%;' class = 'btn btn-primary' target= '_blank'>VER ARQUIVO</a>";
            

?>