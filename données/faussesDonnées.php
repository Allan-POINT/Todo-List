<?php
require_once("metier/Tache.php");
require_once("metier/TodoList.php");
require_once("metier/Compte.php");

$taches = [
	new Tache("Tache1", true, "Commentaire de tâche1",1),
	new Tache("Tache2", false, "Commentaire de tâche2", 1),
	new Tache("Tache3", true, "", "177013", 1)
];

$todoLists = [
	new TodoList(1, "Liste1", "Erina", date("d-m-Y"), $taches),
	new TodoList(3, "Liste3", "Erina", date("d-m-Y"), $taches)
];

$comptes = [
	new Compte("Erina", null,$todoLists, "Erina"),
	new Compte("Shino", null, [], "Shino")
];
