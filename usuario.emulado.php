<?php
// ESSA PÁGINA EXISTE PARA TESTAR A PÁGINA COMO SE FOSSE OUTRO USUÁRIO, PARA VERIFICAR APONTAMENTOS DE ERROS ESPECÍFICOS
session_start();
include "mysql.conecta.rep.php";
// REDEFINE SESSÕES DE VARIÁVEL PARA TESTE

$_SESSION['usuario_fis_is_delegado'] = 1;

$_SESSION['usuario_fis_nome'] = '[EMULADO] Fernando Henrique Guzzi';
$_SESSION['usuario_fis_cpf'] = '05862467971';
$_SESSION['usuario_for_status'] = "normal";

$_SESSION['usuario_emulado'] = 1;


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

ECHO "<a href ='pedidos.lista.php'>ir para lista de pedidos</a>";

?>