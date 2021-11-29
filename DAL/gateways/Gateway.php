<?php
interface Gateway
{
	public function inserer($itemAInserer);
	public function modifier($itemAModifier);
	public function supprimer($itemASupprimer);
}
