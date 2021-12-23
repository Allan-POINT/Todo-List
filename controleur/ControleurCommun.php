<?php
require_once("config/Validation.php");
require_once("modeles/ModelConnecte.php");
class ControleurCommun {
		
	function __construct() {
		global $rep,$vues; // nécessaire pour utiliser variables globales
		$dVueEreur = array ();
		try{
			$action=Validation::netoyerString(isset($_REQUEST['action']) ? $_REQUEST["action"] : "");
			switch($action) {
				//pas d'action, on r�initialise 1er appel
				case NULL:
					$this->Reinit();
					break;
				case "connection":
					if(!isset($_POST["pseudonyme"]) || !isset($_POST["motDePasse"]))
					{
						$erreurs[] = "Erreur lors de la transmission des informations de connections";
						throw new Exception();
					}

					$login = Validation::netoyerString($_POST["pseudonyme"]);
					$mdp = Validation::netoyerString($_POST["motDePasse"]);
					
					if(is_null($login) || is_null($mdp))
					{
						throw new ValueError("Le login ou le mot de passe contient des valeurs illégales");
					}

					$compte = $this->connection($_POST["pseudonyme"], $_POST["motDePasse"]);
					if(!is_null($compte))
					{
						header("Location: ?action=seeLists");
					}
					else
					{
						header("Location: ?action=GloubiBoulga");
					}
					break;
				case "SeConnecter":
				default:
					require("vues/connection.php");
					break;
			}
		}catch(PDOException $e)
		{
			//si erreur BD, pas le cas ici
			$ereurs[] = "Erreur inattendue!!! ";
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

	function connection(string $login, string $mdp) : Compte
	{
		$mdl = new ModelConnecte();
		$compte = $mdl->connection($login, $mdp);
		return $compte;
	}
}
