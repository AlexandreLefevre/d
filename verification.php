<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
    // connexion à la base de données
    $db_username = 'root';
    $db_password = '';
    $db_name     = 'adeuxcom';
    $db_host     = 'localhost';
    $db = mysqli_connect($db_host, $db_username, $db_password,$db_name)
           or die('could not connect to database');
    $bdd = new PDO('mysql:host=localhost;dbname=adeuxcom;charset=utf8', 'root', '');
    // on applique les deux fonctions mysqli_real_escape_string et htmlspecialchars
    // pour éliminer toute attaque de type injection SQL et XSS
    $username = mysqli_real_escape_string($db,htmlspecialchars($_POST['username'])); 
    $password = mysqli_real_escape_string($db,htmlspecialchars($_POST['password']));
    
    if($username !== "" && $password !== "")
    {
   
        $requete_1 = $bdd->query("SELECT * FROM utilisateur where nom_utilisateur = '".$username."' and mot_de_passe = '".$password."' ");
        $user = array();
      while ($donnees = $requete_1->fetch()) {
        $user[] = array('username' => $donnees['nom_utilisateur'], 'admin' => $donnees['Admin']);
}
        if($user[0]!=null) // nom d'utilisateur et mot de passe correctes
        {
         session_name($username);
           $_SESSION['username'] = $username;
           $_SESSION['Admin'] = $user[0]['admin'];
           header('Location: index.php');
        }
        else
        {
           header('Location: login.php?erreur=1'); // utilisateur ou mot de passe incorrect
        }
    }
    else
    {
       header('Location: login.php?erreur=2'); // utilisateur ou mot de passe vide
    }
}
else
{
   header('Location: login.php');
}
mysqli_close($db); // fermer la connexion
?>