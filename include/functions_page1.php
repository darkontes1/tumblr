<?php
    //define ('SALT','ro@t€r');
    //fonction qui fait le lien avec la base de données
    function createLinkBDD(){
        $link = mysqli_connect(SERVER_BDD,USER_BDD,MDP_BDD,BDD);
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

    //affichage des images
    function createfigure($lienimage,$commentaire='') {
        echo '<figure>';
        echo '<div class="blocimg">';
        echo '<img src="'.DIR_IMG.'/'.$lienimage.'" alt="'.$lienimage.'">';
        echo '</div>';
        echo '<figcaption>'.$commentaire.'</figcaption>';
        echo '</figure>';
    }

    //Récupère les images dans la BDD
    function getImages(){
        $linkBDD = createLinkBDD(); //link avec la BDD
        $tabImages=array();
        $reqGetImages = 'SELECT * FROM images'; //requète SQL
        $resGetImages = mysqli_query($linkBDD,$reqGetImages);   //Resultat de le la requete SQL
        while($rowGetImages = mysqli_fetch_assoc($resGetImages)){
            $tabImages[$rowGetImages['nomImage']] = $rowGetImages['captionImage'];
        }
        closeBDD($linkBDD);
        return $tabImages;
    }

    //Récupère les logs des users dans la BDD
    function getUsers(){
        $linkBDD = createLinkBDD(); //link avec la BDD
        $tabUsers=array();
        $reqGetUsers = 'SELECT * FROM users'; //requète SQL
        $resGetUsers = mysqli_query($linkBDD,$reqGetUsers);   //Resultat de le la requete SQL
        while($rowGetUsers = mysqli_fetch_assoc($resGetUsers)){
            $tabUsers[$rowGetUsers['login']] = $rowGetUsers['passwordUser'];
        }
        closeBDD($linkBDD);
        return $tabUsers;
    }

    //Ajoute un utilisateur dans la BDD
    function ajoutUsers($nom, $prenom, $password){
        $linkBDD = createLinkBDD(); //link avec la BDD
        $hash = crypt($password,'$5$'.SALT.'$');    //cryptage utiliser password_hash
        $reqUsers = 'INSERT INTO users (nomUser,prenomUser,passwordUser,createdOn)';    //requête insert
        $reqUsers .= 'VALUES ("'.$nom.'","'.$prenom.'","'.$hash.'",NOW())';
        $resUsers = mysqli_query($linkBDD,$reqUsers);
        //requête update
        $login = mb_substr($prenom,0,1).'.'.mb_substr($nom,0,6).mysqli_insert_id($linkBDD); // login créé
        $reqUsers = 'UPDATE users SET login="'.$login.'" WHERE idUser = "'.mysqli_insert_id($linkBDD).'"';
        $resUsers = mysqli_query($linkBDD,$reqUsers);
        closeBDD($linkBDD);
        return $login;
    }

    //Permet de trouver la personne qui se log
    function logUsers($tabUsers){
        $linkBDD = createLinkBDD(); //link avec la BDD
        $hash = crypt($tabUsers['passwordCo'],'$5$'.SALT.'$');  //cryptage on peut utiliser password_verify
        $reqUsers = 'SELECT * FROM users WHERE login = "'.$tabUsers['loginCo'].'"';
        $reqUsers .= 'AND passwordUser = "'.$hash.'"';
        $resUsers = mysqli_query($linkBDD,$reqUsers);
        if(mysqli_connect_errno($linkBDD)!=0){
            return FALSE;
        }
        $nbUsers = mysqli_num_rows($resUsers);  //compte le nombre de ligne que nous renvoie la requête
        closeBDD($linkBDD);
        //Si aucun resultat =>
        if($nbUsers==0){

            return FALSE;
        }
        //si un résultat =>
        return TRUE;
    }

    //Récupère juste une image passée en paramètre dans la BDD
    function recupImage($image){
        $linkBDD = createLinkBDD(); //link avec la BDD
        $reqSImages = 'SELECT * FROM images WHERE nomImage="'.$image.'"'; //requète SQL
        $resSImages = mysqli_query($linkBDD,$reqSImages);   //Resultat de le la requete SQL
        $rowSImages = mysqli_fetch_assoc($resSImages);
        closeBDD($linkBDD);
        return $rowSImages;
    }

    //Récupère une image passée en paramètre dans la BDD et la créée
    function getSingleImages($image){
        $linkBDD = createLinkBDD(); //link avec la BDD
        $reqSImages = 'SELECT * FROM images WHERE nomImage="'.$image.'"'; //requète SQL
        $resSImages = mysqli_query($linkBDD,$reqSImages);   //Resultat de le la requete SQL
        $rowSImages = mysqli_fetch_assoc($resSImages);
        //var_dump($rowSImages);
        createfigure($rowSImages['nomImage'],$rowSImages['captionImage']);
        closeBDD($linkBDD);
    }

    //Supprime une image passée en paramètre de la BDD
    function deleteImage($image){
        $linkBDD = createLinkBDD(); //link avec la BDD
        $reqSImages = 'DELETE from images WHERE nomImage="'.$image.'"'; //requète SQL
        $resSImages = mysqli_query($linkBDD,$reqSImages);   //Resultat de le la requete SQL
        closeBDD($linkBDD);
    }

    //Update une image passée en paramètre de la BDD
    function updateImage($image,$nomImage='',$captionImage='',$real_path='',$createdOn='0000-00-00'){
        $linkBDD = createLinkBDD(); //link avec la BDD
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
        $resSImages = mysqli_query($linkBDD,$reqSImages);   //Resultat de le la requete SQL
        closeBDD($linkBDD);
    }

    //Insert dans la base de données une nouvelle image 1**
    /*function createImage($nomImage,$captionImage){
        $linkBDD = createLinkBDD(); //link avec la BDD
        $reqSetImages = 'INSERT INTO images (nomImage,captionImage) VALUES ("'.$nomImage.'","'.$captionImage.'")'; //requète SQL
        $resSetImages = mysqli_query($linkBDD,$reqSetImages);
        if(!$resSetImages){
            printf('Code Erreur : ', mysqli_errno($link));
        }
        closeBDD($linkBDD);
    }*/

    //Insert dans la base de données une nouvelle image avec upload de l'image
    function uploadImage($nomImage,$typeImage,$sizeImage,$error,$tmpNameImage,$captionImage){
        $extensions_valides = array('image/jpg','image/jpeg','image/gif','image/png');  //liste des extensions valides
        $linkBDD = createLinkBDD(); //link avec la BDD
        //Si il y a une erreur lors du transfert de l'image
        if($error > 0){
            printf('Erreur $_FILES snif');
            closeBDD($linkBDD);
            exit();
        }
        /*
        //Pour une très grande sécurité et éviter tout virus possible derrière une fausse image
        //Vérifier le type MIME qui va regarder le véritable type et le charset avec
        $finfo = finfo_open(FILEINFO_MIME);
        if (!$finfo) {
            echo "Échec de l'ouverture de la base de données fileinfo";
            exit();
        }
        $filename = $tmpNameImage;
        echo finfo_file($finfo, $filename);
        */
        //Ajoute un real_path qui permet de l'identifier de manière unique
        $real_path = md5($nomImage.time()).'.'.$typeImage;
        $recup = recupImage($nomImage);
        //On vérifie les doublons de l'image et on l'ajoute pas dans ce cas
        /*if($recup['nomImage']==$nomImage && md5($recup['nomImage'])==md5($nomImage)){
            printf('ON NE RENTRE PAS DEUX FOIS LA MEME IMAGE DAS LA TABLE !');
        }
        else{*/
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
                $resUploadImage = mysqli_query($linkBDD,$reqUploadImage);
                //Si la requête s'est mal passé, ça renvoie une erreur
                if(!$resUploadImage){
                    printf('Code Erreur !');
                }
            }
            //Si c'est pas le cas affiche la suite
            else{
                printf('Problème d\'extension ou de taille');
            }
        /*}*/
        closeBDD($linkBDD);
    }


?>