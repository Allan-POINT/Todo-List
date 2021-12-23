<?php
class Validation
{

	public static function netoyerString(?string $str) : ?string
	{
		return filter_var($str, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
	}
	public static function validerEffectuationTache($estFait) : bool
	{
		return filter_var($estFait, FILTER_VALIDATE_BOOL);
	}

	public static function netoyerEtValiderTache(string $nom, string $comm, bool $estFait)
	{
		$nom = self::netoyerString($nom);
		$comm = self::netoyerString($comm);
		$estFaitValide = self::validerEffectuationTache($estFait);

		if($nom == null || $comm == null || !$estFaitValide)
		{
			throw new UnexpectedValueException("Une des valeurs de la tache $nom n'est pas accéptable.");
		}

		return array(
			'nom' => $nom,
			'commentaire' => $comm,
			'estFait' => $estFait
		);
	}

	public static function validerUnIntSupperieurZero($int)
	{
		return filter_var($int, FILTER_VALIDATE_INT, array("min_range"=>1));
	}

	public static function validerNomTiretNum($valeur, $nom)
	{
		return filter_var($valeur, FILTER_VALIDATE_REGEXP, array("option" => array("regexp" => "$name-[1-9][0-9]+$")));
	}
}
