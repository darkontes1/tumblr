<?php
session_start();
    define ('APP_PATH', __DIR__);
    include (__DIR__.'/conf/conf.php');    //include des variables pour la BDD
    include (__DIR__.'/include/functions_page1.php');  //include des fonctions qui peuvent être utilisées dans la page
    include (__DIR__.'/include/header_page1.php');   //include du header
    include (__DIR__.'/class/bdd.php');
    include (__DIR__.'/class/image.php');
    include (__DIR__.'/class/user.php');

    $allImages = TRUE; //Affecte par défaut l'affichage de toutes les images 
    echo "toto";
?>

<?php
    include (__DIR__.'/include/footer_page1.php');
?>