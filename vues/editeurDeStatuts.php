<head>
	<meta charset="utf8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?=$nomDuSite?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

<header>
	<?php require('header.php'); ?>
</header>

<main>
	<form method="post">
		<table>
			<thead>
				<tr>
					<th>Fait</th>
					<th>Tâche</th>
					<th>Commentaire</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach($taches as $tache):?>
					<tr>
						<td><input name="estFait" type="checkbox" <?=$tache->estFait() ? "checked" : ""?>/></td>
						<td><?=$tache->getNom()?></td>
						<td><?=$tache->getCommentaire()?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<input name="Sauvegarder l'étât!" type="submit"/>
		<input name="Décocher toutes les cases" type="reset"/>
	</form>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
