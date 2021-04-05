<?php

//appels aux fichiers nécessaires:
include('../controller/detailsController.php');
include('header.php');

?>
    
                <h1>Détails</h1>
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
        <div class="btn-group my-3">
        <?php
            if(isset($_SESSION['auth']) && $_SESSION['auth'] === 'ok'){
        ?>
            <button type="button" class="btn btn-primary ml-5 my-5"><a class="text-light" href="update_form.php?disc_id=<?= $disc['disc_id'] ?>">Modifier</a></button>
            <button type="button" class="btn btn-primary ml-5 my-5"><a class="text-light" href="delete_form.php?disc_id=<?= $disc['disc_id'] ?>">Supprimer</a></button>
        <?php
            }
        ?>
        
            <button type="button" class="btn btn-primary ml-5 my-5"><a class="text-light" href="../index.php">Retour</a></button>
        </div>
    </div>
    
</body>
</html>