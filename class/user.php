<?php
    class USER{
        private $nom;
        private $prenom;
        private $login;
        private $password;
        private $createdOn;
        //Constructeur de la classe user
        public function __construct(){

        }
        //Récupère les logs des users dans la BDD
        function getUsers(){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $bdd->createLinkBDD();//link avec la BDD
            $tabUsers=array();
            $reqGetUsers = 'SELECT * FROM users'; //requète SQL
            $resGetUsers = mysqli_query($bdd,$reqGetUsers);   //Resultat de le la requete SQL
            while($rowGetUsers = mysqli_fetch_assoc($resGetUsers)){
                $tabUsers[$rowGetUsers['login']] = $rowGetUsers['passwordUser'];
            }
            $bdd->closeBDD();
            return $tabUsers;
        }

        //Ajoute un utilisateur dans la BDD
        function ajoutUsers($nom, $prenom, $password){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $bdd->createLinkBDD();//link avec la BDD
            $hash = crypt($password,'$5$'.SALT.'$');    //cryptage utiliser password_hash
            $reqUsers = 'INSERT INTO users (nomUser,prenomUser,passwordUser,createdOn)';    //requête insert
            $reqUsers .= 'VALUES ("'.$nom.'","'.$prenom.'","'.$hash.'",NOW())';
            $resUsers = mysqli_query($bdd,$reqUsers);
            //requête update
            $login = mb_substr($prenom,0,1).'.'.mb_substr($nom,0,6).mysqli_insert_id($bdd); // login créé
            $reqUsers = 'UPDATE users SET login="'.$login.'" WHERE idUser = "'.mysqli_insert_id($bdd).'"';
            $resUsers = mysqli_query($bdd,$reqUsers);
            $bdd->closeBDD();
            return $login;
        }

        //Permet de trouver la personne qui se log
        function logUsers($tabUsers){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $bdd->createLinkBDD();//link avec la BDD
            $hash = crypt($tabUsers['passwordCo'],'$5$'.SALT.'$');  //cryptage on peut utiliser password_verify
            $reqUsers = 'SELECT * FROM users WHERE login = "'.$tabUsers['loginCo'].'"';
            $reqUsers .= 'AND passwordUser = "'.$hash.'"';
            $resUsers = mysqli_query($bdd,$reqUsers);
            if(mysqli_connect_errno($bdd)!=0){
                return FALSE;
            }
            $nbUsers = mysqli_num_rows($resUsers);  //compte le nombre de ligne que nous renvoie la requête
            $bdd->closeBDD();
            //Si aucun resultat =>
            if($nbUsers==0){

                return FALSE;
            }
            //si un résultat =>
            return TRUE;
        }
    }
?>