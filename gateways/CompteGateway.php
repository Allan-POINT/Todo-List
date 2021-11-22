<?php
require_once("Gateway.php");
class CompteGateway implements Gateway
{
	private $conn;
	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function inserer($itemAInserer)
	{
		if(get_class($itemAInserer) != "Compte")
		{
			throw new TypeError("L'élement à inserer doit être de type compte");
		}

		$requette = "INSERT INTO _Compte(pseudonyme, dateCreation, motDePasse)
			VALUSES(:pseudo, :date, :mdp)";
		return $this->conn->executeQuerry($requette, [
			":pseudo" => [$itemAInserer->getPseudonyme(), PDO::PARAM_STR],
			":date" => ["STR_TO_DATE(".$itemAInserer->getDateCreation().")", PDO::PARAM_STR],
			":mdp" => [$itemAInserer->getMotDePasse(), PDO::PARAM_STR]
		]);
	}

	public function modifier($itemAModifier)
	{
		if(get_class($itemAInserer) != "Compte")
		{
			throw new TypeError("L'élement à modifier doit être de type compte");
		}
		$requette = "UPDATE _Compte SET pseudonyme=:pseudo, dateCreation=:date, motDePasse=:mdp";
		return $this->conn->executeQuerry($requette, [
			":pseudo" => [$itemAModifier->getPseudonyme(), PDO::PARAM_STR],
			":date" => [$itemAModifier->getDateCreation(), PDO::PARAM_STR],
			":mdp" => [$itemAModifier->getMotDePasse(), PDO::PARAM_STR]
		]);
		
	}

	public function supprimer($itemASupprimer)
	{
		if(get_class($itemAInserer) != "Compte")
		{
			throw new TypeError("L'élement à supprimer doit être de type compte");
		}
		$requette = "DELETE FROM _Compte WHERE compteID=:id";
		return $this->conn->executeQuerry($requette, [
			":id" => [$itemAModifier->getID(), PDO::PARAM_INT]
		]);
		
	}

	public function getCompteParPseudo(string $pseudo) : iterable
	{
		$requete = "SELECT * FROM _Compte WHERE pseudonyme=:pseudo";

		if(!$this->conn->executeQuerry($requete, [":pseudo" => [$pseudo, PDO::PARAM_STR]]))
		{
			return array();
		}
		$comptesSQL = $this->conn->getResults();
		$comptes = array();
		$listes = array();
		$requete = "SELECT * FROM _TodoList WHERE Createur=:id";

		foreach($comptesSQL as $compte)
		{
			if(!$this->conn->executeQuerry($requete, [":id" => [$compte["compteID", PDO::PARAM_STR]]))
			{
				$listes = array();
			}
			else
			{
				$listesSQL = $this->conn->getResults();
				foreach($listesSQL as $liste)
				{
					$listes[] = new TodoList(
						$liste["listeID"],
						$liste["nom"],
						$liste["Createur"],
						$liste["dateCreation"],
						$liste["public"]
					);
				}
			}
			$comptes[] = new Compte(
				$compte["pseudonyme"],
				$compte["dateCreation"],
				$listes,
				$compte["motDePasse"],
				$compte["compteID"]
			);
			return $comptes;
		}
	}
}
