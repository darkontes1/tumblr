<?php
    class BDD{
        private $bdd; //La bdd que l'on utilise
        private $srv_bdd;  //Le serveur bdd que l'on utilise
        private $mdp_bdd; //Le mdp de la bdd
        private $user_bdd;   //L'utilisateur de la bdd
        //constructeur de la bdd
        private function __construct(){
            $this->bdd = BDD;
            $this->srv_bdd = SERVER_BDD;
            $this->mdp_bdd = MDP_BDD;
            $this->user_bdd = USER_BDD;
        }
        //fonction qui fait le lien avec la base de données
        function createLinkBDD(){
            try{
                //Syntaxe init PDO => $host;$BDD,$name,$mdp
                $link = new PDO("mysql:host=".$this->srv_bdd.";dbname=".$this->bdd, $this->user_bdd, $this->mdp_bdd);
            }
            catch(PDOException $ex){
                echo '<br/>';
                echo 'echec lors de la connexion a MySQL : ('.$ex->getCode().')';
                echo $ex->getMessage();
                exit();
            }
            return $link;
        }
    }
?>