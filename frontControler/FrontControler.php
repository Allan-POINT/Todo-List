<?php
require_once("controleur/ControleurConnecte.php");
require_once("controleur/ControleurCommun.php");
require_once("modeles/ModelConnecte.php");
require_once("config/Validation.php");
require("config/dsn.php");
class FrontControler
{
	private $actions = array(
		"Compte" => [
			"addList", "setTacheFait", "editionTache", "déconéction", "seeLists", 
			"seeList", "wantAddList", "wantAddTask", "addTask", "supprimerListe", 
			"delTask", "logout", "veuxModifierListe", "modifyList", "veuxModifierTache"],
		"Visiteur" => ["seConnceter", "connection", "veuxSInscrire", "signin"]
	);

	public function start()
	{
		session_start();
		$modelCompte = new ModelConnecte();
		$connecte = $modelCompte->estConnecte();
		$action = Validation::netoyerString(isset($_GET["action"]) ? $_GET["action"] : "");
		if(in_array($action, $this->actions["Compte"]))
		{
			if(!$connecte)
			{
				require("vues/connection.php");
			}else
			{
				$controler = new controleurConnecte();
			}
		}
		else
		{
			if(!$connecte)
			{
				$controler = new controleurCommun();
			}
			else
			{
				$_REQUEST["action"] = "seeLists";
				new ControleurConnecte();
			}
		}
	}
}
