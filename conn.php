<?php
try {
    $PDO = new PDO('mysql:host=localhost;port=3307;dbname=calendario2', 'root', '');
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

function actionDB($sqlData){
    GLOBAL $PDO;
    try {
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $PDO->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $stmt = $PDO->prepare($sqlData);
        if($stmt->execute()){
            return $PDO->lastInsertId();
        } else {
            $set_msg = 2;
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
        $set_msg = 3;
    }
}
?> 