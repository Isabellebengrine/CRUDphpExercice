<?php

// déclaration regex
$regLogin = '/^[A-Za-z0-9]+$/';
$regPassword = '/^[A-Za-z0-9àéèù"()\'@$;:,.% &?!\/*+_-]{10,}$/';

// déclaration tableau d'erreur
$formError = [];

// si le formulaire est envoyé
if (isset($_POST['submit'])) {

// si le champ 'login' n'est pas vide
    if (!empty($_POST['login'])) {
        
        if (preg_match($regLogin, $_POST['login'])) {
            $login = $_POST['login'];
        } else {
            $formError['login'] = 'Caractère non valide';
        }
    } else {
        $formError['login'] = 'Champ vide';
    }

    // si le champ 'password' n'est pas vide
    if (!empty($_POST['password'])) {
        if (preg_match($regPassword, $_POST['password'])) {
            $password = $_POST['password'];
        } else {
            $formError['password'] = 'Mot de passe : pour votre sécurité, plus il est long, mieux c\'est! Ici, on vous demande au moins 10 caractères!';
        }
    } else {
        $formError['password'] = 'Champ vide';
    }

    //si tout est bon dans form :
    if(count($formError) === 0) {
        
        //connexion à db
        require_once('connect.php');

        //  Récupération de l'utilisateur et de son pass hashé
        $req = $db->prepare('SELECT `user_id`, `user_pass` FROM `user` WHERE `user_login` = :login');
        $req->execute(array(
            'login' => $login));
        $resultat = $req->fetch();

        // Comparaison du pass envoyé via le formulaire avec la base :
        $isPasswordCorrect = password_verify($_POST['password'], $resultat['user_pass']);

        if (!$resultat)
        {
            echo 'Mauvais identifiant ou mot de passe !';
        }
        else
        {
            if ($isPasswordCorrect) {
                $_SESSION['id'] = $resultat['id'];
                $_SESSION['login'] = $login;
                echo 'Vous êtes connecté !';
                // les 2 champs sont corrects donc on initialise une variable de session auth avec la valeur ok:
                $_SESSION['auth'] = 'ok';

                // si l'utilisateur s'est authentifié correctement (donc si la variable de session auth existe et contient la valeur ok). 

                if(isset($_SESSION['auth']) && $_SESSION['auth'] === 'ok'){
                    //on redirige sur page qui devient accessible par login :
                    header('Location: ../index.php');
                } else {
                    //Si ce n'est pas le cas, l'utilisateur devra être redirigé sur la page de connexion :
                    header('Location: ../view/login.php');
                }
            }
            else {
                echo 'Mauvais identifiant ou mot de passe !';
            }
        }        
    } else {
        // Sinon la variable de session auth est détruite:
        unset($_SESSION['auth']);
    }
}
