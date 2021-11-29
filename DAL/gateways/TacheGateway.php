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
			':nom' => [$tacheAModifier->nom, PDO::PRAM_STR],
			':commentaire' => [$tacheAModifier->commentaire, PDO::PARAM_STR],
			':fait' => [$tacheAModifier->estFait, PDO::PARAM_BOOL]
		]);
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
			[':id', [$tacheASupprimer->tacheID]]
		);

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
		$requete = "SELECT * FROM _Tache WHERE listID=:id ORDER BY NomTache LIMIT :p+:n, :n";
		if(!$this->conn->executeQuery($requete,[ 
			":id" => [$l->getID(), PDO::PARAM_INT],
			":p" => [$page-1, PDO::PARAM_INT],
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
}
