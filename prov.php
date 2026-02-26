<?php

include "mysql.conecta.rep.php";

$query = "select * from tb_documentos_tipo order by nome asc";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $id = $row[id];
            $nome =  (stripslashes($row[nome]));

            echo "<p>$id - $nome</p><hr>";


              
        }

?>