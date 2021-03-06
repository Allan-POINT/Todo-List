<?php
class Tache
{
	// Attributs
	private $nom;
	private $fait;
	private $commentaire;
	private $tacheID;
	private $datetime;
	private $listeID;

	// Constructeur
	public function __construct(string $nom, bool $estFait=false, ?string $commentaire="", int $tacheID, string $datetime, int $listeID)
	{	
		$this->nom = $nom;
		$this->fait = $estFait;
		$this->commentaire = $commentaire;
		$this->tacheID = $tacheID;
		$this->datetime = $datetime;
		$this->listeID = $listeID;
	}


	// Accesseurs / Mutatteurs
	public function getNom() : string
	{
		return $this->nom;
	}

	public function setNom(string $nouveauNom)
	{
		$this->nom = $nouveauNom;
	}

	public function getCommentaire() : ?string
	{
		return $this->commentaire;
	}

	public function setCommentaire(string $nouveauComm)
	{
		$this->commentaire = $nouveauComm;
	}

	public function estFait() : bool 
	{
		return $this->fait;
	}

	public function setFait(bool $fait)
	{
		$this->fait = $fait;
	}

	public function getID() : int
	{
		return $this->tacheID;
	}
	public function getDateTime(): string
	{
		return $this->dateTime;
	}
	public function getListeID()
	{
		return $this->listeID;
	}
}
