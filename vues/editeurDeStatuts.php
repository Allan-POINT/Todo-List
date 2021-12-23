<head>
	<meta charset="utf8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Visualisation d'une liste</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="styles/chechBoxLineThrought.css" rel="stylesheet"/>
</head>

<body>

<header>
	<?php require('header.php'); ?>
</header>

<main>
	<form method="post" action="?action=setTacheFait">
		<table>
			<thead>
				<tr>
					<th>Fait</th>
					<th>Tâche</th>
					<th>Commentaire</th>
					<th>Supprimer</th>
					<th>Modifer</th>
				</tr>
			</thead>

			<tbody>
				<?php if(isset($taches)) : ?>
					<?php foreach($taches as $tache):?>
						<tr>
							<td><input name="estFait[]" type="checkbox" value="<?=$tache->getID()?>" <?=$tache->estFait() ? "checked" : ""?>/></td>
							<td><?=$tache->getNom()?></td>
							<td><?=$tache->getCommentaire()?></td>
							<td><a href="?action=delTask&task=<?=$tache->getID()?>&list=<?=$actualList?>">À remplacer par une image de poubelle (^u^)"</a></td>
							<td><a href="?action=veuxModifierTache&task=<?=$tache->getID()?>&list=<?=$actualList?>">À remplacer par une image de stylo (^u^")</a></td>
							<td><input name="exist[]" type="hidden" value="<?=$tache->getID()?>"?></td>
						</tr>
					<?php endforeach;?>
				<?php endif;?>
				<tr>
					<td></td>
					<td><a href="?action=wantAddTask&list=<?=$actualList?>">+</td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<input value="Décocher toutes les cases" type="reset"/>
		<input value="Sauvegarder l'étât!" type="submit"/>
	</form>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
