<head>
	<meta charset="utf8"/>
	<title>ERREUR :/</title>
</head>
<body>
	<?php require("vues/header.php");?>
	<table>
		<thead>
			<tr>
				<th>Erreur</th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($erreurs)) : ?>
				<?php foreach($erreurs as $erreur) :?>
					<tr>
						<td><?=$erreur?></td>
					</tr>
				<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</body>
