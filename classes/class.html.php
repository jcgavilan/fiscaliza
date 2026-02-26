<?php 

class Menu_adm {
    function Menu_adm($link) {
        ?>
        
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-white" id = "menu_principal" style = "overflow-y: hidden; background-color:#333333;">
        
            <div class="menu-list" style = "overflow-y: hidden;">
                <div id = "recolhe_menu" style = 'float: right;margin:6px;'><h2><a href = "#" onclick="recolhe_menu()"><i class='icon-arrow-left-circle'></i></a></h2></div>
                <div id = "expande_menu" style = 'float: right;margin:6px;'><h2><a href = "#" onclick="expande_menu()"><i class='icon-arrow-right-circle'></i></a></h2></div>
                <nav class="navbar navbar-expand-lg navbar-light" style = "overflow-y: hidden;">
                    <a class="d-xl-none d-lg-none" href="#"> <img src = "brasao.png" height="10px"> &nbsp;PCSC <br>FISCALIZA</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column" style = "margin-top: 6px;">
                        <img src = "brasao.png" height="100px" width="82px" style = "margin-left:auto; margin-right:auto;"> 
                        <div style = "width: 100%; text-align:center;"><h3 style = 'color: #a7a7a7; padding-top: 12px; padding-bottom:18px'>PCSC<BR>FISCALIZA</h3><!--<h5>Repositório de Arquivos</h5>--></div>
                            


                        <?php
                            $n_novos = 0;
                            $n_concluidos = 0;
                                
                            //$query = "select * from tb_cidadao_pedidos where concluido = 0 ";
                                
                            /*$query = "select * from tb_cidadao_pedidos where concluido = 0 AND ( (id_policial = ''  and id_municipio in (".$_SESSION['usuario_fis_ibge']."))  OR ( id_policial = '".$_SESSION['usuario_fis_cpf']."') ) ";
                            $result=mysqli_query($link, $query);
                                $num = mysqli_num_rows($result);
                                for($i=0;$i<$num;$i++) 
                                {
                                    $row = mysqli_fetch_array($result);
                                    $status = $row[status]; 

                                    if ($status < 2) { // verifica o status, sendo que 2 indica que foi para o delegado.
                                        $n_novos++;
                                    }else{
                                        $n_concluidos++;
                                    }
                            }*/
     
                            //  echo "<li class='nav-item'><a class='nav-link' href='#'><i class='icon-plus'></i>Portal Cidadão <span style = 'color: #cc0000; font-size:14px;'>($num)<span></a></li>";
                              
                            echo " <li class='nav-item'>";
                            echo "<a class='nav-link' href='formulario.interno.cidadao.prev.php'>  Formulário / Alvarás<span style = 'color: #cc0000; font-size:14px;'><span></a>";
                            echo " </li>";
                        ?>
      
                        <li class="nav-item"><a class="nav-link" href="pedidos.lista.php"> </i> Fila de Análise Policial <!--<span style = 'color: #cc0000; font-size:14px;'> (<?php echo $n_novos;?>)<span>--> </a></li>
                                
                        <?php
                                        
                                       
                            if ($_SESSION['usuario_fis_is_delegado'] == 1 || $_SESSION['usuario_fis_cpf'] == '02200965974' || $_SESSION['usuario_fis_cpf'] == '08151009810') { // QUE HARDCODE MAIS FEIO, MAS TEM QUE TESTAR NÉ
                                echo " <li class='nav-item'>";
                                echo "<a class='nav-link' href='delegado.painel2.php'>  Fila de Homologação do Delegado&nbsp; </a>"; //<span style = 'color: #cc0000; font-size:14px;'> ($n_concluidos)<span>
                                echo " </li>";
                            }
                            // PARA PESQUISA, TEM QUE SER 
                            //       if($_SESSION['usuario_for_status'] == "adm" || isset($_SESSION['usuario_especial_restricao'])) {
                            echo " <li class='nav-item'>";
                            echo "<a class='nav-link' href='pesquisa.php'>  Pesquisar Alvarás&nbsp; <span style = 'color: #cc0000; font-size:14px;'> <span></a>";
                            echo " </li>";

                            if ($_SESSION['usuario_fis_cpf'] == '02200965974' || $_SESSION['usuario_fis_cpf'] == '00235191000') {
                                echo " <li class='nav-item'>";
                                echo "<a class='nav-link' href='monitoramento.php'>  Monitoramento&nbsp;</a>";
                                echo " </li>";
                            }
                            //    }

                            if(isset($_SESSION['usuario_fis_carteiras_auth']) && ($_SESSION['usuario_fis_carteiras_auth'] == $_SESSION['usuario_fis_cpf']) ) {
                                // esse filtro é para mostrar o menu somente para quem está autorizado a gerar carteiras - tabela tb_carteiras_auth
                                echo " <li class='nav-item'>";
                                echo "<a class='nav-link' href='carteira.base.php'>  Carteiras<span style = 'color: #cc0000; font-size:14px;'><span></a>";
                                echo " </li>";
                            }
                                           

                            // echo " <li class='nav-item'>";
                            // echo "<a class='nav-link' href='cadastro.alvara.prev.php'>  Cadastro direto<span style = 'color: #cc0000; font-size:14px;'><span></a>";
                            // echo " </li>";

                            if($_SESSION['usuario_for_status'] == "adm") {

                                echo " <li class='nav-item'>";
                                echo "<a class='nav-link' href='usuarios.lotacao.php'>  Usuários e Lotações&nbsp; <span style = 'color: #cc0000; font-size:14px;'> <span></a>";
                                echo " </li>";

                                //    echo " <li class='nav-item'>";
                                //    echo "<a class='nav-link' href='gestores.gestao.php'>  Usuários DRP&nbsp; <span style = 'color: #cc0000; font-size:14px;'> <span></a>";
                                //    echo " </li>";
                            }

                            //}  
                                        
                        ?>
                        <!--    <li class="nav-item "><a class="nav-link" href="unidade.php"><i class='icon-plus'></i>Minha Unid. Policial</a></li>
                                <li class="nav-item "><a class="nav-link" href="logout.php"><i class='icon-logout'></i>Sair</a></li>  logout.php --> 
                        
                        <?php 
                                                           
                            $versao = "1.0";
                            if ($_SESSION['seletor_responsivo'] == "mobile") {
                                echo "<div style = 'position: absolute; bottom: 0px; right:0px; padding: 10px; color: #cccccc; font-size: 11px;'>Versão $versao &nbsp;</div>";
                            }    
                                 
                                
                             
                             
                        ?>
                              
                     
                        </ul>

                    </div>
                </nav>
                <?php 
                                    
                    if ($_SESSION['seletor_responsivo'] == "web") {
                        echo "<div style = 'position: absolute; bottom: 130px; right: 12px; color: #ffffff; font-size: 11px;'>Versão $versao</div>";
                    }                        

                ?>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style = 'position: absolute; bottom: 0px; right:0px; padding: 10px; color: #B8B8B8; font-size: 11px; text-align:center;'>
                    Desenvolvido por:<br>Gerência de Tecnologia da Informação<br>Policia Civil de Santa Catarina               </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <?php
    }
}


