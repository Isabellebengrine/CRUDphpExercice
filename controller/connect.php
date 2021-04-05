<?php
try{
    //connexion à la base
    $db = new PDO('mysql:host=localhost;dbname=record', 'root', '');
    $db->exec('SET NAMES "UTF8"');
} catch(PDOException $e){
    echo 'Erreur : '. $e->getMessage();
    die();
}
?>