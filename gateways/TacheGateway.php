<?php
require_once('modèle/Tache.php');
require_once('modèle/Validation.php');
require_once('modèle/Connection.php');
require_once('Gateway.php');


class TacheGateway implements Gateway
{
// Attributs
	private $conn;
//------------------------------
// Méthodes magiques
	public function __construct(Connection $conn)
	{
		$this->conn = $conn;
	}
//------------------------------
// Méthodes pérsonalisées

	public function inserer($itemAInserer)
	{
		if(get_class($itemAInserer) != "Tache")
		{
			throw new TypeError("L'item à inserer doit être une Tache");
		}
		$requette = "INSERT INTO _Tache(NomTache, TacheFaite, Commentaire, Couleur) VALUES(
			:nom, :fait, :commentaire, :couleur
			)";

		return $this->conn->executeQuery($requette, [
			':nom' => [$itemAInserer->nom, PDO::PARAM_STR],
			':fait'=> [$itemAInserer->estFait, PDO::PARAM_BOOL],
			':commentaire' => [$itemAInserer->commentaire, PDO::PARAM_STR],
			':couleur' => [$itemAInserer->couleur, PDO::PARAM_STR]
		]);
	}

	public function modifier($itemAModifier)
	{
		if(get_class($itemAInserer) != "Tache")
		{
			throw new TypeError("L'item à modifier doit être une Tache");
		}
		$requette = "UPDATE _Tache SET 
			NomTache = :nom,
			Commentaire = :commentaire,
			Couleur = :couleur,
			TacheFaite = :fait
			WHERE
			tacheID = :id";
		return $this->conn->executeQuery($requette,[
			':nom' => [$itemAModifier->nom, PDO::PRAM_STR],
			':commentaire' => [$itemAModifier->commentaire, PDO::PARAM_STR],
			':couleur' => [$itemAModifier->couleur, PDO::PARAM_STR],
			':fait' => [$itemAModifier->estFait, PDO::PARAM_BOOL]
		]);
	}

	public function supprimer($itemASupprimer)
	{
		if(get_class($itemAInserer) != "Tache")
		{
			throw new TypeError("L'item à supprimer doit être une Tache");
		}
		$requette = "DELETE FROM _Tache WHERE tacheID=:id";
		return $this->conn->executeQuery($requette,
			[':id', [$itemASupprimer->tacheID]]
		);

	}

	public function getTacheParListe(TodoList $l) : iterable
	{
		$requete = "SELECT * FROM _Tache WHERE listID=:id";
		if(!$this->conn->executeQuery($requete, 
			[":id" => [$l->getID()]]))
		{
			return array();
		}

		$res = $this->conn->getResults();
		$taches = array();
		foreach($res as $tache)
		{
			$taches[] = new Tache(
				$tache["NomTache"],
				$tache["TacheFaite"],
				$tache["Commentaire"],
				$tache["Couleur"],
				$tache["tacheID"]
			);
		}
		return $taches;
	}
}
