<?php
    $root = $_SERVER['DOCUMENT_ROOT'];
    include_once($root.'/backend/database.php');
    $db = new Database();
	$conn = $db->connect();

    $table = 'votos';

    $votos = $_POST['votos'];
    
    foreach ($votos as $voto) {
        $sql = "INSERT INTO $table (numero, etapa) VALUES (:numero, :etapa)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':numero', $voto['numero']);
        $stmt->bindParam(':etapa', $voto['etapa']);
        $stmt->execute();
    }

    echo json_encode(
        array('message' => 'success')
    );