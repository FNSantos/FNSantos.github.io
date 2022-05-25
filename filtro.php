 <?php
    if(isset($_POST['filtro'])){
        $filtro = $_POST['filtro'];
        /*
            ordenação 
            tipo 0 = ordenação por arquivado/disponivel
            tipo 1 = busca por palavra digitada no campo texto
            tipo 2 = ordem alfabetica
        */
        $tipo = 0;
        @$tipo = $_POST['tipo'];
        
        $ch = curl_init();
        
        $url = '';
        if($tipo == 2){
            $url = 'https://api.github.com/users/FNSantos/repos?sort=full_name&direction='.$filtro;
        }else{
            $url = 'https://api.github.com/users/FNSantos/repos';
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = 'Content-Type: text/html';
        $headers[] = 'Accept: application/vnd.github.v3+json';
        # token criado na plataforma do git para acessa aos meus repositorios
        $headers[] = 'Authorization: token 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';
        $headers[] = 'User-Agent: GitHub-FNSantos';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $data = json_decode($result, true);
        
        echo '<tr>
            <th>Nome</th>
            <th>Disponibilidade</th>
            <th>Data</th>
        </tr>';
        $qtd_matchs = 0;
        foreach ( $data as $e ){
            
            $nome = $e['name'];
            # formatação da data
            $data = date('d/m/Y', strtotime($e['pushed_at']));
            if ($e['archived'] == 1){
                $arquivado = "ARQUIVADO";
                $color = "#f44336";
            }else{
                $arquivado = "DISPONIVEL";
                $color = "#0dd864";
            }
            
            if($tipo == 0){
                echo '<tr>';
                    if($filtro == 'todos'){
                        echo '<td>'.$nome.'</td>';
                        echo '<td style="color:'.$color.';">'.$arquivado.'</td>';
                        echo '<td>'.$data.'</td>';
                    }

                    if($filtro == 'disponivel'){
                        if ($e['archived'] == 0){
                            echo '<td>'.$nome.'</td>';
                            echo '<td style="color:'.$color.';">'.$arquivado.'</td>';
                            echo '<td>'.$data.'</td>';
                        }
                    }

                    if($filtro == 'arquivado'){
                        if ($e['archived'] == 1){
                            echo '<td>'.$nome.'</td>';
                            echo '<td style="color:'.$color.';">'.$arquivado.'</td>';
                            echo '<td>'.$data.'</td>';
                        }
                    }
                echo '</tr>';
            }
            
            
            if($tipo == 1){
                #utilizando preg_match com pattern para identificar se existe um retorno similar para a busca 
                if (preg_match_all("/$filtro/i", $nome)) {
                    $qtd_matchs = $qtd_matchs + 1;
                    echo '<tr>';
                    echo '<td>'.$nome.'</td>';
                    echo '<td style="color:'.$color.';">'.$arquivado.'</td>';
                    echo '<td>'.$data.'</td>';
                    echo '</tr>';
                }   
            }
            
            if($tipo == 2){
                echo '<tr>';
                echo '<td>'.$nome.'</td>';
                echo '<td style="color:'.$color.';">'.$arquivado.'</td>';
                echo '<td>'.$data.'</td>';
                echo '</tr>';
            }
        }
        
        if($qtd_matchs < 1 and $tipo == 1){
            echo '<tr><td colspan="3">nenhum resultado foi encontrado.<br>Para encontrar é neceessario digitar o nome do repositorio</td></tr>';
        }
    }
?>