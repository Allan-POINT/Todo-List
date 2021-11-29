<head>
	<meta charset="utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?=$nomDuSite?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="styles/ajouterListe.css">

</head>


<body class="container-fluid">
	<?php require("header.php"); ?>
	<div class=container>
		<div class="row">
			<div class="col"></div>
			<main class="col">
				<form method="POST" action="?action=addList">
					<label for="nom">Nom </label><input name="nomNouvelleListe" type="input" id="nom"/>
					<label for="ok"/>
					<input name="valider" type="submit" value="Créer la liste" id="ok"/>
				</form>
			</main>
			<div class="col"></div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
