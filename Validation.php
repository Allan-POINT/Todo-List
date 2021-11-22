<?php
class Validation
{
	public static function netoyerNomTache(string $nom)
	{
		return filter_var($nom, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
	}
	public static function netoyerCommentaireTache(string $comm)
	{
		return filter_var($comm, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
	}
	public static function validerCouleurTache(string $couleur)
	{
		return filter_var($couleur, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"([0-9]|([A-F]|[a-f])){6}")));
	}
	public static function validerEffectuationTache($estFait)
	{
		return filter_var($estFait, FILTER_VALIDATE_BOOL);
	}

	public static function netoyerEtValiderTache(string $nom, string $comm, string $couleur, $estFait)
	{
		$nom = self::netoyerNomTache($nom);
		$comm = self::netoyerCommentaireTache($comm);
		$couleurValide = self::validerCouleurTache($couleur);
		$estFaitValide = self::validerEffectuationTache($estFait);

		return array(
			'nom' => $nom,
			'commentaire' => $comm,
			'couleur' => $couleurValide,
			'estFait' => $estFaitValide
		);
	}
}
