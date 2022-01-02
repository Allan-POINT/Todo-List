<?php
require_once("DAL/Connection.php");
require_once("config/Validation.php");
require_once("DAL/gateways/CompteGateway.php");
require_once("DAL/gateways/ListeGateway.php");

class ModelConnecte
{
	/*
	 * \brief Permmet de créer une séssion pour se connécter
	 * \param[in]	login	Login de l'utilisateur.trice
	 * \param[in]	mdp	Mot de passe en claire de l'utilisateur.trice
	 * \return Le compte connécté
	 */
	public function connection(string $login, string $mdp) : Compte
	{
		// Connection à la basse de données
		global $dsn, $loginDB, $pswdDB;
		$gw = new CompteGateway(new Connection($dsn, $loginDB, $pswdDB));

		// Récupère le compte $login
		$compte = $gw->getCompteParPseudo($login);

		// Si il a pas trouvé le compte $login, c'est qu'il existe pas
		if($compte == null)
		{
			throw new Exception("Le login ou le mot de passe est incorecte");
		}

		// Verification du mdp
		if(!password_verify($mdp, $compte->getMotDePasse()))
		{
			throw new Exception("Le login ou le mot de passe est incorecte");
		}

		// Création de la session
		$_SESSION["login"] = $compte->getPseudonyme();
		$_SESSION["Lists"] = $compte->getListes();
		return $compte;
	}

	/*
	 * \brief Permet de savoir si un.e utilisateur.trice est connécté.e
	 * \return True si une personne est connéctée, sinon false
	 */
	public function estConnecte() : bool
	{
		if(isset($_SESSION["login"]) && !empty($_SESSION["login"]))
		{
			return true;
		}
		return false;
	}

	/*
	 * \brief Permet d'avoir les $nbElements listes de la page $page appartenant à $pseudo
	 * \param[in]	pseudo	Pseudo de l'utilisateur.trice
	 * \param[in]	page	Page à charger
	 * \param[in]	nbElements	Nombre de liste à charger
	 * \return La liste des listes de $pseudo
	 */
	public function getLists(string $pseudo, int $page, int $nbElements)
	{
		// Connéction a la base de données
		global $dsn, $loginDB, $pswdDB;
		$gw = new ListeGateway(new Connection($dsn, $loginDB, $pswdDB));

		return $gw->getListeParCreateur($page, $nbElements, $pseudo);
	}

	/*
	 * \brief Permet d'avoir les $nbElements taches de la page $page appartenant à $liste
	 * \param[in]	liste	ID de la liste
	 * \param[in]	page	Numéro de la page à charger
	 * \param[in]	nbElements	Nombre de tache à charger
	 * \return Les taches de la liste $liste
	 */
	public function getTaches(int $liste, int $page, int $nbElements)
	{
		// Connection à la base de données
		global $dsn, $loginDB, $pswdDB;
		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));

		return $gw->getTachesParIDListe($liste, $page, $nbElements);
	}

	/*
	 * \brief Permet de modifier l'état de taches
	 * \param[in]	taches	Toutes les tâches chargées
	 * \param[in]	tachesFaites	Toute les taches chargées faites 
	 * \return Le numéro de la listes modifiée
	 */
	public function setDoneTaches($taches, $tachesFaites) : int
	{
		global $dsn, $loginDB, $pswdDB;
		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));
		$tacheID = null;
		foreach($taches as $tache)
		{
			if(in_array($tache, $tachesFaites))
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
		return $gw->getListeParIDTache($tacheID);
	}

	/*
	 * \brief Créer une TodoList
	 * \param[in]	nom	nom de la TodoList à créer
	 * \return True si tout c'est bien passé. Sinon false.
	 */
	public function createTodoList(string $nom) : bool
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
		return $gw->inserer2($nom, $pseudo);
	}

	/*
	 * \brief Créer une tache
	 * \param[in]	nom	Nom de la tache
	 * \param[in]	comm	Commentaire de la tache
	 * \param[in]	list	ID de la liste à modifier
	 * \return True si tout c'est bien passé. Sinon false.
	 */
	public function createTask(string $nom, string $comm, int $list) : bool
	{
		global $dsn, $loginDB, $pswdDB;

		$gw = new TacheGateway(new Connection($dsn, $loginDB, $pswdDB));
		return $gw->insererSimple($nom, $comm, $list);
	}

	/*
	 * \brief Supprime une liste
	 * \param[in]	listID	ID de la liste a supprimer
	 * \return True en cas de succès. Sinon false
	 */
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
