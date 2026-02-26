<?php

session_start();

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

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
  

if($_SESSION['usuario_for_status'] == "adm") {

  $f -> abre_card(12);

  echo "<h1>Usuários - Lotação expandida</h1><hr>";
    echo "<div style = 'display: block; width:100%; text-align: right;'>";

    echo "<a href = 'usuarios.lotacao.php?acao=cadastrar' class = 'btn btn-primary'>CADASTRAR NOVO USUÁRIO</a> &nbsp;";
    echo "<a href = 'usuarios.lotacao.php?acao=listar_usuarios' class = 'btn btn-primary'>VER USUÁRIOS CADASTRADOS</a> &nbsp;";

    echo "</div>";

  echo "<br>";

  if ($_GET['acao'] == 'listar_usuarios') {

    echo "<br><br><h4>Usuários Cadastrados:</h4>";
    echo "<table class='table table-striped'>";
    echo "<thead>";
        echo "<tr>";
    
        echo "<th scope='col' style = 'width: 40%'>Nome</th>"; 
        echo "<th scope='col'>CPF</th>";
        echo "<th scope='col'>Delegado</th>";
        echo "<th scope='col'>email</th>";
        echo "<th scope='col'>Lotação Expandida</th>"; 
        echo "<th scope='col'>Edição / Exclusão</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        $query = "select * from tb_lotacao_expandida order by nome asc";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $id_usuario = $row[id];
                    $cpf = $row[cpf];
                    $nome =  (stripslashes($row[nome]));
                    $is_delegado = $row[is_delegado];
                    $email =  (stripslashes($row[email]));

                    echo "<tr>";
                        echo "<td>$nome</td>";
                        echo "<td>$cpf</td>";
                        echo "<td>";
                        if ($is_delegado == 1) {
                            echo "SIM";
                        } else {
                            echo "NÃO";
                        }
                        echo "</td>";

                        echo "<td>$email</td>";

                        echo "<td><a href = 'usuarios.lotacao.php?acao=listar_cidades&id_usuario=$id_usuario' class = 'btn btn-primary' title = 'VER LOCALIDADES'><i class='icon-location-pin'></i></a></td>";
                        echo "<td>"; 
                        echo "<a href = 'usuarios.lotacao.php?acao=editar_form&id_usuario=$id_usuario' class = 'btn btn-primary' title 'EDITAR USUÁRIO'><i class='icon-note'></i></a> &nbsp; ";
                        echo "<a href = 'usuarios.lotacao.php?acao=excluir_prev&id_usuario=$id_usuario' class = 'btn btn-danger' title 'EXCLUIR USUÁRIO'><i class='icon-trash'></i></a>";
                        echo "</td>";
                    echo "</tr>";

                }

                echo "</tbody> </table><BR><HR><BR>";

  }

  if ($_GET['acao'] == 'excluir_prev') {

    echo "<h2>Tem certeza de que deseja EXCLUIR o usuário?</h2>";

    echo "<br><br><a href = 'usuarios.lotacao.php?acao=listar_usuarios' class = 'btn btn-primary'>NÃO, voltar para lista de usuários</a>";
    echo "<br><br><a href = 'usuarios.lotacao.php?acao=excluir&id_usuario=".$_GET[id_usuario]."' class = 'btn btn-danger'>SIM, DELETAR USUÁRIO</a>";    

  }

  if ($_GET['acao'] == 'excluir') {

    $id_usuario = (int)$_GET[id_usuario];

    // busca o nome do usuário, e dá o log da exclusão.

    $query = "select nome from tb_lotacao_expandida where id = $id_usuario";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome =  (stripslashes($row[nome]));


    $email = 'marcus-lopes@pc.sc.gov.br';
    $titulo_email = 'EXCLUSÃO DE USUÁRIO NO FISCALIZA';
    $mensagem = "Usuário: ".$_SESSION['usuario_fis_nome']."<br>CPF: ".$_SESSION['usuario_fis_cpf']."<br>Usuario excluido: $nome";
    $envia = exec("curl -d 'to=$email&subject=$titulo_email&html=$mensagem' -X POST https://getin.pc.sc.gov.br/sendmail/");

    $query = "delete from tb_lotacao_expandida_vinculos where id_usuario = $id_usuario";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>LISTA DE LOCALIDADES DESTE USUÁRIO HIGIENIZADA COM SUCESSO</h4>";
        echo "</div>";        
    }

    $query = "delete from tb_lotacao_expandida where id = $id_usuario";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>USUÁRIO EXCLUÍDO COM SUCESSO</h4>";
        echo "</div>";        
    }

  }

  if ($_GET['acao'] == 'cadastrar') {

        $f->abre(4);
        $f -> abre_form("usuarios.lotacao.php?acao=incluir");
        $f->f_input("Nome do Usuário", "nome", '');
        $f->f_input_mask("CPF", 'cpf', 'cpf');
        $f->f_input("Email", "email", '');
      //  $f->f_radio_simnao("O usuário é Delegado?", 'is_delegado');


               echo "<div class='form-group'>";
         echo "<label for='inputText3' class='col-form-label'>O usuário é delegado?</label><br>";
            echo "<label class='custom-control custom-radio custom-control-inline'>";
                echo "<input type='radio' name='is_delegado' class='custom-control-input' value='1'";
                echo "><span class='custom-control-label'>Sim</span>";
            echo "</label>";
            echo "<label class='custom-control custom-radio custom-control-inline'>";
                echo "<input type='radio' name='is_delegado' class='custom-control-input' value='0' checked";
                echo "><span class='custom-control-label'>Não</span>";
            echo "</label>";
        echo "</div>";
        $f->f_button("SALVAR E PROSSEGUIR");
        echo "</form>";
        $f->fecha();

    }

      if ($_GET['acao'] == 'editar_form') {

        $id_usuario = (int)$_GET[id_usuario];

        $query = "select * from tb_lotacao_expandida where id = $id_usuario";
        $result = mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);
        $cpf = stripslashes($row[cpf]);
        $email = stripslashes($row[email]);
        $id = stripslashes($row[id]);
        $is_delegado = stripslashes($row[is_delegado]);
        $nome = stripslashes($row[nome]);


        $f->abre(4);
        $f -> abre_form("usuarios.lotacao.php?acao=editar_int&id_usuario=$id_usuario");
        $f->f_input("Nome do Usuário", "nome", $nome);
        $f->f_input_mask_retorno("CPF", 'cpf', 'cpf', $cpf);
        $f->f_input("Email", "email", $email);
     //   $f->f_radio_simnao("O usuário é Delegado?", 'is_delegado');

         echo "<div class='form-group'>";
         echo "<label for='inputText3' class='col-form-label'>O usuário é delegado?</label><br>";
            echo "<label class='custom-control custom-radio custom-control-inline'>";
                echo "<input type='radio' name='is_delegado' class='custom-control-input' value='1'";
                    if ($is_delegado == 1) {echo " checked";}
                echo "><span class='custom-control-label'>Sim</span>";
            echo "</label>";
            echo "<label class='custom-control custom-radio custom-control-inline'>";
                echo "<input type='radio' name='is_delegado' class='custom-control-input' value='0' ";
                    if ($is_delegado == 0) {echo " checked";}
                echo "><span class='custom-control-label'>Não</span>";
            echo "</label>";
        echo "</div>";


        $f->f_button("SALVAR E PROSSEGUIR");
        echo "</form>";
        $f->fecha();

    }

     if ($_GET['acao'] == 'editar_int') {

        $id_usuario = (int)$_GET[id_usuario];

        $nome  = $f->limpa_variavel($_POST['nome'], 90, $purifier);
        $cpf  = $f->limpa_variavel($_POST['cpf'], 15, $purifier);
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);

        $email  = $f->limpa_variavel($_POST['email'], 90, $purifier);
        $is_delegado = (int)$_POST['is_delegado'];
    
        $query = "update tb_lotacao_expandida set nome = '$nome', cpf = '$cpf', is_delegado = $is_delegado, email = '$email' where id = $id_usuario";
        $result=mysqli_query($link, $query);
            if(!$result){
                echo "<h1>ERRO -> $query</h1>".mysqli_error($link);
            }else{
                echo "<h2>USUÁRIO ATUALIZADO COM SUCESSO</h2>";
               // echo "<br><a href = 'usuarios.lotacao.php?id_usuario=$id_usuario&acao=listar_cidades' class='btn btn-primary'>HABILITAR ESTE USUÁRIO EM NOVAS LOCALIDADES</a>";
            }
    }

    if ($_GET['acao'] == 'incluir') {
        $nome  = $f->limpa_variavel($_POST['nome'], 90, $purifier);
        $cpf  = $f->limpa_variavel($_POST['cpf'], 15, $purifier);
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);

        $email  = $f->limpa_variavel($_POST['email'], 90, $purifier);
        $is_delegado = (int)$_POST['is_delegado'];

        // ANTES DE CADASTRAR, VERIFICA SE JÁ NÃO ESTÁ CADASTRADO

        $query = "select id from tb_lotacao_expandida where cpf = '$cpf'";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);

        if($num == 0){

            $query = "INSERT INTO `tb_lotacao_expandida`( `nome`, `cpf`, `is_delegado`, `email`) VALUES ('$nome','$cpf', $is_delegado, '$email')";
            $result=mysqli_query($link, $query);
            if(!$result){
                echo "<h1>ERRO -> $query</h1>".mysqli_error($link);
            }else{
                echo "<h2>USUÁRIO CADASTRADO COM SUCESSO</h2>";
                $id_usuario = mysqli_insert_id($link);
                echo "<br><a href = 'usuarios.lotacao.php?id_usuario=$id_usuario&acao=listar_cidades' class='btn btn-primary'>HABILITAR ESTE USUÁRIO EM NOVAS LOCALIDADES</a>";
            }
        }else{
            echo "<h1>ESTE CPF JÁ ESTÁ CADASTRADO</h1>";
        }

       
    }


  if ($_GET['acao'] == 'listar_cidades') {

    $id_usuario = (int)$_GET[id_usuario];

    $cidades_usuario = array();

    $query = "select ibge from tb_lotacao_expandida_vinculos where id_usuario = $id_usuario";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $cidades_usuario[] = $row[ibge];
            }

    $f->abre(12);
    $query = "select nome from tb_lotacao_expandida where id = $id_usuario LIMIT 1";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    echo "<h2>Usuário: ".stripslashes($row[nome])."<h2><br>";
    $f->fecha();
    $f->abre(12);
    $c1='';$c2='';$c3='';

    $contador = 1;

    function check_local($nome, $id, $check){
        $str .=  "<label class='custom-control custom-checkbox'>";
        $str .=  "<input type='checkbox'  name='cidade_$id'";
            if($check == 1) {$str .=  " checked = '' ";}
        $str .=  " class='custom-control-input' value = '$id'><span class='custom-control-label'>$nome</span>";
        $str .=  "</label>";
        return $str;
    }

    $f -> abre_form("usuarios.lotacao.php?acao=vincular_cidades&id_usuario=$id_usuario");
    $query = "select nome, ibge_reduzido from tb_municipios_ibge order by nome asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $ibge = $row[ibge_reduzido];
                $nome =  (stripslashes($row[nome]));
                if (in_array($ibge, $cidades_usuario)) {
                    $check = 1;
                }else{
                    $check = 0;
                }

                switch ($contador) {
                    case 1:
                        $c1 .= check_local($nome, $ibge, $check);
                        $contador++;
                        break;
                    
                    case 2:
                        $c2 .= check_local($nome, $ibge, $check);
                        $contador++;
                        break;

                    case 3:
                        $c3 .= check_local($nome, $ibge, $check);
                        $contador = 1;
                        break;
                }
          
            }
            
            $f->abre(4); 
            echo $c1;
            $f->fecha();

            $f->abre(4);
            echo $c2;
            $f->fecha();

            $f->abre(4);
            echo $c3;
            $f->fecha();
            
            $f->abre(12);
            $f->f_button("SALVAR");
            $f->fecha();

            echo "</form>";
            $f->fecha();
        }


    if ($_GET['acao'] == 'vincular_cidades') {

        $id_usuario = (int)$_GET[id_usuario];

        $query2 = "delete from tb_lotacao_expandida_vinculos where id_usuario = $id_usuario";
        $result2 = mysqli_query($link, $query2);

        $query = "select nome, ibge_reduzido from tb_municipios_ibge order by nome asc";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $ibge = $row[ibge_reduzido];
                $nome =  (stripslashes($row[nome]));

                if (isset($_POST['cidade_'.$ibge])) {
                    $query2 = "insert into tb_lotacao_expandida_vinculos (id_usuario, ibge) values ($id_usuario, $ibge)";
                    $result2=mysqli_query($link, $query2);
                    if(!$result2){
                        echo "<h1>ERRO -> $query2</h1>".mysqli_error($link);
                    }else{
                        echo "<h3>Usuário vinculado a $nome com Sucesso</h3>";
                    }
                }
            }        
    }

}else{
    $email = 'marcus-lopes@pc.sc.gov.br';
    $titulo_email = 'USUÁRIO INDEVIDO NO FISCALIZA';
    $mensagem = "Usuário: ".$_SESSION['usuario_fis_nome']."<br>CPF: ".$_SESSION['usuario_fis_cpf'];
    $envia = exec("curl -d 'to=$email&subject=$titulo_email&html=$mensagem' -X POST https://getin.pc.sc.gov.br/sendmail/");
}


$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>