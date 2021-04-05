<?php
//on démarre une session pour pouvoir utiliser la superglobale $_session ensuite donc on copie aussi le session_start en début du fichier où on veut lire l'info ici index.php
session_start();

//vérifier si on a un id = existe et non vide dans url
if(isset($_GET['disc_id']) && !empty($_GET['disc_id'])){
    //vérifier si id existe:
    require_once('../controller/connect.php');
    //on nettoie id envoyé et on protège ainsi des scripts malveillants
    $id = strip_tags($_GET['disc_id']);
    //on écrit requête :
    $sql = 'SELECT disc_id, disc_picture, disc_title, artist.artist_name, disc_label, disc_year, disc_genre, disc_price FROM `disc` JOIN `artist` ON artist.artist_id = disc.artist_id WHERE `disc_id` = :id;';
    //on prépare requête
    $query = $db->prepare($sql);
    //on accroche les paramètres - ici id
    $query->bindValue(':id', $id, PDO::PARAM_INT);//verifie que id est entier
    //on exécute la requête
    $query->execute();
    //on récupère l'enregistrement
    $disc = $query->fetch();//car on n'a qu'1 enregist à récup donc pas fetchall
    //vérif si faut PDO::FETCH_OBJ ? çà marche sans donc c quoi la différence ?
    //on vérifie si disc existe: s'il n'existe pas on aura $disc null
    if(!$disc){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: ../index.php');
    }

} else{//si id pas cohérent ou existe pas je renvoie sur page accueil avec un msg d'erreur pour expliquer
    $_SESSION['erreur'] = "URL invalide";
    header('Location: ../index.php');
}
