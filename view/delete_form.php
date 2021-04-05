<?php
//on démarre une session pour pouvoir utiliser la superglobale $_session ensuite donc on copie aussi le session_start en début du fichier où on veut lire l'info ici index.php
session_start();

//vérifier si on a un id = existe et non vide dans url
if(isset($_GET['disc_id']) && !empty($_GET['disc_id'])){
    //vérifier si id existe:
    require_once('../controller/connect.php');
    //on nettoie id envoyé et on protège ainsi des scripts malveillants
    $id = strip_tags($_GET['disc_id']);
    //DELETE tjs avec WHERE - pour éviter pb ou erreur faire d'abord select puis delete
    $sql = 'SELECT disc_id, disc_picture, disc_title, artist.artist_name, disc_label, disc_year, disc_genre, disc_price FROM `disc` JOIN `artist` ON artist.artist_id = disc.artist_id WHERE `disc_id` = :id;';
    //on prépare requête
    $query = $db->prepare($sql);
    //on accroche les paramètres - ici id
    $query->bindValue(':id', $id, PDO::PARAM_INT);//verifie que id est entier, si n'est pas entier y aura erreur
    //on exécute la requête
    $query->execute();
    //on récupère le produit
    $disc = $query->fetch();//car on n'a qu'1 enregist à récup donc pas fetchall
    //on vérifie si produit existe: s'il existe pas on aura $produit nul
    if(!$disc){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: ../index.php');
        die();
    }
} else{//si id pas cohérent ou existe pas je renvoie sur page accueil avec un msg d'erreur pour expliquer
    $_SESSION['erreur'] = "URL invalide";
    header('Location: ../index.php');
}

//inclure entete :
include 'header.php';

?>

                <h1>Suppression d'un vinyle</h1>
                <p class="alert alert-danger p-3">Important!!!!! Si vous cliquez à nouveau sur le bouton <b>Supprimer</b>, cet enregistrement sera définitivement supprimé. Réfléchissez bien !!!!
                    <button type="button" class="btn btn-danger mx-5 mt-5">
                        <a class="text-light" href="../controller/delete_script.php?disc_id=<?= $disc['disc_id'] ?>">Supprimer</a>
                    </button>
                    <button type="button" class="btn btn-primary mx-5 mt-5">
                        <a class="text-light" href="../index.php">Retour</a>
                    </button>
                </p>
                
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control bg-light" type="text" id="title" name="title" value="<?= $disc['disc_title'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input class="form-control bg-light" type="text" id="year" name="year" value="<?= $disc['disc_year'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="label">Label</label>
                    <input class="form-control bg-light" type="text" id="label" name="label" value="<?= $disc['disc_label'] ?>" disabled>
                </div>
                <p>Picture</p>
                <img src="../public/pictures/<?= $disc['disc_picture'] ?>" alt="Image de l'album" title="Image de l'album" width="200">
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="artist">Artist</label>
                    <input class="form-control bg-light" type="text" id="artist" name="artist" value="<?= $disc['artist_name'] ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input class="form-control bg-light" type="text" id="genre" name="genre" value="<?= $disc['disc_genre'] ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="price">Price</label>
                    <input class="form-control bg-light" type="text" id="price" name="price" value="<?= $disc['disc_price'] ?>" disabled>
                </div>
            </div>
        </div>
        
    </main>
    
</body>
</html>