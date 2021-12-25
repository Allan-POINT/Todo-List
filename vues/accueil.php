<head>
	<meta charset="utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />	
	<title>Accueil</title>
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
					<th>mofifier</th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($todoLists)) : ?>
					<?php foreach($todoLists as $todolist) :?>
						<tr>
							<td><a href="?action=seeList&list=<?=$todolist->getID()?>"><?=$todolist->getNom()?></a></td>
							<td><?=$todolist->getCreateur()?></td>
							<td><?=$todolist->getDateCreation()?></td>
							<td><a href="?action=supprimerListe&list=<?=$todolist->getID()?>"><img alt="Supprimer" src="ressources/imgs/trash.png"/></a></td>
							<td><a href="?action=veuxModifierListe&list=<?=$todolist->getID()?>"><img alt="Modifier" src="ressources/imgs/pen.png"/></a></td>
						</tr>
					<?php endforeach;?>
				<?php endif;?>
				<tr>
					<td><a href="?action=wantAddList">+</a></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		<table>
		<?php if(isset($page) && isset($nbElements) && isset($maxPage)) :?>
			<?php if($maxPage > 1) :?>
				<a href="?action=seeLists&page=<?=$page==1 ?1:$page-1 ?>&nbElements=<?=$nbElements?>"><?=$page==1?1:"&larr;"?></a>
				<a href="?action=seeLists&page=<?=$page==$maxPage ?$maxPage:$page+1 ?>&nbElements=<?=$nbElements?>"><?=$page>=$maxPage?$maxPage:"&rarr;"?></a>
			<?php endif;?>
		<?php endif;?>
	</main>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
