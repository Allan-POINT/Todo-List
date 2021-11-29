<head>
	<meta charset="utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />	
	<title><?=$nomDuSite?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
	<?php require("header.php"); ?>

	<main>
		<table>
			<thead>
				<tr>
					<th>todo list</th>
					<th>créateur</th>
					<th>date de création</th>
					<th>supprimer</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($todoLists as $todolist) :?>
					<tr>
						<td><a href="?seeList=<?=$todolist->getNom()?>"><?=$todolist->getNom()?></a></td>
						<td><?=$todolist->getCreateur()?></td>
						<td><?=$todolist->getDateCreation()?></td>
						<td><a href="?supprimerListe=<?=$todolist->getID()?>">A remplacer par un image de poubelle (^u^)'</a></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		<table>
	</main>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
