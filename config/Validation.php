<?php
class Validation
{

	public static function netoyerNomTache(string $nom) : ?string
	{
		return filter_var($nom, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
	}
	public static function netoyerCommentaireTache(string $comm) : ?string
	{
		return filter_var($comm, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
	}
	public static function validerEffectuationTache($estFait) : bool
	{
		return filter_var($estFait, FILTER_VALIDATE_BOOL);
	}

	public static function netoyerEtValiderTache(string $nom, string $comm, bool $estFait)
	{
		$nom = self::netoyerNomTache($nom);
		$comm = self::netoyerCommentaireTache($comm);
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

	public static function validerUnIntSuperieurZero($int)
	{
		return filter_var($int, FILTER_VALIDATE_INT, array("min_range"=>1));
	}
}
