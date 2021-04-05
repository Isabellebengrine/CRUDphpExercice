<?php

// déclaration regex
$regName = '/^[A-Za-zéèê\' -]+$/';
$regMail = '/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/';
$regLogin = '/^[A-Za-z0-9]+$/';
$regPassword = '/^[A-Za-z0-9àéèù"()\'@$;:,.% &?!\/*+_-]{10,}$/';

// déclaration tableau d'erreur
$formError = [];

// si le formulaire est envoyé
if (isset($_POST['submit'])) {

    // si le champ 'lastname' n'est pas vide
    if (!empty($_POST['lastname'])) {
        
        if (preg_match($regName, $_POST['lastname'])) {
            $lastname = htmlspecialchars($_POST['lastname']);
        } else {
            $formError['lastname'] = 'Caractère non valide';
        }
    } else {
        $formError['lastname'] = 'Champ vide';
    }

    // si le champ 'firstname' n'est pas vide
    if (!empty($_POST['firstname'])) {
            
        if (preg_match($regName, $_POST['firstname'])) {
            $firstname = htmlspecialchars($_POST['firstname']);
        } else {
            $formError['firstname'] = 'Caractère non valide';
        }
    } else {
        $formError['firstname'] = 'Champ vide';
    }
    // si le champ 'mail' n'est pas vide
    if (!empty($_POST['mail'])) {
            
        if (preg_match($regMail, $_POST['mail'])) {
            $mail = htmlspecialchars($_POST['mail']);
        } else {
            $formError['mail'] = 'Caractère non valide';
        }
    } else {
        $formError['mail'] = 'Champ vide';
    }

    // si le champ 'login' n'est pas vide
    if (!empty($_POST['login'])) {
        
        if (preg_match($regLogin, $_POST['login'])) {
            $login = htmlspecialchars($_POST['login']);

            //pour vérifier que pseudo n'existe pas déjà en base :
            //connexion à db
            require_once('connect.php');

            // ON cherche LE PSEUDO DANS LA TABLE
            $sql2  = 'SELECT COUNT(*) AS nbr FROM `user` WHERE `user_login` = :login';

            //on prépare requête
            $query2 = $db->prepare($sql2);

            //on accroche le paramètre
            $query2->bindValue(':login', $login, PDO::PARAM_STR);

            //on exécute la requête
            $query2->execute();

            //on met data dans tableau :
            $res = $query2->fetchAll(PDO::FETCH_ASSOC);

            //on boucle sur le tableau pour voir si nbr ==0 : 
            foreach ($res as $ligne){
                //si non, alors on veut que user choisisse un autre pseudo :
                if (!($ligne['nbr'] == 0)){
                    $formError['login'] = 'Pseudo déjà pris : choisissez un autre pseudo!';
                }
            }

        } else {
            $formError['login'] = 'Caractère non valide';
        }
    } else {
        $formError['login'] = 'Champ vide';
    }

    // si le champ 'password' n'est pas vide
    if (!empty($_POST['password'])) {
        if (preg_match($regPassword, $_POST['password'])) {
            $password = htmlspecialchars($_POST['password']);

            //pour vérifier que email n'existe pas déjà en base :
            //connexion à db
            require_once('connect.php');

            // ON cherche LE mail DANS LA TABLE
            $sql3  = 'SELECT COUNT(*) AS nbr FROM `user` WHERE `user_mail` = :mail';

            //on prépare requête
            $query3 = $db->prepare($sql3);

            //on accroche le paramètre
            $query3->bindValue(':mail', $mail, PDO::PARAM_STR);

            //on exécute la requête
            $query3->execute();

            //on met data dans tableau :
            $res = $query3->fetchAll(PDO::FETCH_ASSOC);

            //on boucle sur le tableau pour voir si nbr ==0 : 
            foreach ($res as $ligne){
                //si non, alors on veut que user choisisse un autre pseudo :
                if (!($ligne['nbr'] == 0)){
                    $formError['mail'] = 'E-mail déjà utilisé : vous êtes déjà inscrit, cliquez sur Connexion!';
                }
            }

        } else {
            $formError['password'] = 'Mot de passe : pour votre sécurité, plus il est long, mieux c\'est! Ici, on vous demande au moins 10 caractères!';
        }
    } else {
        $formError['password'] = 'Champ vide';
    }

    // si le champ 'password2' n'est pas vide
    if (!empty($_POST['password2'])) {
        if (preg_match($regPassword, $_POST['password2'])) {

            //si le champ de confirmation n'est pas le même que le champ password :
            if ($_POST['password2'] != $password){
                $formError['password2'] = 'Veuillez confirmer à nouveau votre mot de passe';
            }
        } else {
            $formError['password2'] = 'Mot de passe : pour votre sécurité, plus il est long, mieux c\'est! Ici, on vous demande au moins 10 caractères!';
        }
    } else {
        $formError['password2'] = 'Champ vide';
    }

    if(count($formError) === 0) {
         
        // Hachage du mot de passe
        $pass_hache = password_hash($password, PASSWORD_DEFAULT);

        //écrire req
        $sql = 'INSERT INTO `user` (`user_lastname`, `user_firstname`, `user_mail`, `user_pass`, `user_login`) VALUES(:lastname, :firstname, :mail, :password, :login)';//sql ok dans phpmyadmin

        //on prépare requête
        $query = $db->prepare($sql);
  
        //on accroche les paramètres
        //champ entier : PARAM_INT; string mettre PARAM_STR
        $query->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $query->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $query->bindValue(':mail', $mail, PDO::PARAM_STR);
        $query->bindValue(':password', $pass_hache, PDO::PARAM_STR);
        $query->bindValue(':login', $login, PDO::PARAM_STR);

        //on exécute la requête
        $query->execute();
/*
        // autre syntaxe possible :
        $req = $bdd->prepare('INSERT INTO user (user_lastname, user_firstname, user_mail, user_pass, user_login) VALUES(:lastname, :firstname, :mail, :password, :login)');

        $query->execute(array(
            'lastname' => $lastname,
            'firstname' => $firstname,
            'mail' => $mail,
            'password' => $pass_hache,
            'login' => $login
            ));
*/
        //on ferme connexion
        require_once('close.php');

        // on initialise une variable de session auth avec la valeur ok:
        $_SESSION['auth'] = 'ok';
        $_SESSION['login'] = $login;

        // Une autre page PHP devra être accessible uniquement si une session a été initialisée, c'est-à-dire si l'utilisateur s'est authentifié correctement (donc si la variable de session auth existe et contient la valeur ok). Si ce n'est pas le cas, l'utilisateur devra être redirigé sur la page de connexion :

        if(isset($_SESSION['auth']) && $_SESSION['auth'] === 'ok'){
            //on redirige sur page qui devient accessible par inscription :
            header('Location: ../index.php');//
        } else {
            //on redirige sur page de connexion :
            header('Location: ../view/signin.php');
        }

    } else {
        // Sinon la variable de session auth est détruite:
        unset($_SESSION['auth']);
    }
    
}
