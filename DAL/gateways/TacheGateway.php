<?php
require_once('metier/Tache.php');


class TacheGateway
{
	// Attributs
	private $conn;
	
	// constructeur
	public function __construct(Connection $conn)
	{
		$this->conn = $conn;
	}


	/*
	 * Paramètre	: tacheAInserer	=> Tache à enregistrer en base de données
	 * Retour	: True si la requete c'est correctement éxécuter. Sinon false
	 * Finalité	: Enregister en base de données une tache.
	 */
	public function inserer(Tache $tacheAInserer) : bool
	{
		$requette = "INSERT INTO _Tache(NomTache, TacheFaite, Commentaire) VALUES(
			:nom, :fait, :commentaire
			)";

		return $this->conn->executeQuery($requette, [
			':nom' => [$tacheAInserer->nom, PDO::PARAM_STR],
			':fait'=> [$tacheAInserer->estFait, PDO::PARAM_BOOL],
			':commentaire' => [$tacheAInserer->commentaire, PDO::PARAM_STR],

		]);
	}

	public function insererSimple(string $nom, string $comm, int $id) : bool
	{
		$requette = "INSERT INTO _Tache(NomTache, TacheFaite, Commentaire, listID) VALUES(
			:nom, :fait, :commentaire, :id
			)";

		return $this->conn->executeQuery($requette, [
			":nom" => [$nom, PDO::PARAM_STR],
			":fait"=> [false, PDO::PARAM_BOOL],
			":commentaire" => [$comm, PDO::PARAM_STR],
			":id" => [$id, PDO::PARAM_INT]

		]);
		
	}

	/*
	 * Paramètre	: tacheAModifier => Tache à éditer en base de données
	 * Retour	: True si la requete c'est correctement éxécuter. Sinon false
	 * Finalité	: Édite la tache <tacheAModifier> en base de données.
	 */
	public function modifier(Tache $tacheAModifier)
	{
		$requette = "UPDATE _Tache SET 
			NomTache = :nom,
			Commentaire = :commentaire,
			TacheFaite = :fait
			WHERE
			tacheID = :id";
		return $this->conn->executeQuery($requette,[
			':nom' => [$tacheAModifier->getNom(), PDO::PRAM_STR],
			':commentaire' => [$tacheAModifier->getCommentaire(), PDO::PARAM_STR],
			':fait' => [$tacheAModifier->estFait(), PDO::PARAM_BOOL]
		]);
	}

	public function modifierDoneTache(int $idTache, bool $done)
	{
		$requette = "UPDATE _Tache SET
			TacheFaite = :d
			WHERE
			tacheID = :id";
		return $this->conn->executeQuery($requette, array(
			":d" => [$done, PDO::PARAM_BOOL],
			":id" => [$idTache, PDO::PARAM_INT]
		));
	}
	public function modifierNomCommTache(int $id, string $nom, string $comm)
	{
		$requette = "UPDATE _Tache
			SET NomTache = :n, Commentaire = :c
			WHERE tacheID = :id";
		return $this->conn->executeQuery($requette, array(
			":n" => [$nom, PDO::PARAM_STR],
			":c" => [$comm, PDO::PARAM_STR],
			":id" => [$id, PDO::PARAM_INT]
		));
	}

	/*
	* Paramètre	: tacheASupprimer => Tache à supprimer en base de données
	* Retour	: True si la requete c'est correctement éxécuter. Sinon false
	* Finalité	: Supprime la tache <tacheASupprimer> en base de données.
	*/
	public function supprimer(Tache $tacheASupprimer)
	{
		$requette = "DELETE FROM _Tache WHERE tacheID=:id";
		return $this->conn->executeQuery($requette,
			[':id', [$tacheASupprimer->tacheID, PDO::PARAM_INT]]
		);

	}

	public function supprimerAvecTacheID(int $id)
	{
		$requette = "DELETE FROM _Tache WHERE tacheID=:id";
		return $this->conn->executeQuery($requette,[
			':id' => [$id, PDO::PARAM_INT]
		]);
	}

	/*
	 * Paramètre	: l		=> Identifiant de la TodoList.
	 * 		  page		=> Numéro de la page à retourner
	 * 		  nbTache	=> Nombre de tache par page 
	 * Retour	: Retourne les taches de la liste <l> par orde de nom de tache
	 * Finalité	: Récuperer les Taches d'une liste et les instancier
	 */
	public function getTachesParIDListe(int $l, int $page, int $nbTache) : iterable
	{
		$requete = "SELECT * FROM _Tache WHERE listID=:id ORDER BY NomTache LIMIT :p, :n";
		if(!$this->conn->executeQuery($requete,[ 
			":id" => [$l, PDO::PARAM_INT],
			":p" => [($page-1)*$nbTache, PDO::PARAM_INT],
			":n" => [$nbTache, PDO::PARAM_INT]
			]))
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
				$tache["tacheID"]
			);
		}
		return $taches;
	}

	public function getListeParIDTache(?int $tache) //: ?int
	{
		if(is_null($tache))
		{
			throw new Exception("Le numero de tache ne doit pas être === à null");
		}
		$requette = "SELECT listID FROM _Tache WHERE tacheID = :id";
		if(!$this->conn->executeQuery($requette, array(":id"=>[$tache, PDO::PARAM_INT])))
		{
			throw new Exception("Problème lors de la récupération de la liste de la tache $tache");
		}
		return $this->conn->getResults()[0][0];
	}
}
