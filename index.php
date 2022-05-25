<?php
                                
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/users/FNSantos/repos');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    $headers[] = 'Content-Type: text/html';
    $headers[] = 'Accept: application/vnd.github.v3+json';
    $headers[] = 'Authorization: token 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';
    $headers[] = 'User-Agent: GitHub-FNSantos';
    $headers[] = 'Accept: application/vnd.github.v3.text-match+jsonSET';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


/***********************

https://api.github.com/users/FNSantos/repos?sort=created&order=desc
                                            visibility=public
É SÓ PASSAR O PARAMETRO PELA URL
//parametro para ordenar por ordem alfabetica
    sort=full_name&direction=desc ou asc

//parametro para ordenar por de criação
    sort=created&direction=desc ou asc

***************************/


    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $data = json_decode($result, true);
   
?>

<!DOCTYPE HTML SYSTEM>
<html>
    <head>
        <meta charset='utf-8'/>
        <title>Felipe Nascimento</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <link rel="stylesheet" media="screen" href="css/index_style.css"/>
        <!--link rel="icon" href=""-->
        <script src="js/jquery.js"></script>
        <script type="text/javascript">
            /*
            ordenação 
            tipo 0 = ordenação por arquivado/disponivel
            tipo 1 = busca por palavra digitada no campo texto
            tipo 2 = ordem alfabetica
            */
            function filtro(){
                var filtro = $("#situacao_repos").val();
                $.ajax({
                    url: "filtro.php",
                    type: "POST",
                    data: {"filtro":filtro},
                    beforeSend: function(){
                        // Mostra loader
                        $("#loader").show();
                        $("#tbl_repos").hide();
                    },
                    success: function( data ) {
                        $("#tbl_repos").show();
                        $("#tbl_repos").html(data);
                    },
                    complete:function(data){
                        // Esconde o loader 
                        $("#loader").hide();
                    }
                })

            }
            function search(){
                var texto = $("#txt_busca").val();
                $.ajax({
                    url: "filtro.php",
                    type: "POST",
                    data: {"filtro":texto, "tipo":1},
                    beforeSend: function(){
                        // Mostra loader
                        $("#loader").show();
                        $("#tbl_repos").hide();
                    },
                    success: function( data ) {
                        $("#tbl_repos").show();
                        $("#tbl_repos").html(data);
                    },
                    complete:function(data){
                        // Esconde o loader 
                        $("#loader").hide();
                    }   
                });
            }
            
            function ordenar(tipo){
                $.ajax({
                    url: "filtro.php",
                    type: "POST",
                    data: {"filtro":tipo, "tipo":2},
                    beforeSend: function(){
                        // Mostra loader
                        $("#loader").show();
                        $("#tbl_repos").hide();
                    },
                    success: function( data ) {
                        $("#tbl_repos").show();
                        $("#tbl_repos").html(data);
                    },
                    complete:function(data){
                        // Esconde o loader 
                        $("#loader").hide();
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="portfolio-container">
            <div class="me">
                <img src="imagens/Felipe.png" alt="a girl smiling" class="my--image">
                <div class="my--bio">
                    <h1>Felipe Nascimento</h1>
                    <h3>Full Stack Developer</h3>
                    <p class="basic-info">.</p>
                    <nav>
                        <a href="https://api.whatsapp.com/send?1=pt_BR&phone=5511958532613" target="_blank" class="nav--btn">Contato</a>
                        <a href="#" class="nav--btn">Sobre</a>
                        <!--a href="#" class="nav--btn">Resume</a-->
                    </nav>
                </div>
            </div>
            <h1>Desafio Proposto</h1>

            <div class="project-wrapper">
                <div id="filtros">
                    <div class="input-group">
                        <label class="user-label">Pesquisa:</label>
                        <input onkeyup="search()" type="text" name="text" autocomplete="off" class="input" id="txt_busca">
                    </div>
                    <div class="input-group">
                        <label class="user-label">Filtro:</label>
                        <select id="situacao_repos" onchange="filtro()">
                            <option value="todos">TODOS</option>
                            <option value="disponivel">DISPONIVEL</option>
                            <option value="arquivado">ARQUIVADO</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="user-label">Ordenar:</label>
                        <p onclick="ordenar('asc')">⬇⬆ A-Z</p>
                        <p onclick="ordenar('desc')">⬇⬆ Z-A</p>
                    </div>
                
                </div>
                <div id='loader' >
                    <img src='imagens/loading.gif'>
                </div>
                <table id="tbl_repos">
                    <tr>
                        <th>Nome</th>
                        <th>Disponibilidade</th>
                        <th>Data</th>
                    </tr>
                    <?php
                        foreach ( $data as $e ){
                            echo '<tr>';
                            $nome = $e['name'];
                            $data = date('d/m/Y', strtotime($e['pushed_at']));
                            if ($e['archived'] == 1){
                                $arquivado = "ARQUIVADO";
                                $color = "#f44336";
                            }else{
                                $arquivado = "DISPONIVEL";
                                $color = "#0dd864";
                            }

                            echo '<td>'.$nome.'</td>';
                            echo '<td style="color:'.$color.';">'.$arquivado.'</td>';
                            echo '<td>'.$data.'</td>';

                            echo '</tr>';
                        }     

                    ?>
                </table>
            </div>
            <div class="project-wrapper">
                <img src="imagens/estudos_trabalho.jpg" alt="Homem em frente ao computador" class="project-thumbnail" style="filter:grayscale(50%);">
                <div class="project-name">
                    <h2 class="project-title">Sobre</h2>
                    <p class="project-description">Formado no curso técnico em desenvolvimento de sistemas pelo Senai SP no ano de 2018. Estou no mercado atuando a 4 anos como desenvolvedor full stack.<br>
                    Com o intuito de adquirir mais conhecimento, curso a faculdade de Análise e Desenvolvimento de Sistemas na faculdade Impacta.
                    </p>
                </div>
            </div>
            
            <div class="project-wrapper">
            </div> 
            <footer>
                <a href="https://www.linkedin.com/in/felipe-nascimento011" target="_blank">LinkedIn</a>
                <p>twitter</p>
                <a href="https://github.com/FNSantos" target="_blank">GitHub</a>
            </footer>
          </div>
    </body>
</html>
