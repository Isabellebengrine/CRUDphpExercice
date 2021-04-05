<?php

session_start();
include '../controller/loginController.php';


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
    <a class="navbar-brand" href="../index.php">VELVET RECORDS</a>
    <!-- Links -->
    <ul class="navbar-nav">
        
        <?php
            if(isset($_SESSION['auth']) && $_SESSION['auth'] === 'ok'){
        ?>
        <li class="nav-item">
            <a class="nav-link" href="../controller/logout.php">Déconnexion</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="add_form.php">Ajouter</a>
        </li>
    <?php
        } else {
    ?>
        <li class="nav-item">
            <a class="nav-link" href="#">Connexion</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="signin.php">Inscription</a>
        </li>
    <?php
        } //ferme le if
    ?>
    </ul>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12">


                <h1>Connexion</h1>
                
                <?php
                if (isset($_POST['submit']) && count($formError) == 0) {
                ?>
                    <p>form ok !!!</p>
                <?php
                } else {
                ?>
                    <form method="POST" action="#">

                        <div class="form-group">
                            <label for="login">Pseudo</label>
                            <input type="text" class="form-control" id="login" name="login" value="<?= isset($_POST['login']) ? $_POST['login'] : '' ?>">
                            <span class="text-danger">
                                <?= isset($formError['login']) ? $formError['login'] : '' ?>
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
                            <span class="text-danger">
                                <?= isset($formError['password']) ? $formError['password'] : '' ?>
                            </span>
                        </div>

                        <input type="submit" name="submit" value="Envoyer" class="btn btn-primary">
                        <button type="button" class="btn btn-primary ml-5 my-5"><a class="text-light" href="../index.php">Retour</a></button>
                        <button type="button" class="btn btn-primary ml-5 my-5"><a class="text-light" href="signin.php">Pas encore inscrit? S'inscrire</a></button>
                    </form>
                    
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