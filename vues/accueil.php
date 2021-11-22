<?php
	require("header.php");
?>

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
					<td><?=$todolist->getNom()?></td>
					<td><?=$todolist->getCreateur()?></td>
					<td><?=$todolist->getDateCreation()?></td>
					<td><a href="?supprimerListe=<?=$todolist->getID()?>">A remplacer par un image de poubelle (^u^)'</a></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	<table>
</main>
