<?php
    class IMAGE{
        private $nomImage;
        private $typeImage;
        private $tmpNameImage;
        private $captionImage;
        private $createOn;

        public function getNomImage(){
            return $this->nomImage;
        }

        public function getCaptionImage(){
            return $this->captionImage;
        }
        //Constructeur d'une image
        public function __construct(/*$nomImage, $typeImage, $tmpNameImage, $captionImage, $createOn*/){
            /*$this->nomImage = $nomImage;
            $this->typeImage = $typeImage;
            $this->tmpNameImage = $tmpNameImage;
            $this->captionImage = $captionImage;
            $this->createOn = $createOn;*/
        }

        //Récupère les images dans la BDD
        function getImages(){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $link = $bdd->createLinkBDD();//link avec la BDD
            $tabImages = array();
            $query = 'SELECT * FROM images'; //requète SQL
            $data = $link->prepare($query);
            $data->execute();
            $result = $data->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $tabImages[$row["nomImage"]] = $row["captionImage"];
            }
            $bdd = null;
            return $tabImages;
        }

        //affichage des images
        function createfigure($nomImage,$captionImage='') {
            echo '<figure>';
            echo '<div class="blocimg">';
            echo '<img src="'.DIR_IMG.'/'.$nomImage.'" alt="'.$nomImage.'">';
            echo '</div>';
            echo '<figcaption>'.$captionImage.'</figcaption>';
            echo '</figure>';
        }

        //Récupère juste une image passée en paramètre dans la BDD
        function recupImage($image){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $link = $bdd->createLinkBDD();//link avec la BDD
            $query = 'SELECT * FROM images WHERE nomImage=:image'; //requète SQL
            
            $data = $link->prepare($query);
            $data->bindValue('image', $image,PDO::PARAM_STR);
            $data->execute();
            
            $result = $data->fetchAll(PDO::FETCH_ASSOC);
            $bdd = null;
            return $result;
        }

        //Récupère une image passée en paramètre dans la BDD et la créée
        function getSingleImages($image){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $link = $bdd->createLinkBDD();//link avec la BDD
            $query = 'SELECT * FROM images WHERE nomImage=:image'; //requète SQL
            
            $data = $link->prepare($query);
            $data->bindValue('image', $image,PDO::PARAM_STR);
            $data->execute();
            
            $result = $data->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($result);
            $image = new IMAGE();
            $image->createfigure($result[0]['nomImage'],$result[0]['captionImage']);
            $bdd = null;
        }

        //Supprime une image passée en paramètre de la BDD
        function deleteImage($image){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $link = $bdd->createLinkBDD();//link avec la BDD
            //Supprime de la table image
            $query = 'DELETE from images WHERE nomImage=:image'; //requète SQL            
            $data = $link->prepare($query);
            $data->bindValue('image', $image,PDO::PARAM_STR);
            $data->execute();
            //Supprime de la table de relation entre image et user
            $query = 'DELETE from relimageuser WHERE relimageuser.idImage';
            $query .= ' IN (SELECT image.idImage FROM image WHERE nomImage LIKE :image);'; //requète SQL            
            $data = $link->prepare($query);
            $data->bindValue('image', $image,PDO::PARAM_STR);
            $data->execute();
            $bdd = null;
        }

        //Update une image passée en paramètre de la BDD
        function updateImage($image,$nomImage='',$captionImage='',$real_path='',$createdOn='0000-00-00'){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $link = $bdd->createLinkBDD();//link avec la BDD
            $recup = recupImage($image);

            if(empty($recup['nomImage'])){
                $nomImageUpd = $nomImage;
            }
            else{
                $nomImageUpd = $recup['nomImage'];
            }

            if(empty($recup['captionImage'])){
                $captionImageUpd = $captionImage;
            }
            else{
                $captionImageUpd = $recup['captionImage'];
            }

            if(empty($recup['real_path'])){
                $real_pathUpd = $real_path;
            }
            else{
                $real_pathUpd = $recup['real_path'];
            }

            if($recup['createdOn']=='0000-00-00'){
                $createdOnUpd = $createdOn;
            }
            else{
                $createdOnUpd = $recup['createdOn'];
            }

            $query = 'UPDATE images SET nomImage=:nomImageUpd, captionImage=:captionImageUpd,'; //requète SQL
            $query .= ' real_path=:real_pathUpd, createdOn=:createdOnUpd WHERE nomImage=:image'; //requète SQL


            $data = $link->prepare($query);

            $data->bindValue('image', $image,PDO::PARAM_STR);
            $data->bindValue('nomImageUpd', $nomImageUpd,PDO::PARAM_STR);
            $data->bindValue('captionImageUpd', $captionImageUpd,PDO::PARAM_STR);
            $data->bindValue('real_pathUpd', $real_pathUpd,PDO::PARAM_STR);
            $data->bindValue('createdOnUpd', $createdOnUpd,PDO::PARAM_STR);
            $data->execute();
            
            $bdd = null;
        }

        //Insert dans la base de données une nouvelle image avec upload de l'image
        function uploadImage($nomImage,$typeImage,$sizeImage,$error,$tmpNameImage,$captionImage,$user){
            $extensions_valides = array('image/jpg','image/jpeg','image/gif','image/png');  //liste des extensions valides
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $link = $bdd->createLinkBDD();//link avec la BDD
            //Si il y a une erreur lors du transfert de l'image
            if($error > 0){
                printf('Erreur $_FILES snif');
                $bdd = null;
                exit();
            }
            //Ajoute un real_path qui permet de l'identifier de manière unique
            $real_path = md5($nomImage.time()).'.'.$typeImage;
            $recup = recupImage($nomImage);           
            //Compare la type de l'image avec le tableau d'extension valide pour savoir si le format est bon
            //et regarde si la taille du fichier à upload est bonne ou non
            if(in_array($typeImage,$extensions_valides) && $sizeImage > 0){
                //déplace le fichier et le rename par le bon nom
                $resultat = move_uploaded_file($tmpNameImage,DIR_UPL.DIRECTORY_SEPARATOR.$nomImage);
                if(!$resultat){
                    printf('Deplacement fail');
                }
                //INSERT INTO de l'image dans la BDD
                $query = 'INSERT INTO images (nomImage,captionImage,real_path,createdOn)'; //requète SQL
                $query .= ' VALUES (:nomImage,:captionImage,:real_path,NOW())';    //requète SQL
                $data = $link->prepare($query);
                $data->bindValue('nomImage', $nomImage,PDO::PARAM_STR);
                $data->bindValue('captionImage', $captionImage,PDO::PARAM_STR);
                $data->bindValue('real_path', $real_path,PDO::PARAM_STR);
                $data->execute();
                //Si la requête s'est mal passé, ça renvoie une erreur
                if(!$data){
                    printf('Code Erreur !');
                }
                $id = $link->lastInsertId('id');
                //INSERT INTO de l'image dans la table relimageuser
                $query = 'INSERT INTO relimageuser (idImage,idUser)'; //requète SQL
                $query .= ' VALUES (:idImage, :idUser)';    //requète SQL
                $data = $link->prepare($query);
                $data->bindValue('idImage', $id,PDO::PARAM_INT);
                $data->bindValue('idUser', $user,PDO::PARAM_STR);
                $data->execute();
                if(!$data){
                    printf('Code Erreur !');
                }
            }
            //Si c'est pas le cas affiche la suite
            else{
                printf('Problème d\'extension ou de taille');
            }
            $bdd = null;
        }
    }
?>