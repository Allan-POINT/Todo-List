<html lang="fr">
<head>
	<meta charset="utf8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?=$nomDuSite?></title>
</head>

<body>

<header>
	<?php require('header.php');?>
</header>

<main>
	<?php require('menu.php'); ?>
	<form method="post">
		<table>
			<thead>
				<tr>
					<th>Tâche</th>
					<th>Commentaire</th>
					<th>Couleur</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach($taches as $tache):?>
					<tr class="<?=$tache->couleur?>">
						<td><input name="nom" type="text" value="<?=$tache->nom?>"/></td>
						<td><input name="commentaire" type="text" value="<?=$tache->commentaire?>"/></td>
						<td><input name="couleur" type="color" value="<?=$tache->couleur?>"/></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<input name="Sauvegarder l'étât!" type="submit"/>
		<input name="Décocher toutes les cases" type="reset"/>
	</form>
</main>

</body>
</html>
