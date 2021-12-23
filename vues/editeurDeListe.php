<head>
	<meta charset="utf8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Éditer une liste</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

<header>
	<?php require('header.php');?>
</header>

<main>
	<form method="post" action="?action=modifyList&list=<?=isset($listeAModifier) ? $listeAModifier : "-1"?>">
		<table>
			<thead>
				<tr>
					<th>Tâche</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><input name="nouveauNom" type="text" placeholder="exemple: Liste des séries à voir"/></td>
				</tr>
			</tbody>
		</table>
		<input name="Éffacer" type="reset"/>
		<input name="Renomer la TODO-List" type="submit"/>
	</form>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
