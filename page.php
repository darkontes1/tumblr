<?php
session_start();
    define ('APP_PATH', __DIR__);
    include (__DIR__.'/conf/conf.php');    //include des variables pour la BDD
    include (__DIR__.'/include/functions_page1.php');  //include des fonctions qui peuvent être utilisées dans la page
    include (__DIR__.'/include/header_page1.php');   //include du header
    include (__DIR__.'/class/bdd.php');
    include (__DIR__.'/class/image.php');
    include (__DIR__.'/class/user.php');
    /*$test = new IMAGE();
    $tab = $test->getImages();
    print_r($tab);*/
       //Objet image
    $newImage = new IMAGE();
    $allImages = TRUE; //Affecte par défaut l'affichage de toutes les images 
    //Si on est connecté =>
    if(!empty($_POST))  //Vérifie si get n'est pas vide --> action à effectuer
    { 
        if($_SESSION['connect'] == TRUE){
            if(!empty($_FILES['image'])){
                $imageUpl = new IMAGE(); 
                $image = $_FILES['image']['name'];
                $type = $_FILES['image']['type'];
                $size = $_FILES['image']['size'];
                $error = $_FILES['image']['error'];
                $tmpNameImage = $_FILES['image']['tmp_name'];

                //var_dump($_FILES);
                //$image = filter_input(INPUT_POST,'image',FILTER_SANITIZE_STRING); 1**
                $caption = filter_input(INPUT_POST,'caption',FILTER_SANITIZE_STRING);
                //création de l'enregistrement dans la BDD
                //createImage($image,$caption);     1**
                $imageUpl->uploadImage($image,$type,$size,$error,$tmpNameImage,$caption,$_SESSION['idUser']);
            }
        } 
    }
    elseif(isset($_GET['singleimage']) && $_GET['singleimage'] != 'ALL'){
        $singleImage = $_GET['singleimage'];//filter_input(INPUT_POST,,FILTER_SANITIZE_STRING);
        $allImages = FALSE;
        //echo $allImages;
    }
    else{
        $_SESSION['connect']=TRUE;
    }
    //Pour obtenir toutes les images de la BDD
    $tabImages = $newImage->getImages();

    if($_SESSION['connect']==TRUE){   
    ?>
    <nav>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" >
            <input type="file" name="image" required>
            <!-- <input type="text" name="image" placeholder="Insérer une image" required>  1** -->
            <input type="text" name="caption" placeholder="Commentaire image" required>
            <input type="submit" value="Envoyer">
        </form>
    <?php
    }
    ?>
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <select name="singleimage">
                <option value="ALL">Toutes les images</option>
                <?php
                foreach ($tabImages as $image => $caption) {
                    ?>
                <option value="<?php echo $image ?>"><?php echo $image;?></option>
                <?php
                }
                ?>
            </select>
            <input type="submit" value="Envoyer">
        </form>
    </nav>
    <br>
<?php 
    if($allImages == TRUE) {
        foreach ($tabImages as $image => $caption) {
            $newImage->createfigure($image,$caption);
        }
    } else {
        $newImage->getSingleImages($singleImage);
    }
?>

<?php
    include (__DIR__.'/include/footer_page1.php');
?>