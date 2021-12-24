<?php
require_once("metier/Compte.php");
require_once("DAL/gateways/ListeGateway.php");
class CompteGateway
{
	// Attributs
	private $conn;

	// Constructeur
	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	/*
	 * Paramètre	: comptreAInserer => Objet de type compte déstiné à être mémorisé
	 * Retour	: True si la requette a été bien éxécuter. Sinon false
	 * Finalité	: Enregistrer en base de données un Compte
	 */
	public function inserer(Compte $compteAInserer) : bool
	{
		$requette = "INSERT INTO _Compte(pseudonyme, dateCreation, motDePasse)
			VALUSES(:pseudo, :date, :mdp)";
		return $this->conn->executeQuerry($requette, [
			":pseudo" => [$compteAInserer->getPseudonyme(), PDO::PARAM_STR],
			":date" => ["STR_TO_DATE(".$compteAInserer->getDateCreation().")", PDO::PARAM_STR],
			":mdp" => [$compteAInserer->getMotDePasse(), PDO::PARAM_STR]
		]);
	}
	public function inscription(string $pseudo, string $mdp_H) : bool
	{
		$requette = "INSERT INTO _Compte(pseudonyme, dateCreation, motDePasse)
			VALUES(:p, NOW(), :mdp)";
		return $this->conn->executeQuery($requette, array(
			":p" => [$pseudo, PDO::PARAM_STR],
			":mdp" => [$mdp_H, PDO::PARAM_STR]
		));
	}

	/*
	 * Paramètre	: compteAEditer => Compte à éditer en base de données
	 * Retour	: True si la requette a été bien éxécuter. Sinon false
	 * Finalité	: Éditer un Compte identifié par son pseudonyme en base de données
	 */
	public function modifier(Compte $compteAModifier)
	{
		$requette = "UPDATE _Compte SET pseudonyme=:pseudo, dateCreation=:date, motDePasse=:mdp";
		return $this->conn->executeQuerry($requette, [
			":pseudo" => [$compteAModifier->getPseudonyme(), PDO::PARAM_STR],
			":date" => ["STR_TO_DATE(".$compteAModifier->getDateCreation().")", PDO::PARAM_STR],
			":mdp" => [$compteAModifier->getMotDePasse(), PDO::PARAM_STR]
		]);
		
	}

	/*
	 * Paramètres	: compteASupprimer => compte à supprimer de la base de données
	 * Retour	: True si la requette a été bien éxécuter. Sinon false
	 * Finalité	: Supprimer un compte de la base de données
	 */
	public function supprimer($compteASupprimer)
	{
		$requette = "DELETE FROM _Compte WHERE pseudonyme=:id";
		return $this->conn->executeQuerry($requette, [
			":id" => [$compteAModifier->getPseudonyme(), PDO::PARAM_INT]
		]);
		
	}

	/*
	 * Paramètres	: pseudo => Pseudonyme du compte a récupérer en base de données
	 * Retour	: Un tableau contenant tout les compte avec le pseudonyme <pseudo> (devrais avoir une taille entre 0 et 1)
	 * Finalité	: Récupérer un Compte <pseudo> en base de données et l'instancier.
	 */
	public function getCompteParPseudo(string $pseudo) : ?Compte
	{
		$gw = new ListeGateway($this->conn);
		$requete = "SELECT * FROM _Compte WHERE pseudonyme=:pseudo";
		if(!$this->conn->executeQuery($requete, [":pseudo" => [$pseudo, PDO::PARAM_STR]]))
		{
			return array();
		}
		$comptesSQL = $this->conn->getResults();
		if(sizeof($comptesSQL) != 0)
		{
			$compte = new Compte(
				$comptesSQL[0]["pseudonyme"],
				$comptesSQL[0]["dateCreation"],
				$gw->getListeParCreateur(1, 10, $comptesSQL[0]["pseudonyme"]),
				$comptesSQL[0]["motDePasse"],
			);
			return $compte;
		}
		return null;
	}
}
