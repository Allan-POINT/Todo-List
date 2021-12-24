<head>
	<meta charset="utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />	
	<title>Inscription</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
	<?php require("header.php"); ?>
	<main>
		<form method="POST" action="?action=signin">
			<label for="pseudonyme">Pseudonyme</label>
			<input name="name" type="text" placeholder="exemple: Machaonix" id="pseudonyme"/><br>
			<label for="mdp1">Entrez votre mot de passe</label>
			<input name="mdp1" id="mdp1" type="password" placeholder="••••••••"/><br>
			<label for="mdp2">Saisir à nouveau votre mot de passe</label>
			<input name="mdp2" id="mdp2" type="password" placeholder="••••••••"/><br>
			<input value="Éffacer tout" type="reset"/>
			<input value="Creer le compte" type="submit"/>
		</form>
	</main>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
