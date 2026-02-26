<?php

header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");
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

// echo "<br>".$_SESSION['usuario_fis_nome'];
// echo "<br>".$_SESSION['usuario_fis_cpf'];
// echo "<br>".$_SESSION['usuario_fis_ibge'];



// if (1 != 1) { // SÓ PARA ESSE CÓDIGO NÃO SER EXECUTADO

//     $query = "select ibge_reduzido from tb_municipios_ibge where ibge_microrregional = ".$_SESSION['usuario_fis_ibge']." OR ibge_reduzido = ".$_SESSION['usuario_fis_ibge']; // assim, mesmo que o municipio seja abrangido por outro, os policiais locais continuam tendo acesso
//     $result = mysqli_query($link, $query);
//         $num = mysqli_num_rows($result);
//         for($i=0;$i<$num;$i++)
//         {
//             $row = mysqli_fetch_array($result);
//             $id = $row[id];
//             $ibge = stripslashes($row[ibge_reduzido]);
    
//             if ($i == 0) {
//                 $municipios_ref = $ibge;
//             }else{
//                 $municipios_ref .= ", ".$ibge;
//             }
//         }
// }

$query = "select id from tb_lotacao_expandida where cpf = '".$_SESSION['usuario_fis_cpf']."'";
$result = mysqli_query($link, $query);
$num = mysqli_num_rows($result);

if($num != 0){
    $row = mysqli_fetch_array($result);
    $id_usuario = $row[id];
    $municipios_ref = '';
    $query = "select ibge from tb_lotacao_expandida_vinculos where id_usuario = $id_usuario"; // assim, mesmo que o municipio seja abrangido por outro, os policiais locais continuam tendo acesso
    $result = mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++)
        {
            $row = mysqli_fetch_array($result);
            $id = $row[id];
            $ibge = stripslashes($row[ibge]);

            $municipios_ref .= ", ".$ibge;

        }
        $_SESSION['usuario_fis_ibge'] = $_SESSION['usuario_fis_ibge'].$municipios_ref;
}




// a definição da session fica condicionada a ter resultado na query, maior que 1, porque se não tiver, trata-se de um municipio que é absorvido por uma circunscrição, e o policial vai acessar localmente.
// não pode ser maior que zero, porque tem o caso do municipio ser unico com a circunscricao
// ao não executar a redefinição, a session permanece com o municipio de origem, setado a partir do graph-ql
    // if ($num > 1) {
    //     $_SESSION['usuario_fis_ibge'] = $municipios_ref;
    // }

// aqui verifica se o usuário tem visualização especial para a página de pesquisa, mesmo não sendo perfil administrativo

if($_SESSION['usuario_for_status'] == "normal"){

    $query = "select id_ref from tb_drps where cpf_usuario_especial = '".$_SESSION['usuario_fis_cpf']."'";
    $result = mysqli_query($link, $query);
    $num = mysqli_num_rows($result);

    if($num != 0){
        $row = mysqli_fetch_array($result);
        $id_ref = $row[id_ref];

        $query = "select ibge_reduzido from tb_municipios_ibge where numero_drp = $id_ref";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++)
            {
              $row = mysqli_fetch_array($result);
              $id = $row[ibge_reduzido];
                if($i == 0){
                    $str = $id;
                }else{
                    $str .= ", ".$id;
                }
            }

            $_SESSION['usuario_especial_restricao'] = $str;
    }else{
        unset($_SESSION['usuario_especial_restricao']);
    }

}


// AQUI VAI SER O TRATAMENTO DA UNIDADE POLICIAL, PARA ESTA INFORMAÇÃO FICAR DISPONÍVEL NO SISTEMA
if(!isset($_GET['atualizar_unidade_policial'])){

    $query = "select unidade_numero, is_delegado from tb_unidades_policiais where cpf_policial = '".$_SESSION['usuario_fis_cpf']."'";
   // echo $query;
    $result = mysqli_query($link, $query);
    $num = mysqli_num_rows($result);

        $row = mysqli_fetch_array($result);
        //$_SESSION['usuario_fis_is_delegado']  = $row[is_delegado];

    if($num == 0){ // num zero -> ainda não há o cadastro do policial
        $header=new Header_adm_WEB(); 
        $f = new Forms();
        $a=new Menu_adm($link);
        $a=new Abre_titulo();
        $a->titulo_pagina('');
        // apresenta o formulário para o policial indicar os dados da unidade
        echo "<h2>Olá ". $_SESSION['usuario_fis_nome']."</h2>";
        echo "<h2>Por favor, Informe os dados da sua Unidade Policial</h2><hr><br>";
        $f -> abre_form("index.php?atualizar_unidade_policial=1");


       /* echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>Informe seu Cargo na Policia Civil</label><br>";
        echo "<select class='form-control' id='cargo' required='' name='cargo'>";
        echo "<option value='0'>AGENTE</option>";
        echo "<option value='0'>ESCRIVÃO</option>";
        echo "<option value='1'>DELEGADO DE POLÍCIA</option>";
        echo "</select></div>";

        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>Número da sua Unidade Policial</label><br>";
        echo "<select class='form-control' id='unidade_numero' required='' name='unidade_numero'>";
        echo "<option value=''></option>";
        for($i=1;$i<1000;$i++) 
                {
                    $unid = sprintf('%03d', $i);
                    echo "<option value='".$i."'";
                    echo ">".$unid."</option>";         
                }
        echo "</select></div>";
        */


        $f -> f_input("Nome da Unidade Policial (exemplo: Blumenau - Divisão de Investigação Criminal - DIC)", "unidade_nome", "");
        $f -> f_input("Endereço da Unidade Policial", "unidade_endereco", "");
        $f->f_button("Salvar");

        echo "</form>";
        $footer=new Footer_adm_WEB();
        $footer->Footer_adm_WEB();
    

    }else{
        // ok, policial já tem a unidade cadastrada, redireciona para a lista.php
        header('Location: pedidos.lista.php');
        exit;
    }


}else{

    // EXECUTA O CÓDIGO DE INCLUSÃO DO POLICIAL NA TABELA DE UNIDADES POLICIAIS
    $header=new Header_adm_WEB(); 
    $f = new Forms();
    $a=new Menu_adm($link);
    $a=new Abre_titulo();
    $a->titulo_pagina('');

    $cargo = (int)$_POST['cargo'];
   // $_SESSION['usuario_fis_is_delegado']  = $cargo;


    $unidade_numero = (int)$_POST['unidade_numero'];
    $unidade_nome  = $f->limpa_variavel($_POST['unidade_nome'], 160, $purifier);
    $unidade_endereco  = $f->limpa_variavel($_POST['unidade_endereco'], 240, $purifier);

    $data = time();

    $query = "INSERT INTO `tb_unidades_policiais`(`cpf_policial`, `unidade_numero`, `unidade_nome`, `unidade_endereco`,`data_cadastro`) VALUES ('".$_SESSION['usuario_fis_cpf']."', $unidade_numero, '$unidade_nome', '$unidade_endereco',   $data)";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>CADASTRO CONCLUÍDO COM SUCESSO</h4>";
        
        echo "</div>";
        echo "<a href = 'pedidos.lista.php' class = 'btn btn-primary' style = 'width: 100%; margin-top: 24px;'>IR PARA O PAINEL DE PEDIDOS DE ALVARÁ</a>";
        
    }
    $footer=new Footer_adm_WEB();
    $footer->Footer_adm_WEB();

}



    
?>