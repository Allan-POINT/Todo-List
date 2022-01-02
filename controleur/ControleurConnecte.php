<?php
require_once("config/Validation.php");
require_once("controleur/ControleurCommun.php");
class ControleurConnecte {
		
	function __construct() {
		$erreurs = array ();
		try{
			// Récupération de l'action a effectuer
			$action=Validation::netoyerString($_REQUEST['action']);
			switch($action) {
				case NULL:
					$this->seeLists();
					break;
				case "seeLists":
					$this->seeLists();
					break;
				case "seeList":
					$this->seeList();
					break;
				case "supprimerListe":
					$this->supprimerListe();
					break;
				case "wantAddList":
					$this->wantAddList();
					break;
				case "addList":
					$this->addList();
					break;
				case "modifyList":
					$this->modifyList();
					break;
				case "setTacheFait":
					$this->editDone();
					break;
				case "editionTache":
					$this->editionTache();
					break;
				case "wantAddTask":
					$this->wantAddTask();
					break;
				case "addTask":
					$this->addTask();
					break;
				case "delTask":
					$this->delTask();
					break;
				case "logout":
					$this->logout();
					break;
				case "veuxModifierListe":
					$this->veuxModifierListe();
					break;
				case "veuxModifierTache":
					$this->veuxModifierTache();
					break;
				default:
					throw new Exception("Action inconnue");
					break;
			}
		}catch(PDOException $e)
		{
			echo "Erreur PDO";
			$erreurs[] =$e->getMessage();
			require ("vues/erreur.php");
		}
		catch (Exception $e2)
		{
			$erreurs[] =$e2->getMessage();
			require ("vues/erreur.php");
		}
	}

	/*
	 * \brief Permet d'afficher les lists de l'utilisateur.trice connecté.e
	 */
	function seeLists()
	{
		// Si la page n'est pas set, on prend la première page
		if(!isset($_REQUEST["page"]) || empty($_REQUEST["page"]))
		{
			$page = 1;
		}
		else
		{
			// si la validation a échouée, on prend la première page
			$page = Validation::validerUnIntSupperieurZero($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
		}

		// Si le nombre d'élément n'est pas set, on en prend par défaut 10
		if(!isset($_GET["nbElements"]) || empty($_GET["nbElements"]))
		{
			$nbElements = 10;
		}
		else
		{
			// Si la validation a échouée, on prend 10 éléments, sinon, le nombre désiré par l'utilisateur.trice
			$nbElements = Validation::validerUnIntSupperieurZero($_GET["nbElements"]) ? $_GET["nbElements"] : 10;
		}

		$mdl = new ModelConnecte();

		// Récupération des listes de l'utilisateur.trice connécté.e par le modèle
		$todoLists = $mdl->getLists(Validation::netoyerString($_SESSION["login"]), $page, $nbElements);

		// Récupération du numéro de page le plus grand en fonction des taches de l'utilisateur.trice et du nombre d'éléments demendé
		$maxPage = $mdl->getMaxPageListes(Validation::netoyerString($_SESSION["login"]), $nbElements);

		// Affichage de la vue
		require("vues/accueil.php");
	}

	/*
	 * \brief Permet de visualiser les tache d'une liste
	 */
	function seeList()
	{
		// Erreur si aucune liste n'est demendée.
		if(!isset($_REQUEST["list"]) || empty($_REQUEST["list"]))
		{
			throw new Exception("Aucune liste n'est demendée");
		}

		// Erreur si le numéro de la liste est < 0
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["list"]))
		{
			throw new Exception("Valeur illégale de la liste requétée");
		}

		// TODO: Verifier que c'est bien une liste de l'utilisateur.trice connécté.e


