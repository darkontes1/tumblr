<?php
    class BDD{
        private $bdd = BDD; //La bdd que l'on utilise
        private $srv_bdd = SERVER_BDD;  //Le serveur bdd que l'on utilise
        private $mdp_bdd = MDP_BDD; //Le mdp de la bdd
        private $user_bdd = USER_BDD;   //L'utilisateur de la bdd
        //constructeur de la bdd
        private function __construct(){}
        //fonction qui fait le lien avec la base de données
        function createLinkBDD(){
            $link = mysqli_connect($this->srv_bdd,$this->user_bdd,$this->mdp_bdd,$this->bdd);
            if(mysqli_connect_errno()){
                printf('echec de connexion',mysqli_connect_errno());
                exit();
            }
            return $link;
        }
        //fonction qui ferme la connexion à la BDD
        function closeBDD($link){
            mysqli_close($link);
        }
    }
?>