<?php
    class IMAGE{
        private $nomImage;
        private $typeImage;
        private $tmpNameImage;
        private $captionImage;
        private $createOn;

        //Constructeur d'une image
        private function __construct($nomImage, $typeImage, $tmpNameImage, $captionImage, $createOn){
            $this->nomImage = $nomImage;
            $this->typeImage = $typeImage;
            $this->tmpNameImage = $tmpNameImage;
            $this->captionImage = $captionImage;
            $this->createOn = $createOn;
        }

        //Récupère les images dans la BDD
        function getImages(){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $bdd->createLinkBDD();//link avec la BDD
            $tabImages = array();
            $reqGetImages = 'SELECT * FROM images'; //requète SQL
            $resGetImages = mysqli_query($bdd,$reqGetImages);   //Resultat de le la requete SQL
            while($rowGetImages = mysqli_fetch_assoc($resGetImages)){
                $tabImages[$rowGetImages['nomImage']] = $rowGetImages['captionImage'];
            }
            $bdd->closeBDD();
            return $tabImages;
        }

        //affichage des images
        function createfigure($lienimage,$commentaire='') {
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
            $bdd->createLinkBDD();//link avec la BDD
            $reqSImages = 'SELECT * FROM images WHERE nomImage="'.$image.'"'; //requète SQL
            $resSImages = mysqli_query($bdd,$reqSImages);   //Resultat de le la requete SQL
            $rowSImages = mysqli_fetch_assoc($resSImages);
            $bdd->closeBDD();
            return $rowSImages;
        }

        //Récupère une image passée en paramètre dans la BDD et la créée
        function getSingleImages($image){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $bdd->createLinkBDD();//link avec la BDD
            $reqSImages = 'SELECT * FROM images WHERE nomImage="'.$image.'"'; //requète SQL
            $resSImages = mysqli_query($bdd,$reqSImages);   //Resultat de le la requete SQL
            $rowSImages = mysqli_fetch_assoc($resSImages);
            //var_dump($rowSImages);
            createfigure($rowSImages['nomImage'],$rowSImages['captionImage']);
            $bdd->closeBDD();
        }

        //Supprime une image passée en paramètre de la BDD
        function deleteImage($image){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $bdd->createLinkBDD();//link avec la BDD
            $reqSImages = 'DELETE from images WHERE nomImage="'.$image.'"'; //requète SQL
            $resSImages = mysqli_query($bdd,$reqSImages);   //Resultat de le la requete SQL
            $bdd->closeBDD();
        }

        //Update une image passée en paramètre de la BDD
        function updateImage($image,$nomImage='',$captionImage='',$real_path='',$createdOn='0000-00-00'){
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $bdd->createLinkBDD();//link avec la BDD
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

            $reqSImages = 'UPDATE images SET nomImage="'.$nomImageUpd.'", captionImage="'.$captionImageUpd.'",';
            $reqSImages .= 'real_path="'.$real_pathUpd.'", createdOn="'.$createdOnUpd.'" WHERE nomImage="'.$image.'"'; //requète SQL
            $resSImages = mysqli_query($bdd,$reqSImages);   //Resultat de le la requete SQL
            $bdd->closeBDD();
        }

        //Insert dans la base de données une nouvelle image avec upload de l'image
        function uploadImage($nomImage,$typeImage,$sizeImage,$error,$tmpNameImage,$captionImage){
            $extensions_valides = array('image/jpg','image/jpeg','image/gif','image/png');  //liste des extensions valides
            $bdd = new BDD();   //Objet bdd pour faire la connection
            $bdd->createLinkBDD();//link avec la BDD
            //Si il y a une erreur lors du transfert de l'image
            if($error > 0){
                printf('Erreur $_FILES snif');
                $bdd->closeBDD();
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
                $reqUploadImage = 'INSERT INTO images (nomImage,captionImage,real_path,createdOn)';
                $reqUploadImage .= 'VALUES ("'.$nomImage.'","'.$captionImage.'","'.$real_path.'",NOW())';
                //Resultat de la requête
                $resUploadImage = mysqli_query($bdd,$reqUploadImage);
                //Si la requête s'est mal passé, ça renvoie une erreur
                if(!$resUploadImage){
                    printf('Code Erreur !');
                }
            }
            //Si c'est pas le cas affiche la suite
            else{
                printf('Problème d\'extension ou de taille');
            }
            $bdd->closeBDD();
        }
    }
?>