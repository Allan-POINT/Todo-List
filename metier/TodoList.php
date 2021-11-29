<?php

class TodoList
{
	// Attrubuts
	private $nom;
	private $createur;
	private $dateCreation;
	private $id;
	private $taches;


	// Constructeurs
	public function __construct(int $id, string $nom, string $createur, string $dateCreation, iterable $taches)
	{
		$this->nom = $nom;
		$this->createur = $createur;
		$this->dateCreation = $dateCreation;
		$this->id = $id;
		$this->taches = $taches;
	}


	// Accesseurs / Mutatteurs
	public function getNom() : string
	{
		return $this->nom;
	}

	public function getDateCreation() : string
	{
		return $this->dateCreation;
	}
	

	public function getCreateur() : string
	{
		return $this->createur;
	}

	public function getID() : int
	{
		return $this->id;
	}
	public function getTaches() : iterable
	{
		return $this->taches;
	}
	public function setTaches(array $taches)
	{
		$this->taches = $taches;
	}
	public function addTache(Tache $t)
	{
		$this->taches[] = $t;
	}
}
