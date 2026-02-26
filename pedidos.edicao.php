<?php

session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

$id_policial = 1;

include "classes/class.html.php";
include "classes/classe.forms.php";
include "mysql.conecta.rep.php";

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(12);


$f -> abre_card(12);



  echo "<h2>Informações gerais do Estabelecimento</h2>";

$id_pedido = (int)$_GET[id_pedido];



if(isset($_GET[id_pedido]) && !isset($_GET[editar])){

    $query = "select nome_estabelecimento, razao_social, cnpj, endereco_rua, endereco_numero, endereco_bairro, endereco_cep, telefone_fixo, telefone_celular, email, nome_proprietario, requerente_nome, requerente_data_nasc, requerente_cpf, requerente_endereco, requerente_telefone, requerente_email from tb_cidadao_pedidos where id = $id_pedido LIMIT 1";
    //echo $query;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
    $razao_social= (stripslashes($row[razao_social]));
    $endereco_rua= (stripslashes($row[endereco_rua]));
    $endereco_numero= (stripslashes($row[endereco_numero]));
    $endereco_bairro= (stripslashes($row[endereco_bairro]));
    $endereco_cep= (stripslashes($row[endereco_cep]));
    $telefone_fixo= (stripslashes($row[telefone_fixo]));
    $telefone_celular= (stripslashes($row[telefone_celular]));
    $nome_proprietario= (stripslashes($row[nome_proprietario]));
    $requerente_cpf= (stripslashes($row[requerente_cpf]));
    $requerente_endereco= (stripslashes($row[requerente_endereco]));
    $requerente_telefone= (stripslashes($row[requerente_telefone]));
    $requerente_email= (stripslashes($row[requerente_email]));

    $f->abre_form("pedidos.edicao.php?editar=1&id_pedido=$id_pedido");

    $f -> abre(6);
    $f -> f_input("Nome do Estabelecimento", "nome_estabelecimento", "$nome_estabelecimento");
    $f -> f_input("Razão Social", "razao_social", "$razao_social");
    $f -> f_input("Rua", "endereco_rua", "$endereco_rua");
    $f -> f_input("Número", "endereco_numero", "$endereco_numero");
    $f -> f_input("Bairro", "endereco_bairro", "$endereco_bairro");
    $f -> f_input("CEP", "endereco_cep", "$endereco_cep");
    $f -> f_input("Telefone Fixo", "telefone_fixo", "$telefone_fixo");
    $f->fecha();
    $f->abre(6);
    $f -> f_input("Telefone Celular", "telefone_celular", "$telefone_celular");
    $f -> f_input("Proprietário", "nome_proprietario", "$nome_proprietario");
    $f -> f_input("CPF do requerente", "requerente_cpf", "$requerente_cpf");
    $f -> f_input("Endereço do Requerente", "requerente_endereco", "$requerente_endereco");
    $f -> f_input("Telefone do Requerente", "requerente_telefone", "$requerente_telefone");
    $f -> f_input("Email do Requerente", "requerente_email", "$requerente_email");

    $query = "select DISTINCT t2.id as id, t1.campo_label as label, t2.campo_nome as nome, t2.campo_value as value from tb_campos_especiais_ref as t1 JOIN tb_campos_especiais as t2 ON t1.campo_nome = t2.campo_nome WHERE t2.id_pedido = $id_pedido";
//   echo $query;
    $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $label =  (stripslashes($row[label]));
                $nome =  trim(stripslashes($row[nome]));
                $value =  (stripslashes($row[value]));
                $f -> f_input("$label", "$nome", "$value");
            }
    $f->fecha();

    ECHO "<div style = 'display: table; padding: 14px;'>";
    $f->f_button(" SALVAR ALTERAÇÕES ");
    echo "</div>";
    $f->fecha_form();
}


