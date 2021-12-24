<head>
	<meta charset="utf8"/>
	<title>ERREUR :/</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
