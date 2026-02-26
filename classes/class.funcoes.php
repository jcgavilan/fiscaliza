<?php

class Funcoes {
   
    function __construct() {}
      
    function dispara_senha($email, $cpf) {
        
        // GERA SENHA
        $a = "ABCEFGHJKMNPRTUXYW2346789";
        $a = str_shuffle($a);
        $senha_prov = substr($a, 0, 4);
       
        $query = "update galeria set titulo = '$titulo', data = $data, descricao = '$descricao' where id = ".$_GET[id];
        $result = mysql_query($query);
        if(!$result)
         {echo "<br><p><b>Não foi possível atualizar o Cadastro do banco de dados </b></p>";}
        else
         {echo "<br><p>Cadastro atualizado com sucesso</p>";}
        
         //email: recuperasenhapcsc@gmail.com
         //senha: 24falha7
        
    }
}



?>