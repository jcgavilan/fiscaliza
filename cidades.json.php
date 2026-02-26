<?php

include "mysql.conecta.rep.php";

$dadosJson = file_get_contents('cidades.ibge.js');
//echo $dadosJson;

$a = explode("*", $dadosJson);

for ($i=0; $i < count($a); $i++) { 
    //echo "<hr>".$a[$i];
    $b = $a[$i];

    $c = explode("&", $b);

    echo "<hr>".$c[0]."<br>".$c[1];

    $nome = $c[0];
    $ibge = $c[1];

    $query = "insert into tb_municipios_ibge (nome, ibge) values ('$nome', '$ibge')";
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
    }
}

?>