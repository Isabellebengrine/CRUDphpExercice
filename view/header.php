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
            <a class="nav-link" href="login.php">Connexion</a>
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

            <?php
            if(isset($_SESSION['auth']) && $_SESSION['auth'] === 'ok'){
            ?>
             <h2 class="text-right">Bienvenue, <?= $_SESSION['login']; ?> !</h2>
             <?php
            }
            ?>
            