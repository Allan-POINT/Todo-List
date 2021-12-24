<?php
require_once("DAL/Connection.php");
require_once("config/Validation.php");
require_once("DAL/gateways/CompteGateway.php");
require_once("DAL/gateways/ListeGateway.php");

class ModelConnecte
{
	public function connection(string $login, string $mdp) : Compte
	{
		require("config/dsn.php");
		$gw = new CompteGateway(new Connection($dsn, "alpoint", "allanallan"));
		$compte = $gw->getCompteParPseudo($login);
		if($compte == null)
		{
			throw new Exception("Le login ou le mot de passe est incorecte");
		}
		if(!password_verify($mdp, $compte->getMotDePasse()))
		{
			throw new Exception("Le login ou le mot de passe est incorecte");
		}
		$_SESSION["login"] = $compte->getPseudonyme();
		$_SESSION["Lists"] = $compte->getListes();
		return $compte;
	}

	public function estConnecte() : bool
	{
		if(isset($_SESSION["login"]) && !empty($_SESSION["login"]))
		{
			return true;
		}
		return false;
	}

	public function getLists(string $pseudo, int $page, int $nbElements)
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new ListeGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $gw->getListeParCreateur($page, $nbElements, $pseudo);
	}

	public function getTaches(int $liste, $page, $nbElements)
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $taches = $gw->getTachesParIDListe($liste, $page, $nbElements);
	}

	public function setDoneTaches()
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));
		$tacheID = null;
		foreach($_REQUEST["exist"] as $tache)
		{
			if(in_array($tache, isset($_REQUEST["estFait"])?$_REQUEST["estFait"]:array() ))
			{
				if(!$gw->modifierDoneTache($tache, true))
				{
					throw new Exception("Erreur lors de la modification du statut de la tache $tache");
				}
			}else
			{
				if(!$gw->modifierDoneTache($tache, false))
				{
					throw new Exception("Erreur lors de la modification du statut de la tache $tache");
				}
			}
			$tacheID = $tache;
		}
		$_REQUEST["action"] = "seeList";
		$_REQUEST["list"] = $gw->getListeParIDTache($tacheID);
	}
	public function createTodoList(string $nom)
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new ListeGateway(new Connection($dsn, $loginDB, $pswdDB));
		if(!$this->estConnecte())
		{
			throw new Exception("Il faut être connecté.e pour créer un Todo List.");
		}
		$pseudo = Validation::netoyerString($_SESSION["login"]);
		if(is_null($pseudo))
		{
			throw new Exception("Erreur avec la valeur enregistré du pseudonyme");
		}
		$gw->inserer2($nom, $pseudo);
	}
	public function createTask(string $nom, string $comm, int $list)
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));
		if(!$gw->insererSimple($nom, $comm, $list))
		{
			throw new Exception("Erreur lors de la création de la tache");
		}
	}
	public function supprimerListe(int $listID) : bool
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new ListeGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $gw->supprimerAvecListID($listID);
	}
	public function delTask(int $id) : bool
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $gw->supprimerAvecTacheID($id);
	}
	public function destroySession()
	{
		session_unset();
		session_destroy();
		$_SESSION = array();
	}
	public function modifierNomListe(int $idListe, string $nouveauNom)
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new ListeGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $gw->modiferNomListe($idListe, $nouveauNom);
	}
	public function modifierNomCommTache(int $idTache, string $nom, string $comm)
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $gw->modifierNomCommTache($idTache, $nom, $comm);
	}
	public function inscription(string $pseudo, string $mdp) : bool
	{
		global $dsn, $loginDB, $pswdDB;
		$mdp_H = password_hash($mdp, PASSWORD_BCRYPT);
		$gw = new CompteGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $gw->Inscription($pseudo, $mdp_H,);
		
	}
	public function getNomListe(int $id): string
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new ListeGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $gw->getListeParID($id)->getNom();
	}
	public function getMaxPageTaches(int $listeID, int $nbElements) : int
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));
		$nbTotal = $gw->getNbTacheParListeID($listeID);
		return ceil($nbTotal/$nbElements);
	}
	public function getMaxPageListes(string $createur, int $nbElements) : int
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new ListeGateway(new Connection($dsn, $loginDB, $pswdDB));
		$nbTotal = $gw->getNbListesParCreateur($createur);
		return ceil($nbTotal/$nbElements);
	}
}