class Header_adm {
    function Header_adm() {
        ?>
        
        <!doctype html>
        <html lang="pt">

        <head>
            <!-- Global site tag (gtag.js) - Google Analytics 
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-171158988-1"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-171158988-1');
            </script>-->
            <!-- Required meta tags --> 
            <meta charset="utf-8">
            <meta file = "class.html.php">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
            <link href="assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
            <link rel="stylesheet" href="assets/libs/css/style.css">
            <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
            <link rel="stylesheet" href="assets/vendor/fonts/simple-line-icons/css/simple-line-icons.css">
            <link rel="stylesheet" href="assets/vendor/charts/chartist-bundle/chartist.css">
            <link rel="stylesheet" href="assets/vendor/charts/morris-bundle/morris.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
            <link rel="stylesheet" href="assets/vendor/charts/c3charts/c3.css">
            <link rel="stylesheet" href="assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
            <title>Jogos e Diversões</title>
            <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
            <link rel="manifest" href="favicon/site.webmanifest">
            <meta name="msapplication-TileColor" content="#da532c">
            <meta name="theme-color" content="#ffffff">


        </head>


        <body>
            <!-- ============================================================== -->
            <!-- main wrapper -->
            <!-- ============================================================== -->
            <!--  <div class="dashboard-main-wrapper">
            ============================================================== -->
            <!-- navbar -->
            <!-- ============================================================== -->
            <!--  <div class="dashboard-header">
                <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="index.html" >
                <img src = "http://getin.pc.sc.gov.br/covid19/images/brasao.png" height="50px"> 
                &nbsp;PCSC </p></a>
                
                
            </nav>
            </div> 
            </div>-->
            <!-- ============================================================== -->
            <!-- end navbar -->
            <!-- ============================================================== -->
        <?php
    }
}


