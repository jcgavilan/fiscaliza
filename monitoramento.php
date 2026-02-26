<?php

session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

include "mysql.conecta.rep.php";

include "classes/class.html.php";
include "classes/classe.forms.php";


require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);


$header=new Header_adm_WEB(); 
$f = new Forms();
$a=new Menu_adm($link);
$a=new Abre_titulo();
$a->titulo_pagina('');

$f -> abre_card(12);
echo "<h1>Monitoramento de Emissão Mensal</h1>";
echo "<div style = 'display: block; width: 100%; text-align:right'><a href = 'monitoramento.php?acao=ver_licencas_mensais'  class='btn btn-primary'>BUSCAR COM BASE NAS LICENÇAS MENSAIS JÁ EMITIDAS</a></div>";
echo "<hr>";

$f-> abre(12);


$f-> abre(8);echo "&nbsp;";$f->fecha();

    echo "<form action='monitoramento.php?acao=fase_1' method='post'>";

        $f->abre(3);
            $f -> f_input_mask("CNPJ da empresa", "cnpj", "cnpj");
        $f->fecha();

        $f->abre(1);
            echo "<button type='submit' name='Submit' class='btn btn-primary' style = 'width: 100%; margin-top: 24px;'><i class='icon-magnifier'></i></button>";
        $f->fecha();

    echo "</form>";

$f->fecha();


if ($_GET['acao'] == 'fase_1') {
    
    $cnpj = $f->limpa_variavel($_POST['cnpj'], 20, $purifier);
    $cnpj = str_replace("-", "", $cnpj);
    $cnpj = str_replace("/", "", $cnpj);

    $query = "select nome_estabelecimento, id_municipio from tb_cidadao_pedidos where cnpj = '$cnpj' LIMIT 1";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $id_municipio = $row[id_municipio];
    $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
    $f->abre(4);
    echo "<form action='monitoramento.php?acao=fase_2' method='post'>";
    $f -> f_input("Nome do Estabelecimento", "nome_estabelecimento", $nome_estabelecimento);
    $f -> f_input_mask_retorno("CNPJ da empresa", "cnpj","cnpj", $cnpj);
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>".$label_endereco['municipio']." $ast</span></label><br>";
    echo "<select class='form-control' id='id_municipio'  name='id_municipio' required = ''>";
    echo "<option value='' selected disabled></option>";
    $query = "select * from tb_municipios_ibge where ativo = 1 order by nome asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $ibge = $row[ibge_reduzido];
                $nome =  (stripslashes($row[nome]));
                echo "<option value='".$ibge."'";
                if($id_municipio == $ibge){echo " selected";}
                echo ">".$nome."</option>";         
            }
    echo "</select></div>";
    echo "<button type='submit' name='Submit' class='btn btn-primary'>CADASTRAR</button>";
    echo "</form>";
    $f->fecha();
    
}

if ($_GET['acao'] == 'fase_2') {

    $cnpj = $f->limpa_variavel($_POST['cnpj'], 20, $purifier);
    $cnpj = str_replace("-", "", $cnpj);
    $cnpj = str_replace("/", "", $cnpj);

    $id_municipio = (int)$_POST[id_municipio];

    $nome_estabelecimento = $f->limpa_variavel($_POST['nome_estabelecimento'], 90, $purifier);

    $query = "insert into tb_monitoramento (cnpj, nome, id_municipio) values ('$cnpj', '$nome_estabelecimento', $id_municipio)";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL GRAVAR O RESUMO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>CNPJ CADASTRADO PARA MONITORAMENTO</h4>";
        echo "</div>";
    }

    echo "<br><br><a href = 'monitoramento.php' class='btn btn-primary'>VOLTAR PARA PÁGINA DE MONITORAMENTOS</a>";
}



