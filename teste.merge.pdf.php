<?php
$id_pedido = 392;
$output = shell_exec("./many2pdf.py -id $id_pedido -o NOME_ARQUIVO_SAIDA.pdf  2>&1");
if(!$output){
    echo "OOOOOOOOOOOOOOps";
}else{
    echo "rodou";
    echo "<br><hr>";
    echo "<a href = 'arquivos_cidadao_prev/NOME_ARQUIVO_SAIDA.pdf'>CLIQUE PARA VER O ARQUIVO<a>";
}

?>