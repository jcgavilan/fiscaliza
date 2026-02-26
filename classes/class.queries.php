<?php

class Queries {
    
    function __construct() {} // para prevenir a duplicaï¿½ï¿½o do print
    
    
    
    function Meucadastro_atualiza($post_array, $id_empresa) {
        $razao_social = filter_var($post_array["razao_social"], FILTER_SANITIZE_STRING);
        $nome_fantasia = filter_var($post_array["nome_fantasia"], FILTER_SANITIZE_STRING);
        $endereco = filter_var($post_array["endereco"], FILTER_SANITIZE_STRING);
        $atividade_economica = filter_var($post_array["atividade_economica"], FILTER_SANITIZE_STRING);
        $nome_representante = filter_var($post_array["nome_representante"], FILTER_SANITIZE_STRING);
        $data_nascimento = filter_var($post_array["data_nascimento"], FILTER_SANITIZE_STRING);
        $cpf = filter_var($post_array["cpf"], FILTER_SANITIZE_STRING);
        $municipio_nascimento = filter_var($post_array["municipio_nascimento"], FILTER_SANITIZE_STRING);
        $nome_mae = filter_var($post_array["nome_mae"], FILTER_SANITIZE_STRING);
        $endereco_representante = filter_var($post_array["endereco_representante"], FILTER_SANITIZE_STRING);
        $fone = filter_var($post_array["fone"], FILTER_SANITIZE_STRING);
        $email = filter_var($post_array["email"], FILTER_SANITIZE_STRING);
        $outros_representante = filter_var($post_array["outros_representante"], FILTER_SANITIZE_STRING);
        $dados_outros = filter_var($post_array["dados_outros"], FILTER_SANITIZE_STRING);

        
        $query = "update empresa_cadastro set razao_social = '$razao_social',  nome_fantasia = '$nome_fantasia',  endereco = '$endereco',  atividade_economica = '$atividade_economica', nome_representante = '$nome_representante', data_nascimento = '$data_nascimento', cpf = '$cpf', municipio_nascimento = 'municipio_nascimento',  nome_mae = '$nome_mae',  endereco_representante = '$endereco_representante', fone = '$fone',  outros_representante = '$outros_representante',  dados_outros = '$dados_outros' where id_empresa = ".$id_empresa;
        $result = mysql_query($query);
        if(!$result)
        {   echo " <div class='alert alert-danger' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>ATENÇÃO: Não foi possível atualizar o Cadastro <br>$query<br> </h4>".mysql_error();
            echo "</div>";
        }else{
            echo " <div class='alert alert-primary' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>Cadastro atualizado com Sucesso! </h4>";
            echo "</div>";
        }     
    }
    
    
    function Aut_evento_insere($post_array, $id_empresa) {
        $nome = filter_var($post_array["nome"], FILTER_SANITIZE_STRING);
        $mod_competicao = filter_var($post_array["mod_competicao"], FILTER_SANITIZE_STRING);
        $endereco = filter_var($post_array["endereco"], FILTER_SANITIZE_STRING);
        data_inicio = filter_var($post_array["data_inicio"], FILTER_SANITIZE_STRING);
        data_fim = filter_var($post_array["data_fim"], FILTER_SANITIZE_STRING);
        $capacidade_local = filter_var($post_array["capacidade_local"], FILTER_SANITIZE_STRING);
        cobrado_ingresso = settype($post_array["cobrado_ingresso"], FILTER_SANITIZE_STRING);
        $municipio_nascimento = filter_var($post_array["municipio_nascimento"], FILTER_SANITIZE_STRING);
        $nome_mae = filter_var($post_array["nome_mae"], FILTER_SANITIZE_STRING);
        $endereco_representante = filter_var($post_array["endereco_representante"], FILTER_SANITIZE_STRING);
        $fone = filter_var($post_array["fone"], FILTER_SANITIZE_STRING);
        $email = filter_var($post_array["email"], FILTER_SANITIZE_STRING);
        $outros_representante = filter_var($post_array["outros_representante"], FILTER_SANITIZE_STRING);
        $dados_outros = filter_var($post_array["dados_outros"], FILTER_SANITIZE_STRING);
        
        
        $query = "update empresa_cadastro set razao_social = '$razao_social',  nome_fantasia = '$nome_fantasia',  endereco = '$endereco',  atividade_economica = '$atividade_economica', nome_representante = '$nome_representante', data_nascimento = '$data_nascimento', cpf = '$cpf', municipio_nascimento = 'municipio_nascimento',  nome_mae = '$nome_mae',  endereco_representante = '$endereco_representante', fone = '$fone',  outros_representante = '$outros_representante',  dados_outros = '$dados_outros' where id_empresa = ".$id_empresa;
        $result = mysql_query($query);
        if(!$result)
        {   echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>ATENÇÃO: Não foi possível atualizar o Cadastro <br>$query<br> </h4>".mysql_error();
        echo "</div>";
        }else{
            echo " <div class='alert alert-primary' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>Cadastro atualizado com Sucesso! </h4>";
            echo "</div>";
        }
    }   
}


/*
 CREATE TABLE aut_evento (
 id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 id_empresa int not null,
 data_solicitacao int not null,
 ip VARCHAR(16) NOT NULL default '',
 nome VARCHAR(120) NOT NULL default '',
 mod_competicao VARCHAR(120) NOT NULL default '',
 endereco VARCHAR(120) NOT NULL default '',
 data_inicio VARCHAR(30) NOT NULL default '',
 data_fim VARCHAR(16) NOT NULL default '',
 capacidade_local VARCHAR(30) NOT NULL default '',
 cobrado_ingresso int NOT NULL,
 ingressos_vendidos VARCHAR(20) NOT NULL default '',
 policiamento_ostensivo int NOT NULL,
 ingresso_menores int NOT NULL,
 bebida_alcoolica int NOT NULL,
 queima_fogos int NOT NULL,
 construcao VARCHAR(250) NOT NULL default ''
 )
 */

?>