if (!isset($_GET['acao'])) { // já faz a leitura automática dos alvarás do mês

    // busca os dados da tabela de pedidos, no mês de referência, para comparação

    $mes = date('m');
    $ano = date('Y');
    $data_ini = mktime(0,0,0, $mes, 1, $ano);

    $a_cnpj = array();
    $a_data_pedido = array();
    $a_data_conclusao = array();

    $query = "select cnpj, data_pedido, data_conclusao from tb_cidadao_pedidos where data_pedido >= $data_ini";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++)
        {
            $row = mysqli_fetch_array($result);
            $cnpj = $row[cnpj];
            $data_pedido = $row[data_pedido];
            $data_conclusao = $row[data_conclusao];
            $a_cnpj[]= $cnpj;
            $a_data_pedido[$cnpj] = $data_pedido;
            $a_data_conclusao[$cnpj] = $data_conclusao;
        }


    
    $query = "select nome, cnpj, id_municipio from tb_monitoramento";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);

    if($num != 0){
        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
            echo "<th scope='col'>Estabelecimento</th>";
            echo "<th scope='col'>Data do Pedido</th>";
            echo "<th scope='col'>Data da Conclusão</th>";
            echo "<th scope='col'>Status</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
    }

  //  print_r($a_data_pedido);

    for($i=0;$i<$num;$i++)
        {
            $row = mysqli_fetch_array($result);
            $id_municipio = $row[id_municipio];
            $nome = (stripslashes($row[nome]));
            $cnpj = (stripslashes($row[cnpj]));

            echo "<tr>";
            echo "<td>$nome - $cnpj</td>";
            
            echo "<td>";
                if (isset($a_data_pedido[$cnpj])) {
                    echo date("d/m/Y", $a_data_pedido[$cnpj]);
                    $status = "<span class='badge badge-warning'>PEDIDO CADASTRADO</span>";
                }else{
                    echo "-";
                    $status = "<span class='badge badge-danger'>PEDIDO NÃO CADASTRADO</span>";
                }
            echo"</td>";

            echo "<td>";
                if (isset($a_data_conclusao[$cnpj]) && $a_data_conclusao[$cnpj] !=0) {
                    echo date("d/m/Y", $a_data_conclusao[$cnpj]);
                    $status = "<span class='badge badge-info'>PEDIDO CONCLUÍDO</span>";
                }

                if (isset($a_data_conclusao[$cnpj]) && $a_data_conclusao[$cnpj] == 0) {
                    echo "-";
                    $status = "<span class='badge badge-warning'>PEDIDO EM ATENDIMENTO</span>";
                }


            echo"</td>";

            echo "<td>";
                echo $status;
            echo "</td>";
            

            echo "</tr>";

        }   
        echo "</tbody></table>";
}

// ESSE SCRIPT É PARA VERIFICAR CADASTROS RECENTES
if ($_GET['acao'] == 'ver_licencas_mensais') {


    $cnpj_array = array();
    $id_municipio = '420540';
    $query = "select cnpj, nome_estabelecimento, data_pedido, data_conclusao, requerente_email from tb_cidadao_pedidos where id_municipio = $id_municipio AND tipo_pedido = 3 order by data_pedido desc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);

    echo "<table class='table table-striped'>";
    echo "<thead>";
    echo "<tr>";
        echo "<th scope='col'>CNPJ</th>";
        echo "<th scope='col'>Estabelecimento</th>";
        echo "<th scope='col'>Email</th>";
        echo "<th scope='col'>Data do Pedido</th>";
        echo "<th scope='col'>Data da Conclusão</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $mes = date('m');
    $ano = date('Y');

    $mes_inicio = mktime(0, 0, 0, $mes, 1, $ano);

    for($i=0;$i<$num;$i++)
        {
            $row = mysqli_fetch_array($result);
            $cnpj = $row[cnpj];
            $nome_estabelecimento = (stripslashes($row[nome_estabelecimento]));
            $requerente_email = (stripslashes($row[requerente_email]));
            $data_pedido = $row[data_pedido];
            $data_conclusao = $row[data_conclusao];

            if($data_pedido>$mes_inicio){
                $badge = 'success';
            }else{
                $badge = 'warning';
            }

            if(!in_array($cnpj, $array_cnpj)){

                echo "<tr>";
                echo "<td>$cnpj</td>";
                echo "<td>$nome_estabelecimento</td>";
                 echo "<td>$requerente_email</td>";
                echo "<td><span class='badge badge-$badge'>".date("d/m/Y", $data_pedido)."</span></td>";
                echo "<td>";
                if ($data_conclusao == 0) {
                    echo "EM ABERTO";
                }else{
                    echo date("d/m/Y", $data_conclusao);
                }
                echo "</td>";
                echo "</tr>";
                $array_cnpj[] = $cnpj;
            }
        }

    echo "</tbody></table>";
}



$f->fecha_card();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();
?>