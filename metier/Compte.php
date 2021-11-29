<?php

class Compte
{
	// Attributs
	private $pseudonyme;
	private $dateCreation;
	private $listes;
	private $motDePasse;


	//Constructeur
	public function __construct(string $nom, string $dateCreation=null, iterable $lists = array(), string $motDePasse)
	{
		$this->pseudonyme = $nom;
		$this->dateCreation = $dateCreation == null ? date("j/m/Y") : $dateCreation;
		$this->listes = $lists;
		$this->motDePasse = $motDePasse;
	}

	//Accesseurs/Mutatteurs
	public function getPseudonyme() : string
	{
		return $this->pseudonyme;
	}

	public function setPseudonyme(string $nouveauPseudonyme) : void
	{
		if(!empty($nouveauPseudonyme))
		{
			$this->pseudonyme = $nouveauPseudonyme;
		}
	}

	public function getDateCreation()
	{
		return $this->dateCreation;
	}

	public function getMotDePasse()
	{
		return $this->motDePasse;
	}

	public function getListes()
	{
		return $this->listes;
	}

	public function setListes(iterable $listes)
	{
		$this->listes = $listes;
	}

	public function addListe(TodoList $l)
	{
		$this->listes[] = $l;
	}
}
