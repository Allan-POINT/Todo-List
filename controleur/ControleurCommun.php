<?php
require_once("config/Validation.php");
require_once("modeles/ModelConnecte.php");
class ControleurCommun {
		
	function __construct() {
		try{
			$action=Validation::netoyerString(isset($_REQUEST['action']) ? $_REQUEST["action"] : "");
			switch($action) {
				//pas d'action, on r�initialise 1er appel
				case NULL:
					$this->Reinit();
					break;
				case "connection":
					$this->connection();
					break;
				case "veuxSInscrire":
					$this->veuxSInscrire();
					break;
				case "signin":
					$this->signin();
					break;
				case "SeConnecter":
				default:
					require("vues/connection.php");
					break;
			}
		}catch(PDOException $e)
		{
			//si erreur BD, pas le cas ici
			$erreurs[] = $e->getMessage();
			require("vues/erreur.php");
		}
		catch (Exception $e2)
		{
			$erreurs[] = $e2->getMessage();
			require("vues/erreur.php");
		}
		exit(0);
	}

	function Reinit() {
		global $rep,$vues; // nécessaire pour utiliser variables globales
		require("vues/connection.php");
	}

	function connection()
	{
		if(!isset($_REQUEST["pseudonyme"]) || !isset($_REQUEST["motDePasse"]))
		{
			throw new Exception("Erreur lors de la transmission des informations de connections");
		}

		$login = Validation::netoyerString($_REQUEST["pseudonyme"]);
		$mdp = Validation::netoyerString($_REQUEST["motDePasse"]);
		
		if(is_null($login) || is_null($mdp))
		{
			throw new ValueError("Le login ou le mot de passe contient des valeurs illégales");
		}
		$mdl = new ModelConnecte();
		$compte = $mdl->connection($login, $mdp);
		if(!is_null($compte))
		{
			require_once("controleur/ControleurConnecte.php");
			$_REQUEST["action"] = "seeLists";
			new ControleurConnecte();
		}
		else
		{
			throw new Exception("Erreur lors de la récupération du compte");
		}
	}
	function veuxSInscrire()
	{
		require("vues/inscription.php");
	}
	function signin()
	{
		if(!isset($_REQUEST["name"]))
		{
			throw new Exception("Le pseudonyme doit être envoyer au serveur");
		}
		if(empty($_REQUEST["name"]))
		{
			throw new Exception("Le pseudonyme doit contenire des caractères");
		}
		if(strlen($_REQUEST["name"]) < 4)
		{
			throw new Exception("Le pseudonyme doit contenire au moins 5 caractères");
		}
		

		if(!isset($_REQUEST["mdp1"]))
		{
			throw new Exception("Le mot de passe doit être envoyer au serveur");
		}
		if(empty($_REQUEST["mdp1"]))
		{
			throw new Exception("Le mot de passe doit contenire des caractères");
		}
		if(strlen($_REQUEST["mdp1"]) < 7)
		{
			throw new Exception("Le pseudonyme doit contenire au moins 8 caractères");
		}
		if($_REQUEST["mdp1"] != $_REQUEST["mdp2"])
		{
			throw new Exception("Le deux mots de passes ne coresspondent pas");
		}

		$pseudo = Validation::netoyerString($_REQUEST["name"]);

		if(is_null($pseudo))
		{
			throw new Exception("Le pseudonyme contien des valeurs illégales");
		}
		$mdl = new ModelConnecte();
		if(!$mdl->inscription($pseudo, $_REQUEST["mdp1"]))
		{
			throw new Exception("Erreur lors de l'enregistrement");
		}
		$_REQUEST["action"] = "connection";
		$_REQUEST["pseudonyme"] = $pseudo;
		$_REQUEST["motDePasse"] = $_REQUEST["mdp1"];
		new ControleurCommun();
	}
}
