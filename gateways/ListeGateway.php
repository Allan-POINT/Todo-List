<?php
class ListeGateway
{

	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function inserer($l) : bool
	{
		$requete = "INSERT INTO _TodoList(nom, dateCreation, public, createur)
			VALUES(:nom, :date, :pub, :createur)";
		return $this->conn->executeQuery($requete,
			":nom" => [$l->getNom(), PDO::PARAM_STR],
			":date" => ["STR_TO_DATE(".$l->getDateCreation().")", PDO::PARAM_STR],
			":pub" => [$l->estPublic(), PDO::PARAM_BOOL],
			":createur" => [$l->getCreateur(), PDO::PARAM_INT]
		]);
	}

	public function supprimer($l) : bool 
	{
		$requete = "DELETE FROM _TodoList WHERE listeID=:id";
		return $this->conn->executeQuery($requete,[
			":id"=>[$l->getID(), PDO::PARAM_INT]
		]);
	}

	public function modifier($l) : bool
	{
		$requete="UPDATE _TodoList SET
			nom=:n, public=:p";
		return $this->conn->executeQuery($requete, [
			":n" => [$l->getNom(), PDO::PARAM_STR],
			":p" => [$l->estPublic(), PDO::PARAM_BOOL]
		]);
	}
	public function listeParDate(int $page, int $nbTache) : iterable
	{
		$lites = array();
		$requete = "SELECT * FROM _TodoList ORDER BY dateCreation LIMIT (:p -1)+:n, :n";
		$isOK=$this->conn->executeQuery($requete, [
			":p" => [$page, PDO::PARAM_INT],
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
				$liste["public"]
			);
		}
		return $listes;
	}	
	public function listeParNom() : iterable
	{
		$lites = array();
		$requete = "SELECT * FROM _TodoList ORDER BY nom  LIMIT (:p -1)+:n, :n";
		$isOK=$this->conn->executeQuery($requete, [
			":p" => [$page, PDO::PARAM_INT],
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
				$liste["public"]
			);
		}
		return $listes;
	}	
}
