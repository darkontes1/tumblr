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
            $link = $bdd->createLinkBDD();//link avec la BDD
            $tabUsers=array();
            $query = 'SELECT * FROM users'; //requète SQL
            $data = $link->prepare($query);
            $data->execute();
            $result = $data->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $tabUsers[$row["login"]] = $row["passwordUser"];
            }
            $bdd = null;
            return $tabUsers;
        }

        //Ajoute un utilisateur dans la BDD
        function ajoutUsers($nom, $prenom, $password){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $link = $bdd->createLinkBDD();//link avec la BDD
            $hash = crypt($password,'$5$'.SALT.'$');    //cryptage utiliser password_hash
            $query = 'INSERT INTO users (nomUser,prenomUser,passwordUser,createdOn)'; //requète SQL
            $query .= 'VALUES (:nom,:prenom,:hash,NOW())';
            
            $data = $link->prepare($query);
            $data->bindValue('nom',$nom,PDO::PARAM_STR);
            $data->bindValue('prenom',$prenom,PDO::PARAM_STR);
            $data->bindValue('hash',$hash,PDO::PARAM_STR);
            $data->execute();
            
            //requête update
            $id = $link->lastInsertId('id');
            $login = mb_substr($prenom,0,1).'.'.mb_substr($nom,0,6).$id; // login créé
            $query = 'UPDATE users SET login=:login WHERE idUser = :id';

            $data = $link->prepare($query);
            $data->bindValue('login',$login,PDO::PARAM_STR);
            $data->bindValue('id',$id,PDO::PARAM_INT);
            $data->execute();
            
            $bdd = null;
            return $login;
        }

        //Permet de trouver la personne qui se log
        function logUsers($tabUsers){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $link = $bdd->createLinkBDD();//link avec la BDD
            $hash = crypt($tabUsers['passwordCo'],'$5$'.SALT.'$');  //cryptage on peut utiliser password_verify
            $query = 'SELECT * FROM users WHERE login = :login';    //requète SQL
            $query .= ' AND passwordUser = :hash LIMIT 1';
            $data = $link->prepare($query);
            $data->bindValue('login',$tabUsers['loginCo'],PDO::PARAM_STR);
            $data->bindValue('hash',$hash,PDO::PARAM_STR);
            $data->execute();
            $result = $data->fetchAll(PDO::FETCH_ASSOC);
            $err = $link->errorInfo();
            print_r($err);
            if($err[0] !== '00000'){
                return FALSE;
            }
            $bdd = null;
            //Si aucun resultat =>
            print_r($result);
            if(empty($result)){
                return FALSE;
            }
            //si un résultat =>
            return TRUE;
        }
    }
?>