<?php
session_start();
define ('APP_PATH', __DIR__);
    include (__DIR__.'/conf/conf.php');    //include des variables pour la BDD
    include (__DIR__.'/include/functions_page1.php');  //include des fonctions qui peuvent être utilisées dans la page
    include (__DIR__.'/include/header_page1.php');   //include du header
    
    $_SESSION['connect']=FALSE; //Si la personne est connecté ça sera à TRUE
    //Si le formulaire de session nom est vide on initialise
    if(empty($_SESSION['formInscri']['nom'])){
        $_SESSION['formInscri']['nom'] = $nom = '';
    }
    //sinon on mets dans $nom la valeur
    else{
        $nom = $_SESSION['formInscri']['nom'];
    }
    //Si le formulaire de session prenom est vide on initialise
    if(empty($_SESSION['formInscri']['prenom'])){
        $_SESSION['formInscri']['prenom'] = $prenom = '';
    }
    //sinon on mets dans $prenom la valeur
    else{
         $prenom = $_SESSION['formInscri']['prenom'];
    }
    //Si la session loginCo n'a pas encore été rempli
    if(empty($_SESSION['loginCo'])){
        $_SESSION['loginCo'] = $loginCo = '';
    }
    //Si il a déjà été rempli
    else{
        $loginCo = $_SESSION['loginCo'];
    }
    if(!empty($_SESSION['newCompte'])){
        echo '<p>Votre compte a pour login : '.$_SESSION['newCompte'].' . Ne le perdez pas !</p>';
    }
    var_dump($_SESSION);
?>
    <form method="POST" class="login" action="backoffice.php" >
        <fieldset>
            <legend>Connexion</legend>
            <label>login</label><input type="text" name="loginCo" placeholder="votre login" value="<?php echo $loginCo; ?>" required>
            <label>password</label><input type="password" name="passwordCo" required>
            <input type="submit" name="logon" value="Connexion">
        </fieldset>
    </form>

    <form method="POST" class="login" action="backoffice.php" >
        <fieldset>
            <legend>Inscription</legend>
            <label>Votre nom</label><input type="text" name="nom" placeholder="votre nom" 
            value="<?php echo $nom; ?>" required>
            <label>Votre prénom</label><input type="text" name="prenom" placeholder="votre prenom" 
            value="<?php echo $prenom; ?>" required>
            <label>password</label><input type="password" name="password" required>
            <label>vérif password</label><input type="password" name="passwordVerif" required>
            <input type="submit" name="create" value="Inscription">
        </fieldset>
    </form>
<?php

    include (__DIR__.'/include/footer_page1.php');
?>