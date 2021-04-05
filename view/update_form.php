<?php
    session_start();

    //pour afficher formulaire avec info du disque choisi :

    //je verifie d'abord que j'ai bien un id qui marche (sinon ca sert à rien d'afficher formulaire)
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
        $query->bindValue(':id', $id, PDO::PARAM_INT);//verifie que id est entier, si n'est pas entier y aura erreur
        //on exécute la requête
        $query->execute();
        //on récupère le produit
        $disc = $query->fetch();//car on n'a qu'1 enregist à récup donc pas fetchall
        //on vérifie si produit existe: s'il existe pas on aura $disc null
        if(!$disc){
            $_SESSION['erreur'] = "Cet id n'existe pas";
            header('Location: ../index.php');
        }

    //si id pas cohérent ou existe pas je renvoie sur page accueil avec un msg d'erreur pour expliquer
    }else{
        $_SESSION['erreur'] = "URL invalide";
        header('Location: ../index.php');
    }

    //on inclut le controller :
    include '../controller/update_script.php';

    //inclure entete :
    include '../header.php';
?>


                <h1>Modifier un vinyle</h1>
                <?php
                
                if (isset($_POST['submit']) && count($formError) == 0) {
                    //no error on submit so data sent to base so redirection to index.php in controller so nothing here
                } else {
                ?>

                <form action="#" method="POST" enctype="multipart/form-data"><!--att  enctype pour pouvoir uploader files-->
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" type="text" id="title" name="title" value="<?= $disc['disc_title'] ?>">
                        <span class="text-danger">
                            <?= isset($formError['title']) ? $formError['title'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="artistid">Artist</label>
                        <?php 
                        //pour liste déroulante avec noms d'artiste:
                        // connexion à la base
                        require_once('../controller/connect.php'); 
                        //j'écris la requête :
                        $sql = 'SELECT DISTINCT artist_id, artist_name FROM `artist`';
                        //je prép la requête :
                        $query = $db->prepare($sql);
                        //j'exécute la requête :
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
                        //now que j'ai data je ferme la connection
                        require_once('../controller/close.php');
                        //utilisation des données obtenues par la requête dans les options de liste déroulante
                        echo '<select class="form-control" id="artistid" name="artistid" size="1">';
                        //mettre nom artiste actuel en selected dans liste des autres options :
                        foreach($result as $row){
                            if ($row['artist_name'] == $disc['artist_name']){
                                echo '<option  value="'.$row['artist_id'].'" selected>'.$row['artist_id'].' - '.$row['artist_name'].'</option>';
                            } else {
                                echo '<option  value="'.$row['artist_id'].'">'.$row['artist_id'].' - '.$row['artist_name'].'</option>';
                            }
                            
                        }  
                        echo '</select>';
                        ?>
                        <span class="text-danger">
                            <?= isset($formError['artistid']) ? $formError['artistid'] : '' ?>
                        </span>

                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input class="form-control" type="text" id="year" name="year" value="<?= $disc['disc_year'] ?>">
                        <span class="text-danger">
                            <?= isset($formError['year']) ? $formError['year'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <input class="form-control" type="text" id="genre" name="genre" value="<?= $disc['disc_genre'] ?>">
                        <span class="text-danger">
                            <?= isset($formError['genre']) ? $formError['genre'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="label">Label</label>
                        <input class="form-control" type="text" id="label" name="label" value="<?= $disc['disc_label'] ?>">
                        <span class="text-danger">
                            <?= isset($formError['label']) ? $formError['label'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input class="form-control" type="text" id="price" name="price" value="<?= $disc['disc_price'] ?>">
                        <span class="text-danger">
                            <?= isset($formError['price']) ? $formError['price'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <p><label for="picture">Picture</label></p>
                        <input type="file" id="picture" name="picture" value="<?= $disc['disc_picture'] ?>">
                        <!--code pour limiter la taille de fichier :-->
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                        <p class="mt-2"><img src="../public/pictures/<?= $disc['disc_picture'] ?>" alt="Image de l'album" title="Image de l'album" width="300"></p>
                    </div>
                    <!--ne pas oublier champ caché pour id :-->
                    <input type="hidden" name="id" value="<?= $disc['disc_id'] ?>">
                    <div class="row mb-2">
                        <input type="submit" class="btn btn-primary mr-2 mt-2" value="Modifier" name="submit">
                        <button type="button" class="btn btn-primary mr-2 mt-2"><a class="text-light" href="../index.php">Retour</a></button>
                    </div>
                </form>
                <?php
                }
                ?>
            </div>
        </div>
        

        
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>