if(isset($_GET[id_pedido]) && isset($_GET[editar])){
// recebe os dados para atualização

    function checa_alteracao($variavel_anterior, $nova_variavel, $id_pedido, $link){
        
        if(trim($variavel_anterior) != trim($nova_variavel))
        {
           // echo "<h1>$variavel_anterior É DIFERENTE DE $nova_variavel</h1>";
            // SENDO DIFERENTE, FAZ O REGISTRO DO LOG DA ALTERAÇÃO

            $texto = "<hr>ALTERAÇÃO DE DADOS: O ITEM $variavel_anterior FOI ALTERADO PARA $nova_variavel EM ".date("d/m/Y H:i")." POR ".$_SESSION['usuario_fis_nome']."(".$_SESSION['usuario_fis_cpf'].")";
            $query = "update tb_cidadao_pedidos set pagina_resumo = CONCAT('$texto', pagina_resumo) where id = $id_pedido";
        //    echo "<hr>".$query;
            $result = mysqli_query($link, $query);
            if(!$result)
            {   
                echo " <div class='alert alert-danger' role='alert'>";
                echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL ATUALIZAR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
                echo "</div>";
            }


        }else{
           // echo "<h2>$variavel_anterior É IGUAL A $nova_variavel</h2>";
        }
    }

    // busca os dados novamente, para poder checar o que foi alterado.
    $query = "select nome_estabelecimento, razao_social, cnpj, endereco_rua, endereco_numero, endereco_bairro, endereco_cep, telefone_fixo, telefone_celular, email, nome_proprietario, requerente_nome, requerente_data_nasc, requerente_cpf, requerente_endereco, requerente_telefone, requerente_email from tb_cidadao_pedidos where id = $id_pedido LIMIT 1";
    //echo $query;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome_estabelecimento_prev= (stripslashes($row[nome_estabelecimento]));
    $razao_social_prev = (stripslashes($row[razao_social]));
    $endereco_rua_prev = (stripslashes($row[endereco_rua]));
    $endereco_numero_prev = (stripslashes($row[endereco_numero]));
    $endereco_bairro_prev = (stripslashes($row[endereco_bairro]));
    $endereco_cep_prev = (stripslashes($row[endereco_cep]));
    $telefone_fixo_prev = (stripslashes($row[telefone_fixo]));
    $telefone_celular_prev= (stripslashes($row[telefone_celular]));
    $nome_proprietario_prev = (stripslashes($row[nome_proprietario]));
    $requerente_cpf_prev = (stripslashes($row[requerente_cpf]));
    $requerente_endereco_prev = (stripslashes($row[requerente_endereco]));
    $requerente_telefone_prev = (stripslashes($row[requerente_telefone]));
    $requerente_email_prev = (stripslashes($row[requerente_email]));

    $nome_estabelecimento  = $f->limpa_variavel($_POST['nome_estabelecimento'], 90, $purifier);
    $razao_social  = $f->limpa_variavel($_POST['razao_social'], 90, $purifier);
    $endereco_rua  = $f->limpa_variavel($_POST['endereco_rua'], 90, $purifier);
    $endereco_numero  = $f->limpa_variavel($_POST['endereco_numero'], 90, $purifier);
    $endereco_bairro  = $f->limpa_variavel($_POST['endereco_bairro'], 90, $purifier);
    $endereco_cep  = $f->limpa_variavel($_POST['endereco_cep'], 90, $purifier);
    $telefone_fixo  = $f->limpa_variavel($_POST['telefone_fixo'], 90, $purifier);
    $telefone_celular  = $f->limpa_variavel($_POST['telefone_celular'], 90, $purifier);
    $nome_proprietario  = $f->limpa_variavel($_POST['nome_proprietario'], 90, $purifier);
    $requerente_cpf  = $f->limpa_variavel($_POST['requerente_cpf'], 90, $purifier);
    $requerente_endereco  = $f->limpa_variavel($_POST['requerente_endereco'], 90, $purifier);
    $requerente_telefone  = $f->limpa_variavel($_POST['requerente_telefone'], 90, $purifier);
    $requerente_email  = $f->limpa_variavel($_POST['requerente_email'], 90, $purifier);

    checa_alteracao($nome_estabelecimento_prev, $nome_estabelecimento, $id_pedido, $link);
    checa_alteracao($razao_social_prev, $razao_social, $id_pedido, $link);
    checa_alteracao($endereco_rua_prev, $endereco_rua, $id_pedido, $link);
    checa_alteracao($endereco_numero_prev, $endereco_numero, $id_pedido, $link);
    checa_alteracao($endereco_bairro_prev, $endereco_bairro, $id_pedido, $link);
    checa_alteracao($endereco_cep_prev, $endereco_cep, $id_pedido, $link);
    checa_alteracao($telefone_fixo_prev, $telefone_fixo, $id_pedido, $link);
    checa_alteracao($telefone_celular_prev, $telefone_celular, $id_pedido, $link);
    checa_alteracao($nome_proprietario_prev, $nome_proprietario, $id_pedido, $link);
    checa_alteracao($requerente_cpf_prev, $requerente_cpf, $id_pedido, $link);
    checa_alteracao($requerente_endereco_prev, $requerente_endereco, $id_pedido, $link);
    checa_alteracao($requerente_telefone_prev, $requerente_telefone, $id_pedido, $link);
    checa_alteracao($requerente_email_prev, $requerente_email, $id_pedido, $link);

    // realiza a query de atualização

    $query = "update tb_cidadao_pedidos set nome_estabelecimento = '$nome_estabelecimento', razao_social = '$razao_social', endereco_rua = '$endereco_rua', endereco_numero = '$endereco_numero', endereco_bairro = '$endereco_bairro', endereco_cep = '$endereco_cep', telefone_fixo = '$telefone_fixo', telefone_celular = '$telefone_celular', email = '$email', nome_proprietario = '$nome_proprietario', requerente_nome = '$requerente_nome', requerente_data_nasc = '$requerente_data_nasc', requerente_cpf = '$requerente_cpf', requerente_endereco = '$requerente_endereco', requerente_telefone = '$requerente_telefone', requerente_email =  '$requerente_email' where id = $id_pedido";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL ATUALIZAR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>CADASTRO ATUALIZADO COM SUCESSO</h4>";
        echo "</div>"; 
    }

    // SEGUNDA PARTE DAS ATUALIZAÇÕES, DOS CAMPOS ESPECIAIS, SE HOUVER

    $query = "select  campo_nome from tb_campos_especiais WHERE id_pedido = $id_pedido";
    $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $campo_nome =  trim(stripslashes($row[campo_nome]));
                if (isset($_POST[$campo_nome])) {

                    $campo_nome_insere  = $f->limpa_variavel($_POST[$campo_nome], 90, $purifier);

                    if(strlen($campo_nome_insere) > 0){

                        $query2 = "update tb_campos_especiais set campo_value = '$campo_nome_insere' where campo_nome = '$campo_nome' and id_pedido = $id_pedido";
                        //echo "<hr>".$query2;
                        $result2 = mysqli_query($link, $query2);
                        if(!$result2)
                        {   
                            echo " <div class='alert alert-danger' role='alert'>";
                            echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL ATUALIZAR O CADASTRO DO CAMPO ESPECIAL<br>$query2<br> </h4>".mysqli_error($link);
                            echo "</div>";
                        }
                    }
                }
               
                
            }
    $f->fecha();

}
    
echo "<div style = 'display:block; width:100%; text-align: right; padding: 24px;'>";
echo "<p><a href = 'pedidos.painel2.php?id_pedido=$id_pedido' class = 'btn btn-light'>VOLTAR PARA A PÁGINA DE ANÁLISE</a></p>";
echo "</div>";

$f -> fecha_card();


$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>



