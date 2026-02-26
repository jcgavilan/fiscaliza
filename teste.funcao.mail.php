<?php

$mail = mail('marcus-lopes@pc.sc.gov.br', 'Meu Assunto', "texto da minha mensagem");
if (!$mail) {
    echo "não mandou";
}else{
    echo "diz que foi";
}

?>