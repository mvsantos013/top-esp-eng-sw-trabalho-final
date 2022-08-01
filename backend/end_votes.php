<?php

    // Este arquivo é responsável por calcular resultados da eleição e resetar o banco de dados.

    // Carrega arquivo de configuração do banco de dados
    $root = $_SERVER['DOCUMENT_ROOT'];
    include_once($root.'/backend/database.php');
    $db = new Database();
	$conn = $db->connect();

    $table = 'votos';
    
    $sql = "SELECT numero, etapa FROM $table";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $votos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Resultado da votação
    $resultado = array(
        'vereador' => array(
            'total' => 0,
            'nulos' => 0,
            'brancos' => 0,
            'vencedor' => null,
            'votos' => (object)[]
        ),
        'prefeito' => array(
            'total' => 0,
            'nulos' => 0,
            'brancos' => 0,
            'vencedor' => null,
            'votos' => (object)[]
        )
    );

    // Calcula resultado das eleições
    foreach($votos as $voto){
        $etapa = $voto['etapa'];
        $numero = $voto['numero'];
        if($numero == 'nulo')
            $resultado[$etapa]['nulos']++; // Conta nulos
        else if($numero == 'branco')
            $resultado[$etapa]['brancos']++; // Conta brancos
        else {
            // Computa voto para candidato
            if(property_exists($resultado[$etapa]['votos'], $numero)){
                $resultado[$etapa]['votos']->$numero++;
            }else{
                $resultado[$etapa]['votos']->$numero = 1;
            }
        }
        
        $resultado[$etapa]['total']++;
    }

    // Computa vencedores
    foreach($resultado['vereador']['votos'] as $numero => $qtd_votos) {
        $numero_vencedor = $resultado['vereador']['vencedor'];
        if(!$numero_vencedor){
            $resultado['vereador']['vencedor'] = $numero;
            continue;
        }

        if($qtd_votos >= $resultado['vereador']['votos']->$numero_vencedor){
            $resultado['vereador']['vencedor'] = $numero;
        }
    }

    foreach($resultado['prefeito']['votos'] as $numero => $qtd_votos) {
        $numero_vencedor = $resultado['prefeito']['vencedor'];
        if(!$numero_vencedor){
            $resultado['prefeito']['vencedor'] = $numero;
            continue;
        }

        if($qtd_votos >= $resultado['prefeito']['votos']->$numero_vencedor){
            $resultado['prefeito']['vencedor'] = $numero;
        }
    }

    $sql = "TRUNCATE TABLE $table";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo json_encode(
        array($resultado)
    );