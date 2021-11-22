<html lang="fr">
<head>
	<meta charset="utf8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?=$nomDuSite?></title>
</head>

<body>

<header>
	<?php require('header.php'); ?>
</header>

<main>
	<?php require('menu.php'); ?>
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
				<tr>
					<td><input name="estFait" type="checkbox" value="0"/></td>
					<td>TacheEnDur</td>
					<td>Cette tâche est à retirer des que possible ^^"</td>
				</tr>
				<?php foreach($taches as $tache):?>
					<tr class="<?=$tache->couleur?>">
						<td><input name="estFait" type="checkbox" <?=$tache->estFait ? "" : "checked"?>/></td>
						<td><?=$tache->nom?></td>
						<td><?=$tache->commentaire?></td>
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
