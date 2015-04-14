<?php
session_start();
    define ('APP_PATH', __DIR__);
    include (__DIR__.'/conf/conf.php');
    include (__DIR__.'/include/functions_page1.php');  //include des fonctions qui peuvent être utilisées dans la page
    include (__DIR__.'/class/bdd.php');
    include (__DIR__.'/class/user.php');
    $logon = FALSE;

    //Si on a cliquer sur le bouton de connexion
    if(filter_has_var(INPUT_POST, 'logon')){

        $filterPost = array (
        'loginCo' => array (
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => array (
                FILTER_FLAG_ENCODE_LOW,
                FILTER_FLAG_ENCODE_HIGH
                )
            ),
        'passwordCo' => array (
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_FLAG_ENCODE_HIGH
            )
        );

        $tabPost = filter_input_array(INPUT_POST, $filterPost);
        $logon = logUsers($tabPost);

        if($logon==TRUE){
            $_SESSION['connect'] = TRUE;
            $_SESSION['connectime'] = time();
            header('location: page1.php');
        }
        else{
            printf('erreur lors du login');
            $_SESSION['loginCo'] = $tabPost['loginCo'];
            header('location: login.php');
        }
    }

    //Si le formulaire est bien rempli
    elseif(filter_has_var(INPUT_POST, 'create')){
        $filterPost = array (
        'nom' => array (
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => array (
                FILTER_FLAG_ENCODE_LOW,
                FILTER_FLAG_ENCODE_HIGH
                )
            ),
        'prenom' => array (
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => array (
                FILTER_FLAG_ENCODE_LOW,
                FILTER_FLAG_ENCODE_HIGH
                )
            ),
        'password' => array (
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_FLAG_ENCODE_HIGH
            ),
        'passwordVerif' => array (
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_FLAG_ENCODE_HIGH
            )
        );

        $tabPost = filter_input_array(INPUT_POST, $filterPost);
        var_dump($tabPost);
        //Si les mdp ne sont pas les mêmes
        if($tabPost['password']!=$tabPost['passwordVerif']){
            $_SESSION['formInscri']['nom'] = $tabPost['nom'];
            $_SESSION['formInscri']['prenom'] = $tabPost['prenom'];
            header('location: login.php');
        }
        //Si ils sont pareils on ajoute dans la BDD
        else{
            //rentrer dans la base de données avec le pwd qui est crypté dans la fonction
            $_SESSION['newCompte'] = ajoutUsers($tabPost['nom'], $tabPost['prenom'], $tabPost['password']);
            header('location: login.php');
        }
    }

?>