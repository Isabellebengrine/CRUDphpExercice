<?php

//pour utiliser $_SESSION :
session_start();
//on inclut le controller :
include '../controller/add_script.php';
include 'header.php';

?>
                <h1>Ajouter un vinyle</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
            <?php
                if (isset($_POST['submit']) && count($formError) === 0) {
                    //data sent to base so redirection sur index.php dans controller donc rien
                } else {
            ?>
            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control" type="text" id="title" name="title" value="Enter title">
                    <span class="text-danger">
                        <?= isset($formError['title']) ? $formError['title'] : '' ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="artist">Artist</label>
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
                    echo '<select class="form-control" id="artistid" name="artistid">';
                    foreach($result as $row){
                        echo '<option  value="'.$row['artist_id'].'">'.$row['artist_id'].' - '.$row['artist_name'].'</option>';
                    }  
                    echo '</select>';
                    ?>
                    <span class="text-danger">
                        <?= isset($formError['artistid']) ? $formError['artistid'] : '' ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input class="form-control" type="text" id="year" name="year" value="Enter year">
                    <span class="text-danger">
                        <?= isset($formError['year']) ? $formError['year'] : '' ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input class="form-control" type="text" id="genre" name="genre" value="Enter genre (Rock, Pop, Prog...)">
                    <span class="text-danger">
                        <?= isset($formError['genre']) ? $formError['genre'] : '' ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="label">Label</label>
                    <input class="form-control" type="text" id="label" name="label" value="Enter label (EMI, Warner, PolyGram, ...)">
                    <span class="text-danger">
                        <?= isset($formError['label']) ? $formError['label'] : '' ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input class="form-control" type="text" id="price" name="price" value="">
                    <span class="text-danger">
                        <?= isset($formError['price']) ? $formError['price'] : '' ?>
                    </span>
                </div>
                <div class="form-group">
                    <p><label for="picture">Picture</label></p>
                    <input type="file" id="picture" name="picture" accept="image/*">
                    <!--code pour limiter la taille de fichier :-->
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                </div>
                <div class="row">
                    <input type="submit" class="btn btn-primary mr-2 mt-2" value="Ajouter" name="submit">
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