		// Si la page n'est pas set, on prend la première page
		if(!isset($_REQUEST["page"]) || empty($_REQUEST["page"]))
		{
			$page = 1;
		}
		else
		{
			// si la validation a échouée, on prend la première page
			$page = Validation::validerUnIntSupperieurZero($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
		}

		// Si le nombre d'élément n'est pas set, on en prend par défaut 10
		if(!isset($_GET["nbElements"]) || empty($_GET["nbElements"]))
		{
			$nbElements = 10;
		}
		else
		{
			// Si la validation a échouée, on prend 10 éléments, sinon, le nombre désiré par l'utilisateur.trice
			$nbElements = Validation::validerUnIntSupperieurZero($_GET["nbElements"]) ? $_GET["nbElements"] : 10;
		}
		$mdl = new ModelConnecte();

		// Récupération des taches dans le modèle
		$taches = $mdl->getTaches($_REQUEST["list"], $page, $nbElements);

		// Définition des variable nécéssaire à la vue.
		$actualList = $_REQUEST["list"];
		$nomListe = $mdl->getNomListe($actualList);
		$maxPage = $mdl->getMaxPageTaches($actualList, $nbElements);

		// Affichage de la vue
		require("vues/editeurDeStatuts.php");
	}

	/*
	 * \brief Permet d'enrgistrer l'état des taches d'une listes
	 */
	function editDone()
	{
		// estFait doit être un tableau des taches faites
		if(isset($_REQUEST["estFait"]))
		{
			if(!is_array($_REQUEST["estFait"]))
			{
				throw new Exception("La liste des taches faites doit être un tableau.");
			}
		}
		else
		{
			$_REQUEST["estFait"] = array();
		}

		// exist contient toute les taches de la page où été l'utilisateur.trice
		if(!isset($_REQUEST["exist"]))
		{
			throw new Exception("Aucune tâche n'est définit");
		}
		if(!is_array($_REQUEST["exist"]))
		{
			throw new Exception("La liste des taches doit être un tableau.");
		}

		$mdl = new ModelConnecte();

		// Enregistrement avec le modle
		$list = $mdl->setDoneTaches($_REQUEST["exist"], $_REQUEST["estFait"]);

		// Rediréction
		$_REQUEST["action"] = "seeList";
		$_REQUEST["list"] = $list;
		new ControleurConnecte();
	}

	/*
	 * \brief Permet d'afficher la page d'ajout de liste
	 */
	function wantAddList()
	{
		// Affichage de la vue
		require("vues/ajouterListe.php");
	}

	/*
	 * \brief Permet de créer une todoList
	 */
	function addList()
	{
		// Si le nom de la nouvelle liste n'existe pas ou si elle est vide, on lève une exception
		if(!isset($_REQUEST["nomNouvelleListe"]))
		{
			throw new Exception("La nouvelle liste doit avoir un nom!");
		}
		if(empty($_REQUEST["nomNouvelleListe"]))
		{
			throw new Exception("La nouvelle liste doit avoir un nom!");
		}
		// Nétoyage du nom de la nouvelle liste
		$nom = Validation::netoyerString($_REQUEST["nomNouvelleListe"]);

		// Si nom est null, c'est qu'il y a eu un problème avec le netoyage
		if(is_null($nom))
		{
			throw new Exception("Le nom de la nouvelle liste contien un ou plusieurs caractères illégales.");
		}
		$mdl = new ModelConnecte();

		// Création de la todoList par le modèle.
		$mdl->createTodoList($nom);

		// Rediréction vers l'accueil
		$_REQUEST["action"] = "seeLists";
		new ControleurConnecte();
	}

	/*
	 * \brief Permet d'afficher la page de création de tache
	 */
	function wantAddTask()
	{
		$mdl = new ModelConnecte();

		// Si la liste où on veut rajouter la tache n'est pas set ou est vide, on lève une exception
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le numero de liste doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le numero de liste doit être définit");
		}

		// Validation du numérode la liste, renvoi null si la validation a échouée
		$actualList = Validation::validerUnIntSupperieurZero($_REQUEST["list"]) ? $_REQUEST["list"] : null;

		// Si la validation a échouée, on lève une exception
		if(is_null($actualList))
		{
			throw new Exception("Le numero de liste doui être un entier supperieur à 0");
		}