class Header_adm_WEB {
    function Header_adm_WEB() {
        ?>
        
        <!doctype html>
        <html lang="pt">
        
        <head>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-171158988-1"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', 'UA-171158988-1');
            </script>
                <!-- Required meta tags --> 
            <meta charset="utf-8">
            <meta file = "class.html.php">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <!-- Bootstrap CSS -->
            <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
            <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
            <link href="assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
            <link rel="stylesheet" href="assets/libs/css/style.css">
            <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
            <link rel="stylesheet" href="assets/vendor/fonts/simple-line-icons/css/simple-line-icons.css">
            <link rel="stylesheet" href="assets/vendor/charts/chartist-bundle/chartist.css">
            <link rel="stylesheet" href="assets/vendor/charts/morris-bundle/morris.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
            <link rel="stylesheet" href="assets/vendor/charts/c3charts/c3.css">
            <link rel="stylesheet" href="assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
            <title>FISCALIZA</title>
            <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
            <link rel="manifest" href="favicon/site.webmanifest">
            <meta name="msapplication-TileColor" content="#da532c">
            <meta name="theme-color" content="#ffffff">

            <script >
                showPdf = function(path){
                    document.getElementById("show").innerHTML = "<object data='../uploads/" + path + "' type='application/pdf' style = 'width:100%; height:680px;'><embed src='../uploads/" + path + "' type='application/pdf' /></object>"; 
                }
                showVideo = function(path){
                    document.getElementById("show").innerHTML = "<div style = 'background-color: #666666; width:100%; min-height:680px; padding: 30px; text-align:center;'><p><video width='100%' controls><source src='../uploads/" + path + "' type='video/mp4'></video></div></p>"; 
                }
            </script>

        </head>

        <body>
            <!-- ============================================================== -->
            <!-- main wrapper -->
            <!-- ============================================================== -->
            <!--  <div class="dashboard-main-wrapper">
            ============================================================== -->
                <!-- navbar -->
                <!-- ============================================================== -->
            
                
            <!--TOPO DO TEMPLATE ORIGINAL
                <div class="dashboard-header">
                    <nav class="navbar navbar-expand-lg bg-white fixed-top">
                    <a class="navbar-brand" href="index.php" >
                    <img src = "http://getin.pc.sc.gov.br/covid19/images/brasao.png" height="40px"> 
                    &nbsp; PCSC - Repositório de Arquivos</p></a>
                        
                        
                    </nav>
                </div> 
            </div> --> 


                <!-- ============================================================== -->
                <!-- end navbar -->
                <!-- ============================================================== -->
        <?php
    }
}


class Abre_titulo {
    
    function __construct() {} // para prevenir a duplicação do print
   
    function titulo_pagina($nome_pagina) {
        ?>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper" id = "div_total_prev" style = 'background-color: #e1e1e1;'>
            <div class="container-fluid dashboard-content" id = "div_total" >
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" 
                    <?php 
                    if ($_SESSION['seletor_responsivo'] == "web") {
                      // echo " style = 'padding-top:50px;'";
                    }
                    ?>
                   >
                       <!-- <div class="page-header" >
                            <h4 class="pageheader-title"><?php //echo $nome_pagina?> &nbsp;</h4>
                           
                            
                        </div>-->
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  >
        <?php

        //echo "<h2>Sistema de RPCA</h2>";

    }
}


class Form_desbloqueio {
    
    function __construct() {} // para prevenir a duplicação do print
    
