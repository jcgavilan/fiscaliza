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

$id_estabelecimento = filter_var($_GET['id_estabelecimento'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);
    echo "<h2>Lista de Alvarás</h2><hr><br>";

 
    
    // BUSCA OS NOMES DOS DOCUMENTOS REQUERIDOS
    $docs = array();
    $query = "select id, nome from tb_documentos_tipo order by nome asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $id = $row[id];
                $docs[$id] =  (stripslashes($row[nome]));          
            }

    $docs_id = array();
    $query = "select * from tb_documentos_requeridos ";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $id_alvara = $row[id_alvara];
                $id_documento_tipo = $row[id_documento_tipo];
                $docs_id[$id_alvara][] = $id_documento_tipo;          
            }


    $query = "select id, nome from tb_alvaras_tipo order by nome asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $id_alvara = $row[id];
                $nome =  (stripslashes($row[nome]));
                echo "<br><br><h4>$nome &nbsp <a href = 'gera.alvara.prev.php?id_estabelecimento=$id_estabelecimento&id_alvara=$id_alvara' class='btn btn-primary'>GERAR PEÇA</a></H4>";

                echo "<ul>";
                for($k=0;$k<count($docs_id[$id_alvara]);$k++) 
                {
                    $id_documento = $docs_id[$id_alvara][$k];
                    echo "<li>".$docs[$id_documento]."</li>";
                }
                echo "</ul>";

                       
            }



            $f -> fecha_card();
               
            $f -> fecha();
            
            $f -> abre(4);
 

            $f -> fecha();
            
            $f -> fecha();
            
            
    $footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();




?>