		// Affichage de la vue
		require("vues/addTask.php");
	}

	/*
	 * \brief Permet d'ajouter une tache dans une liste
	 */
	function addTask()
	{
		$mdl = new ModelConnecte();
		
		// Si le nom de la tache est vide ou n'est pas set, on lève une exception
		if(!isset($_REQUEST["nomTache"]))
		{
			throw new Exception("Le nom de la novelle tache est introuvable (?o?)'");
		}
		if(empty($_REQUEST["nomTache"]))
		{
			throw new Exception("Le nom de la nouvelle tache ne doit pas être vide");
		}

		// Si le commentaire de la tache n'est pas set, on lève une exception
		if(!isset($_REQUEST["commentaireTache"]))
		{
			throw new Exception("Le commentaire de la tache est introuvable!");
		}

		// Si le numéro de la liste est vide ou n'est pas set, on lève une exception
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le numero de liste doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le numero de liste doit être définit");
		}

		// Validation des paramètres
		$list = Validation::validerUnIntSupperieurZero($_REQUEST["list"]) ? $_REQUEST["list"] : null;
		$nom = Validation::netoyerString($_REQUEST["nomTache"]);
		$comm = Validation::netoyerString($_REQUEST["commentaireTache"]);

		// Verification des paramètre, si il y en a 1 qui vas pas, on lève une exception
		if(is_null($nom) || is_null($comm) || is_null($list))
		{
			throw new Exception("Le nom, la liste ou le commentaire de la nouvelle tache contiennent des caractèrent illégales!");
		}

		// Création de la tache par le modèle
		$mdl->createTask($nom, $comm, $list);

		// Rediréction vers la liste modifée
		$_REQUEST["action"] = "seeList";
		$_REQUEST["list"] = $list;
		new ControleurConnecte();

	}

	/*
	 * \brief Permet de supprimer une liste
	 */
	function supprimerListe()
	{
		$mdl = new ModelConnecte();
		// Si le numérode liste n'est pas set ou est vide, on lève une exception
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le paramètre list doit contenire une valeur");
		}

		// Si le numéro de liste est <= 0, on lève une exception
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit être un entier strictement superieur à 0");
		}
		// TODO: verifier que c'est bien une liste de l'utilisateur.trice
		$mdl->supprimerListe($_REQUEST["list"]);

		// Rediréction vers l'accueil
		$_REQUEST["action"] = "seeLists";
		new ControleurConnecte();
	}

	/*
	 * Permet d'effacer une tache d'une liste
	 */
	function delTask()
	{
		$mdl = new ModelConnecte();

		// Si la tache est vide, pas set ou <= 0, on lève une exception
		if(!isset($_REQUEST["task"]))
		{
			throw new Exception("Le parametre task doit exister");
		}
		if(empty($_REQUEST["task"]))
		{
			throw new Exception("Le paramètre task doit contenire une valeur");
		}
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["task"]))
		{
			throw new Exception("Le parametre task doit être un entier strictement superieur à 0");
		}

		//TODO: verifier que c'est bien la tache de l'utilisateur.trice

		// Si le numéro de la liste est vide, pas set ou <= 0, on lève une exception
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le paramètre list doit contenire une valeur");
		}
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit être un entier strictement superieur à 0");
		}
		//TODO: verifier que c'est bien la list de l'utilisateur.trice et que c'est bien une tache de la liste

		// Suppression de la tache par le modèle
		$mdl->delTask($_REQUEST["task"]);

		//Rediréction vers l'affichage de la liste modifiée
		$_REQUEST["action"] = "seeList";
		new ControleurConnecte();
	}

	/*
	 * \brief permet de se déconnécter
	 */
	function logout()
	{
		$mdl = new ModelConnecte();
		
		// Destruction de la séssion par le modèle
		$mdl->destroySession();

		// Rediréction vers la page de connection
		new ControleurCommun();
	}

	/*
	 * \brief permet d'afficher la page de modification d'une liste (le nom de la liste)
	 */
	function veuxModifierListe()
	{
		// Si le numéro de la liste n'est pas set, vide ou <= 0, on lève une exception
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le paramètre list doit contenire une valeur");
		}
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit être un entier strictement superieur à 0");
		}

		// Définition des varables dont a besoin la vue
		$listeAModifier = $_REQUEST["list"];

		// Affichage de la vue
		require("vues/editeurDeListe.php");
	}

	/*
	 * \brief Permet de modifier le nom de la liste list
	 */
	function modifyList()
	{
		$mdl = new ModelConnecte();
		
		// Si le numéro de la lsite est vide, pas set ou <=0, on lève une exception
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le paramètre list doit contenire une valeur");
		}
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit être un entier strictement superieur à 0");
		}

		// Si le nouveau non n'est pas set, vide on invalide, on lève une exception
		if(!isset($_REQUEST["nouveauNom"]))
		{
			throw new Exception("Le parametre nouveauNom doit exister");
		}
		if(empty($_REQUEST["nouveauNom"]))
		{
			throw new Exception("Le paramètre nouveauNom doit contenire une valeur");
		}
		$nouveauNom = Validation::netoyerString($_REQUEST["nouveauNom"]);
		if(is_null($nouveauNom))
		{
			throw new Exception("Le nouveau nom contient des caractères illégeaux");
		}

		// Modification du nom de la liste par le modèle
		$mdl->modifierNomListe($_REQUEST["list"], $nouveauNom);

		// Rediréction vers l'accueil
		$_REQUEST["action"] = "seeLists";
		new ControleurConnecte();
	}

	/*
	 * \brief Permet d'afficher la page de modification d'une tache
	 */
	function veuxModifierTache()
	{
		// Si la tache est vide, pas set ou <=0, on lève une exception
		if(!isset($_REQUEST["task"]))
		{
			throw new Exception("Le parametre task doit exister");
		}
		if(empty($_REQUEST["task"]))
		{
			throw new Exception("Le paramètre task doit contenire une valeur");
		}
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["task"]))
		{
			throw new Exception("Le parametre task doit être un entier strictement superieur à 0");
		}

		// Si la liste est vide, pas set ou <=0, on lève une exception
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le paramètre list doit contenire une valeur");
		}
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit être un entier strictement superieur à 0");
		}

		// Définition des varable nécéssaire à la vue
		$tacheAModifier = $_REQUEST["task"];
		$listeAModifier = $_REQUEST["list"];

		// Affichage de la vue
		require("vues/editeurDeTache.php");
	}

	/*
	 * \brief Permet de modifier une tache
	 */
	function editionTache()
	{
		$mdl = new ModelConnecte();

		// Si le numéro de la tache est pas set, vide ou <=0, on lève une exception
		if(!isset($_REQUEST["task"]))
		{
			throw new Exception("Le parametre task doit exister");
		}
		if(empty($_REQUEST["task"]))
		{
			throw new Exception("Le paramètre task doit contenire une valeur");
		}
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["task"]))
		{
			throw new Exception("Le parametre task doit être un entier strictement superieur à 0");
		}

		// Si le numéro de la liste est pas set, vide ou <=0, on lève une exception
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le paramètre list doit contenire une valeur");
		}
		if(!Validation::validerUnIntSupperieurZero($_REQUEST["list"]))
		{
			throw new Exception("Le parametre list doit être un entier strictement superieur à 0");
		}

		// Si le nom est vide ou pas set, on lève une exception
		if(!isset($_REQUEST["nom"]))
		{
			throw new Exception("Le parametre nom doit exister");
		}
		if(empty($_REQUEST["nom"]))
		{
			throw new Exception("Le paramètre nom doit contenire une valeur");
		}

		// Si le commentaire est pas set, on lève une exception
		if(!isset($_REQUEST["commentaire"]))
		{
			throw new Exception("Le parametre commentaire doit exister");
		}

		// Validation des paramètres
		$nom = Validation::netoyerString($_REQUEST["nom"]);
		$comm = Validation::netoyerString($_REQUEST["commentaire"]);

		// Si un des paramètres est invalide, on lève une exception
		if(is_null($nom) || is_null($comm))
		{
			throw new Exception("Le nom ou le commentaire contien des valeurs illégales");
		}

		// Modification de la tache par le modèle
		$mdl->modifierNomCommTache($_REQUEST["task"], $nom, $comm);

		// Définition des variables nécessaire à la vue
		$list = $_REQUEST["list"];

		// Rediréction vers l'affichage le la liste list
		$_REQUEST["action"] = "seeList";
		new ControleurConnecte();
	}
}
