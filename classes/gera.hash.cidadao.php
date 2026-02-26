<?php

function gera_str($num, $tipo){

    $str_str = "abcdefghijkmnopqrstuvxywz";
    $str_num = "23456789";

    switch ($tipo) {
        case 'string':
            $str = $str_str;
            break;

        case 'num':
            $str = $str_num;
            break;

        case 'tudo':
            $str = $str_str.$str_num;
            break;

    }

    $s = '';

    for ($i=0; $i < $num; $i++) { 
        
        $mix = str_shuffle($str);
        $s .= $mix[0];
    }

    return $s;

}

$hash = gera_str(9, 'tudo').'h'.gera_str(4, 'tudo').gera_str(1, 'num').'c'.gera_str(3, 'tudo').gera_str(1, 'num').'d'.gera_str(2, 'num').gera_str(20, 'tudo').gera_str(1, 'num').gera_str(5, 'tudo');
$senha_cidadao = gera_str(3, 'string').gera_str(3, 'num');
// echo $hash;

// $hash = 'gut7qx3g8hhao8ccfg97d7595jepjxabdx45z3gnj6w62gfox';

// echo "<hr>";
// echo "<br>pos 9 ->".$hash[9]." - h";
// echo "<br>pos 14 ->".$hash[14]." - num";
// echo "<br>pos 15 ->".$hash[15]." - c";
// echo "<br>pos 19 ->".$hash[19]." - num";
// echo "<br>pos 20 ->".$hash[20]." - d";
// echo "<br>pos 22 ->".$hash[22]." - num";
// echo "<br>pos 43 ->".$hash[43]." - num";

 function valida_hash($hash){

    $valido = 1;

    $n = $hash[9]; 
    if ($n != 'h') {$valido = 0;}
    $n = $hash[14];
    if( $n=='2' || $n=='3' || $n=='4' || $n=='5' || $n=='6' || $n=='7' || $n=='8' || $n=='9'){$nada = 1;}else{$valido = 0;}
    $n = $hash[15]; 
    if ($n != 'c') {$valido = 0;}
    $n = $hash[19];
    if( $n=='2' || $n=='3' || $n=='4' || $n=='5' || $n=='6' || $n=='7' || $n=='8' || $n=='9'){$nada = 1;}else{$valido = 0;}
    $n = $hash[20]; 
    if ($n != 'd') {$valido = 0;}
    $n = $hash[22];
    if( $n=='2' || $n=='3' || $n=='4' || $n=='5' || $n=='6' || $n=='7' || $n=='8' || $n=='9'){$nada = 1;}else{$valido = 0;}
    $n = $hash[43];
    if( $n=='2' || $n=='3' || $n=='4' || $n=='5' || $n=='6' || $n=='7' || $n=='8' || $n=='9'){$nada = 1;}else{$valido = 0;}

    //código nada elegante, mas bem efetivo
   
    return $valido;
}

// $valido = valida_hash($hash);

// echo "<h1>Válido: $valido</h1>";








?>