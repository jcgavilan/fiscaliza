<?php
// ESTOU COMENTANDO TODOS OS PRINTS DE HTML PORQUE ESTE DOCUMENTO VAI SER DISPARADO VIA SHELL
session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

// if($_SESSION["usuario_fis_is_delegado"] == 0)
// {
//   header('Location: https://integra.pc.sc.gov.br/');
//   exit();
// }

include "classes/class.html.php";
include "classes/classe.forms.php";
include "mysql.conecta.rep.php";

////$header=new Header_adm_WEB(); 

$f = new Forms();

//$a=new Menu_adm($link);

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

//$nome_pagina= "Bem-Vindo";
//$a=new Abre_titulo();
//$a->titulo_pagina($nome_pagina);

//echo "<h1>Painel de Análise de Pedidos</h1>";
//$f -> abre_card(12);
$now = time();
$data_limite = $now+(60*60*24);
$query = "select id, id_municipio, documento_final, data_conclusao, tipo_pedido, nome_estabelecimento, razao_social, cnpj from tb_cidadao_pedidos where status = 3 AND data_validade < $data_limite";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
//echo "<h1>REsultado: $num</h1>";
for($i=0;$i<$num;$i++) 
        {
            //echo "<h1>--> $i</h1>";
            $row = mysqli_fetch_array($result);
            $id = $row[id];
            $id_municipio = $row[id_municipio];
            $documento_final = stripslashes($row[documento_final]);
            $data_conclusao = $row[data_conclusao];
            $data_expedicao = date("d/m/Y", $data_conclusao);
            $tipo_pedido = $row[tipo_pedido];
            $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
            $razao_social =  (stripslashes($row[razao_social]));
            $cnpj =  (stripslashes($row[cnpj]));
            $a = $cnpj;
            $cnpj = $a[0].$a[1].".".$a[2].$a[3].$a[4].".".$a[5].$a[6].$a[7]."/".$a[8].$a[9].$a[10].$a[11]."-".$a[12].$a[13];

            // CRIA UMA CÓPIA DO ARQUIVO ORIGINAL EM UMA PASTA INTERNA
            $copia = copy("../cidadao/alvaras/$documento_final", "alvaras_vencidos/$documento_final");
            if (!$copia) {
                // echo " <div class='alert alert-danger' role='alert'>";
                // echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL COPIAR O ARQUIVO<br></h4>";
                // echo "</div>";
            }

            // GERA O ARQUIVO DE DECLARAÇÃO DE VENCIMENTO, E SUBSTITUI NOS ARQUIVOS
            $now = time();
            $data_vencimento = date("d/m/Y", $now);

            include "html_base/alvara_vencido.php";

            $str_base = "abcdefghijklmnopqrstuvxywzabcdefghijklmnopqrstuvxywzabcdefghijklmnopqrstuvxywzabcdefghijklmnopqrstuvxywzabcdefghijklmnopqrstuvxywz";
            $str_meio = "";
            for($k=0;$k<8;$k++)
            {
                $str_prov = str_shuffle($str_base);
                $str_meio .= substr($str_prov, 0, 1);
            }
          
           // echo $html;
            $nome_arquivo = $str_meio.".html";
            $myfile = fopen("html_descartavel/$nome_arquivo", "w") or die("erro na abertura do arquivo");
            
            fwrite($myfile, $html);
            fclose($myfile);

            $output = exec("python3 html2pdf.py html_descartavel/$nome_arquivo ../cidadao/alvaras/$documento_final 2>&1");

            if(!$output)
            {
              //  echo "FALHA  python3 html_descartavel/$nome_arquivo alvaras/$nome_final";
            }          

            // ATUALIZA O STATUS DO REGISTRO

            // status 5 = vencido
            $query2 = "update tb_cidadao_pedidos set status = 5, historico = CONCAT(historico, 'DOCUMENTO VENCIDO EM $data_expedicao') where id = $id AND id_municipio = $id_municipio LIMIT 1";
            $result2=mysqli_query($link, $query2);
            if (!$result2) {
         //     echo "<H1>NÃO ATUALIZOU - $query2</H1>";
            }  

        }






// verifica 


// $f -> fecha_card();

// $footer=new Footer_adm_WEB();
// $footer->Footer_adm_WEB();



?>