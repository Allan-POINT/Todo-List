<?php
require_once("metier/Tache.php");
require_once("metier/TodoList.php");
require_once("DAL/gateways/TacheGateway.php");
class ListeGateway
{

	// Attributs
	private $conn;


	// Constructeurs
	public function __construct($conn)
	{
		$this->conn = $conn;
	}


	/*
	 * Paramètre	: l => TodoList à inserer en base de données
	 * Retour	: True si la requette à résussie à s'éxécuter. Sinon False
	 * Finalité	: Inserer une TodoList en base de données
	 */
	public function inserer(TodoList $l) : bool
	{
		$requete = "INSERT INTO _TodoList(nom, dateCreation, createur)
			VALUES(:nom, :date, :createur)";
		return $this->conn->executeQuery($requete, [
			":nom" => [$l->getNom(), PDO::PARAM_STR],
			":date" => ["STR_TO_DATE(".$l->getDateCreation().")", PDO::PARAM_STR],
			":createur" => [$l->getCreateur(), PDO::PARAM_STR]
		]);
	}

	/*
	 * Paramètres	: l => TodoList à supprimer de la base de données
	 * Retour	: True si la requette à résussie à s'éxécuter. Sinon False
	 * Finalité	: Supprimer une liste <l> de la base de données
	 */
	public function supprimer(TodoList $l) : bool 
	{
		$requete = "DELETE FROM _TodoList WHERE listeID=:id";
		return $this->conn->executeQuery($requete,[
			":id"=>[$l->getID(), PDO::PARAM_INT]
		]);
	}

	/*
	 * Paramètres	: l => TodoList à éditer en base de données
	 * Retour	: True si la requette à résussie à s'éxécuter. Sinon False
	 * Finalité	: Éditer la TodoList <l> en base de données
	 */
	public function modifier(TodoList $l) : bool
	{
		$requete="UPDATE _TodoList SET
			nom=:n, public=:p";
		return $this->conn->executeQuery($requete, [
			":n" => [$l->getNom(), PDO::PARAM_STR],

		]);
	}

	/*
	 * Paramètres	: page 		=> Numéro de la page à afficher
	 * 		  nbTache	=> Nombre de tâches à afficher par pages
	 * Retour	: Retourne un tableau de listes de taille maximale <nbTache> ordoné par date de création
	 * Finalité	: Récuperer les todoLists en bases de données par ordre de création et les instancier
	 */
	public function listeParDate(int $page, int $nbTache) : iterable
	{
		$gwTache = new TacheGateway($this->conn);
		$lites = array();
		$requete = "SELECT * FROM _TodoList ORDER BY dateCreation LIMIT :p+:n, :n";
		$isOK=$this->conn->executeQuery($requete, [
			":p" => [$page-1, PDO::PARAM_INT],
			":n" => [$nbTache, PDO::PARAM_INT]
		]);
		if(!$isOK)
		{
			return array();
		}

		$res = $this->conn->getResults();
		foreach($res as $liste)
		{
			$listes[] = new TodoList(
				$liste["listeID"],
				$liste["nom"],
				$liste["Createur"],
				$liste["dateCreation"],
				$gwTache->getTachesParIDListe($liste["listeID"])
			);
		}
		return $listes;
	}

	/*
	 * Paramètres	: page 		=> Numéro de la page à afficher
	 * 		  nbTache	=> Nombre de tâches à afficher par pages
	 * Retour	: Retourne un tableau de listes de taille maximale <nbTache> ordoné par nom de liste (ordre lexicographique)
	 * Finalité	: Récuperer les todoLists en bases de données par ordre de lexicographique et les instancier
	 */
	public function listeParNom(int $page, int $nbListes) : iterable
	{
		$gwTache = new TacheGateway($this->conn);
		$lites = array();
		$requete = "SELECT * FROM _TodoList ORDER BY nom  LIMIT :p+:n, :n";
		$isOK=$this->conn->executeQuery($requete, [
			":p" => [$page-1, PDO::PARAM_INT],
			":n" => [$nbListes, PDO::PARAM_INT]
		]);
		if(!$isOK)
		{
			return array();
		}

		$res = $this->conn->getResults();
		
		foreach($res as $liste)
		{
			$listes[] = new TodoList(
				$liste["listeID"],
				$liste["nom"],
				$liste["Createur"],
				$liste["dateCreation"],
				$gwTache->getTachesParIDListe($liste["listeID"], 1, 10)
			);
		}
		return $listes;
	}
	
	/*
	 * Paramètres	: page 		=> Numéro de la page à afficher
	 * 		  nbTache	=> Nombre de tâches à afficher par pages
	 * 		  createur	=> Pseudonyme du créateur des listes enregistrés en base de données
	 * Retour	: Retourne un tableau de listes de taille maximale <nbTache> créer par <createur> de liste
	 * Finalité	: Récuperer les todoLists en bases de données créer par créateur et les instancier
	 */
	public function getListeParCreateur(int $page, int $nbListes, string $createur) : iterable
	{
		$gwTache = new TacheGateway($this->conn);
		$lites = array();
		$requete = "SELECT * FROM _TodoList WHERE Createur = :c  LIMIT :p+:n, :n";
		$isOK=$this->conn->executeQuery($requete, [
			":c" => [$createur, PDO::PARAM_STR],
			":p" => [$page-1, PDO::PARAM_INT],
			":n" => [$nbListes, PDO::PARAM_INT]
		]);
		if(!$isOK)
		{
			return array();
		}

		$res = $this->conn->getResults();
		
		foreach($res as $liste)
		{
			$listes[] = new TodoList(
				$liste["listeID"],
				$liste["nom"],
				$liste["Createur"],
				$liste["dateCreation"],
				$gwTache->getTachesParIDListe($liste["listeID"], 1, 10)
			);
		}
		return $listes;
	}

	/*
	 * Paramètres	: l	=> TodoList à acctualiser
	 * 		  pages => Numéro de la page à afficher
	 * 		  nbTache	=> Nombre de tâches à afficher par pages
	 * Retour	: Retourne la liste <l> avec les <nbTaches> tâches de la page <page>
	 * Finalité	: Actualiser les taches contenues dans <l> en fonction de <page> et <nbTaches>
	 */
	public function ActualiserListe(TodoList $l, int $page, int $nbTaches) : TodoList
	{
		$gwTache = new TacheGateway($this->conn);
		$l->setTaches($gwTaches->getTachesParIDListe($l->getID(), $page, $nbTaches));
		return $l;
	}
}
