<?php

// déclaration regex
$regTitle = '/^[A-Za-z ]+$/';//sert à title et label aussi
$regYear = '/^([19]*[20]*)+[0-9]{2}$/';
$regGenre = '/^[A-Za-z, \/]+$/';//autorise aussi / et ,
$regPrice = '/^[0-9]{1,4}[,.]{0,1}[0-9]{0,2}$/';//nb decimal (ou pas) avec . ou ,
// déclaration tableau d'erreur
$formError = [];

// si le formulaire est envoyé
if (isset($_POST['submit'])) {

    // si le champ 'title' n'est pas vide
    if (!empty($_POST['title'])) {
        if (preg_match($regTitle, $_POST['title'])) {
            $title = strip_tags($_POST['title']);//protection contre injection de scripts malveillants
        } else {
            $formError['title'] = 'Caractère non valide';
        }
    } else {
        $formError['title'] = 'Champ vide';
    }

    // idem pour chaque champ :
    if (!empty($_POST['year'])) {
        if (preg_match($regYear, $_POST['year'])) {
            $year = strip_tags($_POST['year']);
        } else {
            $formError['year'] = 'Caractère non valide';
        }
    } else {
        $formError['year'] = 'Champ vide';
    }

    if (!empty($_POST['genre'])) {
        if (preg_match($regGenre, $_POST['genre'])) {
            $genre = strip_tags($_POST['genre']);
        } else {
            $formError['genre'] = 'Caractère non valide';
        }
    } else {
        $formError['genre'] = 'Champ vide';
    }

    if (!empty($_POST['label'])) {
        if (preg_match($regTitle, $_POST['label'])) {
            $label = strip_tags($_POST['label']);
        } else {
            $formError['label'] = 'Caractère non valide';
        }
    } else {
        $formError['label'] = 'Champ vide';
    }

    if (!empty($_POST['price'])) {
        if (preg_match($regPrice, $_POST['price'])) {
            $price = strip_tags($_POST['price']);
        } else {
            $formError['price'] = 'Format non valide (veuillez entrer un nombre entier ou décimal avec 2 décimales maximum)';
        }
    } else {
        $formError['price'] = 'Champ vide';
    }

    // pour champ artistid choix dans select donc:
    if (!empty($_POST['artistid'])) {
        $artistid = strip_tags($_POST['artistid']);
    } else {
        $formError['artistid'] = 'Choisissez une option';
    }

    if(count($formError) == 0) {
        
            //connexion à db :
            require_once('connect.php');
            //nettoyer et protéger contre injection de scripts malveillants
            //ici par rapport à add_form.php je veux aussi nettoyer l'id donc je rajoute çà ici:
            $id = strip_tags($_POST['id']);
            //requête :
            $sql = 'UPDATE `disc` SET `disc_title` = :title, `artist_id`= :artistid, `disc_label` = :label, `disc_year` = :annee, `disc_genre` = :genre, `disc_price` = :price WHERE `disc_id` = :id';//verif par sql ok
            //on prépare requête
            $query = $db->prepare($sql);
            //on accroche les paramètres
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':artistid', $artistid, PDO::PARAM_INT);
            $query->bindValue(':annee', $year, PDO::PARAM_INT);
            $query->bindValue(':genre', $genre, PDO::PARAM_STR);
            $query->bindValue(':label', $label, PDO::PARAM_STR);
            $query->bindValue(':price', $price);

            //on exécute la requête
            $query->execute();

            //si changement d'image donc nvo fichier à uploader et champ disc_picture à mettre à jour :
            if ($_FILES['picture']['name'] != ''){

                //code for uploading file :
                $target_dir = "../public/pictures/";
                $target_file = $target_dir . basename($_FILES["picture"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["picture"]["tmp_name"]);
                    if($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }

                /* Check if file already exists
                if (file_exists($target_file)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                }
                correction php / cédric le 20/10/20 : vérif si fichier déjà dans dossier inutile car si on re-upload une image déjà dans dossier, on écrase donc peu importe, au final on évitera en enlevant cette vérif d'afficher msg d'erreur du à doublon d'img alors que ajout ou update ok !
                
                */
                // Check file size
                //  tableau global $_FILES contient la taille du fichier mais peu fiable. fonction PHP filesize() retourne la taille d'un fichier en octets. Il suffit de lui donner l'adresse du fichier qui est contenue dans $_FILES['picture']['tmp_name'] :
                // taille maximum (en octets)
                $taille_maxi = 500000;
                //Taille du fichier
                $taille = filesize($_FILES['picture']['tmp_name']);
                if($taille>$taille_maxi) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                //(Cette méthode est une première approche qui nous suffit pour le moment mais, en réalité, il faudrait vérifier le type MIME des fichiers uploadés)
                //if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                //&& $imageFileType != "gif" ) {
                //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                //$uploadOk = 0;
                //}
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["picture"]["name"])). " has been uploaded.";
                    } else {
                    echo "Sorry, there was an error uploading your file.";
                    }
                }
                //fin du upload - vérif fichier ajouté dans dossier pictures : ok

                //now mettre nom fichier en bd :
                $picture = $_FILES['picture']['name'];
                //écrire req pour update champ picture :
                $sql2 = 'UPDATE `disc` SET `disc_picture` = :picture WHERE `disc_id` = :id';//verif par sql ok
                //on prépare requête
                $query2 = $db->prepare($sql2);
                //on accroche les paramètres
                //verifie que champ est entier, si string mettre PARAM_STR
                $query2->bindValue(':id', $id, PDO::PARAM_INT);
                $query2->bindValue(':picture', $picture, PDO::PARAM_STR);
                //on exécute la requête
                $query2->execute();                
            } 

            //on affiche message de confirmation
            $_SESSION['message'] = "Disque modifié";
            //on ferme connexion
            require_once('close.php');
            //on redirige sur page accueil
            header('Location: ../index.php');

        } 
    }

?>
