<?php
require_once("config/Validation.php");
require_once("controleur/ControleurCommun.php");
class ControleurConnecte {
		
	function __construct() {
		global $rep,$vues; // nécessaire pour utiliser variables globales

		//debut
		//on initialise un tableau d'erreur

		$dVueEreur = array ();
		try{
			$action=Validation::netoyerString($_REQUEST['action']);
			switch($action) {

				//pas d'action, on r�initialise 1er appel
				case NULL:
					echo "Normal";
					//$this->Reinit();
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
					echo "Default";
					/*
					$dVueEreur[] =	"Erreur d'appel php";
					require ($rep.$vues['vuephp1']);
					 */
					break;
			}
		}catch(PDOException $e)
		{
			//si erreur BD, pas le cas ici
			echo "Erreur PDO";
			$erreurs[] =$e->getMessage();
			require ("vues/erreur.php");
		}
		catch (Exception $e2)
		{
			$erreurs[] =$e2->getMessage();
			require ("vues/erreur.php");
		}
		// exit(0);
	}

	function Reinit() {
		global $rep,$vues; // nécessaire pour utiliser variables globales
		require ($rep.$vues['connection']);
	}

	function seeLists()
	{
		if(!isset($_REQUEST["page"]) || empty($_REQUEST["page"]))
		{
			$page = 1;
		}
		else
		{
			$page = Validation::validerUnIntSupperieurZero($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
		}

		if(!isset($_GET["nbElements"]) || empty($_GET["nbElements"]))
		{
			$nbElements = 10;
		}
		else
		{
			$nbElements = Validation::validerUnIntSupperieurZero($_GET["nbElements"]) ? $_GET["nbElements"] : 10;
		}

		$mdl = new ModelConnecte();
		$todoLists = $mdl->getLists(Validation::netoyerString($_SESSION["login"]), $page, $nbElements);
		$maxPage = $mdl->getMaxPageListes(Validation::netoyerString($_SESSION["login"]), $nbElements);
		require("vues/accueil.php");
	}

	function seeList()
	{
		if(!isset($_REQUEST["list"]) || empty($_REQUEST["list"]))
		{
			throw new Exception("Aucune liste n'est demendée");
		}

		if(!Validation::validerUnIntSupperieurZero($_REQUEST["list"]))
		{
			throw new Exception("Valeur illégale de la liste requétée");
		}
		if(!isset($_REQUEST["page"]) || empty($_REQUEST["page"]))
		{
			$page = 1;
		}
		else
		{
			$page = Validation::validerUnIntSupperieurZero($_REQUEST["page"]) ? $_REQUEST["page"] : 1;
		}

		if(!isset($_GET["nbElements"]) || empty($_GET["nbElements"]))
		{
			$nbElements = 10;
		}
		else
		{
			$nbElements = Validation::validerUnIntSupperieurZero($_GET["nbElements"]) ? $_GET["nbElements"] : 10;
		}
		$mdl = new ModelConnecte();
		$taches = $mdl->getTaches($_REQUEST["list"], $page, $nbElements);
		$actualList = $_REQUEST["list"];
		$nomListe = $mdl->getNomListe($actualList);
		$maxPage = $mdl->getMaxPageTaches($actualList, $nbElements);
		require("vues/editeurDeStatuts.php");
	}

	function editDone()
	{
		if(isset($_REQUEST["estFait"]))
		{
			if(!is_array($_REQUEST["estFait"]))
			{
				throw new Exception("La liste des taches faites doit être un tableau.");
			}
		}
		if(!isset($_REQUEST["exist"]))
		{
			throw new Exception("Aucune tâche n'est définit");
		}
		if(!is_array($_REQUEST["exist"]))
		{
			throw new Exception("La liste des taches doit être un tableau.");
		}
		$mdl = new ModelConnecte();
		$mdl->setDoneTaches();
		new ControleurConnecte();
	}

	function wantAddList()
	{
		require("vues/ajouterListe.php");
	}

	function addList()
	{
		if(!isset($_REQUEST["nomNouvelleListe"]))
		{
			throw new Exception("La nouvelle liste doit avoir un nom!");
		}
		if(empty($_REQUEST["nomNouvelleListe"]))
		{
			throw new Exception("La nouvelle liste doit avoir un nom!");
		}
		$nom = Validation::netoyerString($_REQUEST["nomNouvelleListe"]);
		if(is_null($nom))
		{
			throw new Exception("Le nom de la nouvelle liste contien un ou plusieurs caractères illégales.");
		}
		$mdl = new ModelConnecte();
		$mdl->createTodoList($nom);
		$_REQUEST["action"] = "seeLists";
		new ControleurConnecte();
	}

	function wantAddTask()
	{
		$mdl = new ModelConnecte();
		if(!$mdl->estConnecte())
		{
			throw new Exception("Permission non suffisantes pour effectuer cette action!");
		}
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le numero de liste doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le numero de liste doit être définit");
		}
		$actualList = Validation::validerUnIntSupperieurZero($_REQUEST["list"]) ? $_REQUEST["list"] : null;
		if(is_null($actualList))
		{
			throw new Exception("Le numero de liste doui être un entier supperieur à 0");
		}
		require("vues/addTask.php");
	}
	function addTask()
	{
		$mdl = new ModelConnecte();
		if(!$mdl->estConnecte())
		{
			throw new Exception("Permission non suffisantes pour effectuer cette action!");
		}

		if(!isset($_REQUEST["nomTache"]))
		{
			throw new Exception("Le nom de la novelle tache est introuvable (?o?)'");
		}
		if(empty($_REQUEST["nomTache"]))
		{
			throw new Exception("Le nom de la nouvelle tache ne doit pas être vide");
		}
		if(!isset($_REQUEST["commentaireTache"]))
		{
			throw new Exception("Le commentaire de la tache est introuvable!");
		}
		if(!isset($_REQUEST["list"]))
		{
			throw new Exception("Le numero de liste doit exister");
		}
		if(empty($_REQUEST["list"]))
		{
			throw new Exception("Le numero de liste doit être définit");
		}
		$list = Validation::validerUnIntSupperieurZero($_REQUEST["list"]) ? $_REQUEST["list"] : null;
		$nom = Validation::netoyerString($_REQUEST["nomTache"]);
		$comm = Validation::netoyerString($_REQUEST["commentaireTache"]);
		if(is_null($nom) || is_null($comm) || is_null($list))
		{
			throw new Exception("Le nom, la liste ou le commentaire de la nouvelle tache contiennent des caractèrent illégales!");
		}
		$mdl->createTask($nom, $comm, $list);
		$_REQUEST["action"] = "seeList";
		$_REQUEST["list"] = $list;
		new ControleurConnecte();

	}