    function Form_desbloqueio($mensagem, $link) {
        ?>
        
        <!doctype html>
        <html lang="en">
        
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>Jogos e Diversões</title>
            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
            <link href="assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
            <link rel="stylesheet" href="assets/libs/css/style.css">
            <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
            <style>
                html,
                body {
                    height: 100%;
                }

                body {
                    display: -ms-flexbox;
                    display: flex;
                    -ms-flex-align: center;
                    align-items: center;
                    padding-top: 40px;
                    padding-bottom: 40px;
                }
            </style>
    
            <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
            <link rel="manifest" href="favicon/site.webmanifest">
            <meta name="msapplication-TileColor" content="#da532c">
            <meta name="theme-color" content="#ffffff">
        </head>

        <body>
        <!-- ============================================================== -->
        <!-- login page  -->
        <!-- ============================================================== -->
            <div class="splash-container">
            <?php 
            
                if(strlen($mensagem) > 1){
                    echo "<div class='alert alert-danger' role='alert' style = 'text-align:center;'>".$mensagem."</div>";
                }
            
            ?>
                <div class="card ">
                    <div class="card-header text-center"><img src = "brasao.png" height="80px "><h2 style ='padding-TOP:20px;'>PC/SC FISCALIZA</h2><h3>Desbloqueio de usuário</h3></div>
                    <div class="card-body">
                        <form action='desbloqueiasenha.conclui.php?autentica=1' method='post'>
                            <div class="form-group">
                                <input class="form-control form-control-lg" id="password1" type="password" name="password1" placeholder="Informe sua nova senha (máx. 6 caracteres)" maxlength="6">
                            </div>
                            
                            <div class="form-group">
                                <input class="form-control form-control-lg" id="password2" type="password" name="password2" placeholder="Confirme sua nova senha" maxlength="6">
                            </div>
                            <div class="form-group">
                        
                                <?php 
                                
                                    echo "<div class='form-group'>";
                                    //  echo "<label for='inputText3' class='col-form-label'></label><br>";
                                    echo "<select class='form-control' id='id_regional' name='id_regional'>";
                                    echo "<option value=''>INFORME AQUI SUA REGIONAL</option>";
                                    $query = "select id, nome from fiscal_regionais order by nome asc";
                                    $result = mysqli_query($link, $query);
                                    $num = mysqli_num_rows($result);
                                    for($i=0;$i<$num;$i++)
                                    {
                                        $row = mysqli_fetch_array($result);
                                        $id = $row[id];
                                        $nome = utf8_encode(stripslashes($row[nome]));
                                        echo "<option value='".$id."'>".$nome."</option>";
                                    }
                                    echo "</select></div>";
                                ?>
                            
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">SALVAR DADOS</button>
                        </form>
                    </div>
                    <div class="card-footer bg-white p-0  ">
                        <!--          <div class="card-footer-item card-footer-item-bordered">
                            <a href="desbloqueiasenha.form.php" class="footer-link">Desbloquear minha senha</a></div>
                        <div class="card-footer-item card-footer-item-bordered">
                        <a href="#" class="footer-link">Esqueci a Senha</a>
                        </div> --> 
                    </div>
                </div>
                <center>Gerência de Tecnologia da Informação</center>
                <center>Polícia Civil de Santa Catarina</center>
            </div>
  
            <!-- ============================================================== -->
            <!-- end login page  -->
            <!-- ============================================================== -->
            <!-- Optional JavaScript -->
        
            <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
            <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
            <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
            <script src="assets/vendor/slimscroll/jquery.slimscroll.js"></script>
            <script src="assets/libs/js/main-js.js"></script>
            <script src="assets/vendor/inputmask/js/jquery.inputmask.bundle.js"></script>
            <script>
                $(function(e) {
                    "use strict";
                
                        $(".cc-inputmask").inputmask("999-999-999-99"),


                        $(".email-inputmask").inputmask({
                            mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
                            greedy: !1,
                            onBeforePaste: function(n, a) {
                                return (e = e.toLowerCase()).replace("mailto:", "")
                            },
                            definitions: {
                                "*": {
                                    validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
                                    cardinality: 1,
                                    casing: "lower"
                                }
                            }
                        })
                });
            </script>
        </body>
 
    </html>
    <?php
    }
}


class Abre_titulo_NOADM {
    
    function __construct() {} // para prevenir a duplicaÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½o do print
    
    function Abre_titulo_NOADM($nome_pagina) {
        ?>

        <div >
            <div class="container-fluid dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title" style = "padding-left:14px;"><?php echo $nome_pagina?> </h2>
                            <p class="pageheader-text">onde vai esse texto?</p>
                           
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <?php
    }
}
class Footer_adm {
    
    function __construct() {} // para prevenir a duplicaÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½o do print
    
