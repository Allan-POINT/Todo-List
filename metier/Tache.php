<?php
class Tache
{
	// Attributs
	private $nom;
	private $fait;
	private $commentaire;
	private $tacheID;

	// Constructeur
	public function __construct(string $nom, bool $estFait=false, string $commentaire="", int $tacheID)
	{	
		$this->nom = $nom;
		$this->fait = $estFait;
		$this->commentaire = $commentaire;
		$this->tacheID = $tacheID;
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

	public function getCommentaire() : string
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
}
