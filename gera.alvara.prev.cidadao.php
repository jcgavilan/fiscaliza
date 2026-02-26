<?php

session_start();

if(!isset($_SESSION["id_usuario_des"]))
{
  //header('Location: login.php');
  //exit();
}

include "classes/class.html.php";
include "classes/classe.forms.php";
include "mysql.conecta.rep.php";

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);
    echo "<h2>Geração de Documento - Dados adicionais</h2><hr><br>";


    $id_pedido = $_POST[id_pedido];
    settype($id_pedido, 'integer');

    //busca o nome do alvará;

    $query = "select tipo_pedido, nome_estabelecimento from tb_cidadao_pedidos where id = $id_pedido";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $id_alvara =  (stripslashes($row[tipo_pedido]));
    $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));


    // $query = "select nome from tb_alvaras_tipo where id = ".$id_alvara;
    // $result=mysqli_query($link, $query);
    // $row = mysqli_fetch_array($result);
    // $nome_alvara =  (stripslashes($row[nome]));

    // $query = "select nome_estabelecimento from tb_estabelecimentos where id = ".$id_estabelecimento;
    // $result=mysqli_query($link, $query);
    // $row = mysqli_fetch_array($result);
    // $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));

    $now = time();
    $data_expedicao = date("d/m/Y", $now);

    $f -> abre_form("gera.alvara.cidadao.php");
    echo "<input type='hidden' name='id_pedido' value='$id_pedido'>"; 
    echo "<input type='hidden' name='id_alvara' value='$id_alvara'>"; 
    echo "<h3>Estabelecimento: $nome_estabelecimento</h3>";
    $f -> f_input("Código da Unidade Policial", "cod_unid_policial", "");
    $f -> f_input("Taxa Estadual", "taxa_estadual", "");
    $f -> f_input("Data de Expedição", "data_expedicao", "$data_expedicao");
    $f -> f_input_mask("Data de Validade do Alvará", "data_validade", "data");
    
    // BUSCA CAMPOS ESPECIAIS A SEREM PREENCHIDOS PELO POLICIAL, SE HOUVER

    $query = "select * from tb_campos_especiais_policia where id_alvara = $id_alvara"; 
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $campo_nome = $row[campo_nome];
                $campo_label =  stripslashes($row[campo_label]);
                $value_prev =  stripslashes($row[value_prev]);
                $f -> f_input("$campo_label", "$campo_nome", "$value_prev");
            }


    $f->f_button("GERAR ALVARÁ");

    echo "</form>";


    $f -> fecha_card();
        
    $f -> fecha();
    
    $f -> abre(4);


    $f -> fecha();
      
    $f -> fecha();
    
          
    $footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();




?>