    function Footer_adm() {
        ?>
        </div><!-- esse div é só para fechar a que abri para alinhar o conteúdo-->
        </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                         
                             <p align = "center">Gerência de Tecnologia da Informação</p>
                            <!--    <p align = "center">Policia Civil de Santa Catarina</p>-->
                        </div>
                         <!-- <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end main wrapper -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
     <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="assets/libs/js/main-js.js"></script>
    <script src="assets/vendor/inputmask/js/jquery.inputmask.bundle.js"></script>
    <script src="assets/vendor/parsley/parsley.js"></script>
   <script src="js/var_cidade_estado.js"></script>
    
    <script>
    $(function(e) {
        "use strict";
        $(".cc-inputmask").inputmask("999-999-999-99"),
        $(".cnpj-inputmask").inputmask("99-999-999/9999-99"),
        $(".date-inputmask").inputmask("99/99/9999"),
        $(".horario-inputmask").inputmask("99:99"),
        $(".cep-inputmask").inputmask("99.999-999"),
        $(".cpf-inputmask").inputmask("999.999.999-99"),
        $(".telefone_fixo-inputmask").inputmask("(99) 9999-9999"),
        $(".telefone_celular-inputmask").inputmask("(99) 99999-9999"),
            $(".phone-inputmask").inputmask("(999) 999-9999"),
            $(".international-inputmask").inputmask("+9(999)999-9999"),
            $(".xphone-inputmask").inputmask("(999) 999-9999 / x999999"),
            $(".purchase-inputmask").inputmask("aaaa 9999-****"),
            $(".cc-inputmask").inputmask("9999 9999 9999 9999"),
            $(".ssn-inputmask").inputmask("999-99-9999"),
            $(".isbn-inputmask").inputmask("999-99-999-9999-9"),
            $(".currency-inputmask").inputmask("$9999"),
            $(".percentage-inputmask").inputmask("99%"),
            $(".decimal-inputmask").inputmask({
                alias: "decimal",
                radixPoint: "."
            }),

            $(".email-inputmask").inputmask({
                mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
                greedy: !1,
                onBeforePaste: function(n, a) {
                    return (e = e.toLowerCase()).replace("mailto:", "")
                },
                definitions: {
                    "*": {
                        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
                        cardinality: 1,
                        casing: "lower"
                    }
                }
            })
    });
    </script>
</body>
 
</html>
        <?php
    }
}

class Footer_adm_WEB {
    
    function __construct() {} // para prevenir a duplicaÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¯ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â¿ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â½o do print
    
    function Footer_adm_WEB() {
        ?>
        </div>
                </div>
            </div>
            
        </div>
        <!-- ============================================================== -->
        <!-- end main wrapper -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>

    <script src="assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="assets/libs/js/main-js.js"></script>
    <!--<script src="assets/vendor/inputmask/js/jquery.inputmask.bundle.js"></script> -->
    <script src="assets/vendor/parsley/parsley.js"></script>
    <!-- <script src="formularios.js"></script> -->
    <!--<script src="js/show.js"></script>-->
    <script src="js/menu.js"></script>
    <script src="js/var_cidade_estado.js"></script>
    <script src="js/js_providencias.js"></script>
    <script src="js/pesquisa.js"></script>
    <script src="js/jquery.inputmask.bundle.js"></script>
    <script src="js/normas_corregedoria.js"></script>
    <!-- <script src="js_dinamico/corregedores_tramitacao.js"></script> -->
    <script src="js/show.js"></script>
    <script src="js/somentePdf.js"></script>
    
    <script>
    $(function(e) {
        "use strict";
        $(".cc-inputmask").inputmask("999-999-999-99"),
        $(".cnpj-inputmask").inputmask("99-999-999/9999-99"),
        $(".date-inputmask").inputmask("99/99/9999"),
        $(".cpf-inputmask").inputmask("999.999.999-99"),
        $(".horario-inputmask").inputmask("99:99"),
        $(".cep-inputmask").inputmask("99.999-999"),
        $(".telefone_fixo-inputmask").inputmask("(99) 9999-9999"),
        $(".telefone_celular-inputmask").inputmask("(99) 99999-9999"),
            $(".phone-inputmask").inputmask("(999) 999-9999"),
            $(".international-inputmask").inputmask("+9(999)999-9999"),
            $(".xphone-inputmask").inputmask("(999) 999-9999 / x999999"),
            $(".purchase-inputmask").inputmask("aaaa 9999-****"),
            $(".cc-inputmask").inputmask("9999 9999 9999 9999"),
            $(".ssn-inputmask").inputmask("999-99-9999"),
            $(".isbn-inputmask").inputmask("999-99-999-9999-9"),
            $(".currency-inputmask").inputmask("$9999"),
            $(".percentage-inputmask").inputmask("99%"),
            $(".decimal-inputmask").inputmask({
                alias: "decimal",
                radixPoint: "."
            }),

            $(".email-inputmask").inputmask({
                mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
                greedy: !1,
                onBeforePaste: function(n, a) {
                    return (e = e.toLowerCase()).replace("mailto:", "")
                },
                definitions: {
                    "*": {
                        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
                        cardinality: 1,
                        casing: "lower"
                    }
                }
            })
    });
    </script>
    
</body>
 
</html>
        <?php
    }
}










































?>