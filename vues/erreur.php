<head>
	<meta charset="utf8"/>
	<title>ERREUR :/</title>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>Erreur</th>
				<th>Commentaire</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($erreurs as $erreur => $commentaire) :?>
				<tr>
					<td><?=$erreur?></td>
					<td><?=$commentaire?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</body>
