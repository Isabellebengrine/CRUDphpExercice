<?php
//on démarre une session pour pouvoir utiliser la superglobale $_session ensuite donc on copie aussi le session_start en début du fichier où on veut lire l'info ici index.php
session_start();

//vérifier si on a un id = existe et non vide dans url
if(isset($_GET['disc_id']) && !empty($_GET['disc_id'])){
    //vérifier si id existe:
    require_once('connect.php');
    //on nettoie id envoyé et on protège ainsi des scripts malveillants
    $id = strip_tags($_GET['disc_id']);

    //TO DO - pour supprimer image du fichier après upload:
    //récup nom du fichier disc_picture:
    //$sql = 'SELECT disc_picture FROM disc';
    //unlink();
    //d'abord mettre info ds var global puis delete de base puis unlink du fichier dans dossier


    //DELETE 
    $sql = 'DELETE FROM `disc` WHERE `disc_id` = :id;';
    //on prépare requête
    $query = $db->prepare($sql);
    //on accroche les paramètres - ici id
    $query->bindValue(':id', $id, PDO::PARAM_INT);//verifie que id est entier, si n'est pas entier y aura erreur
    //on exécute la requête
    $query->execute();

    

    //message de confirmation
    $_SESSION['message'] = "Produit supprimé";
    //on ferme la connection
    require_once('close.php');
    //redirection
    header('Location: ../index.php');
}else{//si id pas cohérent ou existe pas je renvoie sur page accueil avec un msg d'erreur pour expliquer
    $_SESSION['erreur'] = "URL invalide";
    header('Location: ../index.php');
}


?>