	function supprimerListe()
	{
		$mdl = new ModelConnecte();
		if(!$mdl->estConnecte())
		{
			throw new Exception("Permission non suffisantes pour effectuer cette action!");
		}
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
		$mdl->supprimerListe($_REQUEST["list"]);
		$_REQUEST["action"] = "seeLists";
		new ControleurConnecte();
	}

	function delTask()
	{
		$mdl = new ModelConnecte();
		if(!$mdl->estConnecte())
		{
			throw new Exception("Permission non suffisantes pour effectuer cette action!");
		}
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
		$mdl->delTask($_REQUEST["task"]);
		$_REQUEST["action"] = "seeList";
		new ControleurConnecte();
	}
	function logout()
	{
		$mdl = new ModelConnecte();
		$mdl->destroySession();
		new ControleurCommun();
	}
	function veuxModifierListe()
	{
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
		$listeAModifier = $_REQUEST["list"];
		require("vues/editeurDeListe.php");
	}
	function modifyList()
	{
		$mdl = new ModelConnecte();
		if(!$mdl->estConnecte())
		{
			throw new Exception("Permission non suffisantes pour effectuer cette action!");
		}
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
		$mdl->modifierNomListe($_REQUEST["list"], $nouveauNom);
		$_REQUEST["action"] = "seeLists";
		new ControleurConnecte();
	}
	function veuxModifierTache()
	{
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
		$tacheAModifier = $_REQUEST["task"];
		$listeAModifier = $_REQUEST["list"];
		require("vues/editeurDeTache.php");
	}
	function editionTache()
	{
		$mdl = new ModelConnecte();
		if(!$mdl->estConnecte())
		{
			throw new Exception("Permission non suffisantes pour effectuer cette action!");
		}
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
		if(!isset($_REQUEST["nom"]))
		{
			throw new Exception("Le parametre nom doit exister");
		}
		if(empty($_REQUEST["nom"]))
		{
			throw new Exception("Le paramètre nom doit contenire une valeur");
		}
		if(!isset($_REQUEST["commentaire"]))
		{
			throw new Exception("Le parametre commentaire doit exister");
		}
		$nom = Validation::netoyerString($_REQUEST["nom"]);
		$comm = Validation::netoyerString($_REQUEST["commentaire"]);
		if(is_null($nom) || is_null($comm))
		{
			throw new Exception("Le nom ou le commentaire contien des valeurs illégales");
		}
		$mdl->modifierNomCommTache($_REQUEST["task"], $nom, $comm);
		$list = $_REQUEST["list"];
		$_REQUEST["action"] = "seeList";
		new ControleurConnecte();
	}
}
