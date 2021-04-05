<?php
//on démarre une session pour utiliser la superglobale $_SESSION qui vient de details.php ou autres pages
session_start();

//inclure connexion à db
require_once('controller/connect.php');
//écrire requête
$sql = 'SELECT disc_id, disc_picture, disc_title, artist.artist_name, disc_label, disc_year, disc_genre FROM `disc` JOIN `artist` ON artist.artist_id = disc.artist_id ORDER BY artist_name';

//prepared statement
$query = $db->prepare($sql);
//on exécute la requête
$query->execute();
//on stocke le résultat dans un tableau associatif
$result = $query->fetchAll(PDO::FETCH_ASSOC);

if (!$query) //donc si la variable $query vaut NULL
{
    $tableauErreurs = $db->errorInfo();
    echo $tableauErreur[2]; 
    die("Erreur dans la requête");
}

if ($query->rowCount() == 0) 
{
   // Pas d'enregistrement
   die("La table est vide");
}

//var_dump($result);//(permet de vérifier si on récup bien les diff infos - ici on a tout en double donc on ajoute dans fetchall une constante pdo PDO::FETCH_ASSOC qui lui dit de ne mettre ds résts qu'infos avec titres des diff colonnes)

//now que j'ai data je ferme la connection
require_once('controller/close.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Velvet Records</title>
</head>
<body>

    <nav class="navbar navbar-expand-sm bg-info navbar-dark justify-content-between">
    <!-- Brand/logo -->
    <a class="navbar-brand" href="#">VELVET RECORDS</a>
    <!-- Links -->
    <ul class="navbar-nav">
        
        <?php
            if(isset($_SESSION['auth']) && $_SESSION['auth'] === 'ok'){
        ?>
        <li class="nav-item">
            <a class="nav-link" href="controller/logout.php">Déconnexion</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="view/add_form.php">Ajouter</a>
        </li>
    <?php
        } else {
    ?>
        <li class="nav-item">
            <a class="nav-link" href="view/login.php">Connexion</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="view/signin.php">Inscription</a>
        </li>
    <?php
        } //ferme le if
    ?>
    </ul>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12">

            <?php
            if(isset($_SESSION['auth']) && $_SESSION['auth'] === 'ok'){
            ?>
             <h2 class="text-right">Bienvenue, <?= $_SESSION['login']; ?> !</h2>
             <?php
            }
            
            if(!empty($_SESSION['erreur'])){
                echo '<div class="alert alert-danger" role="alert">'.$_SESSION['erreur'].'</div>';
                //une fois affiché on n'en a plus besoin donc
                $_SESSION['erreur'] = "";
            }
        
            if(!empty($_SESSION['message'])){
                echo '<div class="alert alert-success" role="alert">'.$_SESSION['message'].'</div>';
                //une fois affiché on n'en a plus besoin donc
                $_SESSION['message'] = "";
            }
            ?> 
            </div>
        </div>    

        <div class="d-flex justify-content-between m-2">
            <h1 class="col-10 col-sm-10">Liste des disques (<?= count($result); ?>)</h1><!--affiche compteur du nb de lignes de la liste-->
            
        </div>

        <div class="row m-2"> 
            <div class="d-flex flex-wrap my-2">
            <?php
                foreach ($result as $row){
            ?>
                <div class="col-6 col-sm-3 flex-fill my-2">
                    <img src="public/pictures/<?= $row['disc_picture'] ?>" alt="Image de l'album" title="Image de l'album" width="100%">
                </div>
                <div class="col-6 col-sm-3 flex-fill my-2">
                    <h2><?= $row['disc_title'] ?></h2>
                    <p class="font-weight-bold"><?= $row['artist_name'] ?></p>
                    <p><b>Label : </b><?= $row['disc_label'] ?><br>
                    <b>Year : </b><?= $row['disc_year'] ?><br>
                    <b>Genre : </b><?= $row['disc_genre'] ?></p>
                    <p><button class="btn btn-primary btn-sm m-1"><a class="text-light" href="view/details.php?disc_id=<?= $row['disc_id'] ?>">Détails</a></button></p>
                </div>
            <?php
                }
            ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>