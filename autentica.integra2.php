<?php

header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");
header('P3P: CP="CAO PSA OUR"');
session_start();

if (!isset($_GET['fase_acesso'])) {
    // FASE 1 - RECEBE O EVENTO E CRIA O COOKIE PARA PASSAR PARA O PHP
    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>

        <title>DESAPARECIDOS</title>
        <script>
            window.parent.postMessage("ask for credentials", "*");

            window.addEventListener("message", function (event) {
                //console.log(event.data);
                document.cookie = `token=${event.data.tokenWizard}`;
                document.cookie = `recursos=${event.data.recursos}`;

            });
        </script>

        <meta http-equiv="refresh" content="0; URL='autentica.integra2.php?fase_acesso=1'" />

    </head>

    <body>
    </body>

    </html>

    <?php
}


if ($_GET[fase_acesso] == 1) {

    // RECEBE O COOKIE E FAZ A VERIFICAÇÃO DO WIZARD
    $token = $_COOKIE['token'];

    $recursos = $_COOKIE['recursos'];

    print_r($recursos);
    $r = json_decode($recursos);
    // echo "<hr>-1->".$r[0]->aliasRecurso;

    $alias = $r[1]->aliasRecurso;
    if ($alias == "fiscaliza-adm") {
        //   echo "<h1>admin logado</h1>";
        $_SESSION['usuario_for_status'] = "adm";
    } else {
        $_SESSION['usuario_for_status'] = "normal";
    }



    $token = $_COOKIE['token'];

    //echo $token;

    $url = "https://getin.pc.sc.gov.br/api_usuarios/graphql";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $post =
        [
            "query" => '
        {
          getTokenWizard_Usuarios(
            tokenwizard: "' . $token . '"
          ) {
            status
            msn
            iat
            exp
            iswizard
            #FIELDS
            id
            cpf
            nome
            lotacao_ibge
            cargo
            lotacao_nome
          }
        }
        '
        ];


    $data = json_encode($post);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $resp = curl_exec($curl);
    $resp = json_decode($resp);
    //echo $resp;

    $cpf = $resp->data->getTokenWizard_Usuarios->cpf;
    $nome_usuario = $resp->data->getTokenWizard_Usuarios->nome;
    $cargo_usuario = $resp->data->getTokenWizard_Usuarios->cargo;
    $lotacao_ibge = $resp->data->getTokenWizard_Usuarios->lotacao_ibge;
    $lotacao_nome = $resp->data->getTokenWizard_Usuarios->lotacao_nome;

    // echo "<h1>CPF: $cpf</h1>";
    // echo "<h1>cargo: $cargo_usuario</h1>";
    // echo "<h1>nome: $nome_usuario</h1>";
    // echo "<h1>lotação: $lotação_nome</h1>";   para DEBUG Júlio 29/10/2025

    // die;

    curl_close($curl);

    $_SESSION['usuario_fis_nome'] = $nome_usuario;
    $_SESSION['usuario_fis_cpf'] = $cpf;

    if ($cpf == '08151009810') {

        $_SESSION['usuario_fis_is_delegado'] = 1;
        $cargo = 'DELEGAD';
        $lotacao_ibge = 421565;
        // echo "entrou";
        // die;

        // echo "<h1>CPF: $cpf</h1>";
        // echo "<h1>cargo: $cargo</h1>";
        // echo "<h1>nome: $nome_usuario</h1>";
        // echo "<h1>lotação: $lotacao_ibge</h1>";   //para DEBUG Júlio 29/10/2025

        // die;

    }
    // $_SESSION['usuario_fis_ibge'] = $lotacao_ibge;
    $_SESSION['usuario_fis_lotacao'] = $lotacao_nome;

    $is_delegado = strpos($cargo_usuario, 'DELEGAD');

    //conexão com a base de dados
    include "mysql.conecta.rep.php";

    if ($is_delegado === false) {
        $_SESSION['usuario_fis_is_delegado'] = 0;
    } else {
        $_SESSION['usuario_fis_is_delegado'] = 1;

        // para o caso de login de delegado, verifica se não há mudança de lotação necessária por conta de função gratificada (caso do delegado de games and fun)


        // $query = "select lotacao_especial, lotacao_especial_nome from tb_usuarios_lotacao_especial where cpf = '".$_SESSION['usuario_fis_cpf']."'";
        // $result = mysqli_query($link, $query);
        // $num = mysqli_num_rows($result);
        // if ($num != 0) {
        //     $row = mysqli_fetch_array($result);
        //     $_SESSION['usuario_fis_ibge'] = $row[lotacao_especial];
        //     $_SESSION['usuario_fis_lotacao'] = $row[lotacao_especial_nome];
        // }
    }

    // verifica se o usuário está habilitado para a geração de carteiras, e acessar o painel.
    $query = "select cpf from tb_carteiras_auth where cpf = '" . $_SESSION['usuario_fis_cpf'] . "'";
    // echo $query;
    $result = mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    if ($num != 0) {
        $row = mysqli_fetch_array($result);
        $_SESSION['usuario_fis_carteiras_auth'] = $row[cpf];
    }

    // define quais municipios o usuário está setado para ver.

    $query = "select id from tb_lotacao_expandida where cpf = '" . $_SESSION['usuario_fis_cpf'] . "'";
    $result = mysqli_query($link, $query);
    $num = mysqli_num_rows($result);

    if ($num != 0) {
        $row = mysqli_fetch_array($result);
        $id_usuario = $row[id];

        $query = "select ibge from tb_lotacao_expandida_vinculos where id_usuario = $id_usuario"; // assim, mesmo que o municipio seja abrangido por outro, os policiais locais continuam tendo acesso
        $result = mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for ($i = 0; $i < $num; $i++) {
            $row = mysqli_fetch_array($result);
            $id = $row[id];
            $ibge = stripslashes($row[ibge]);
            if ($i == 0) {
                $municipios_ref = $ibge;
            } else {
                $municipios_ref .= ", " . $ibge;
            }

        }
        $_SESSION['usuario_fis_ibge'] = $municipios_ref;
    }

    setcookie("nome", "", time() - 3600);

    header("location: pedidos.lista.php"); // ANTES DIRECIONAVA PARA INDEX.PHP, MAS COMO NÃO TEM MAIS A IDENTIFICAÇÃO MANUAL DO MUNICIPIO, NÃO HÁ NECESSIDADE DESTA CAMADA IMTERMEDIÁRIA

}


if ($_GET[fase_acesso] == 2) {

    if (!isset($_COOKIE['name'])) {
        echo "cookie já era";
    } else {
        echo "still alive!!! KILL!!!!!!!!!";
    }

}


?>