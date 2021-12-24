<head>
	<meta charset="utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="styles/connection.css">
	<title>Se connecter</title>
</head>
<body>
	<main>
		<div>
			<h1>Se Connecter</h1>
			<form method="POST" action="?action=connection">
				<table>
					<tr>
						<td><label for="ps">Pseudo</label></td>
						<td><input type="text" id="ps" name="pseudonyme"></td>
					</tr>
					<tr>
						<td><label for="pw">Mot de passe</label></td>
						<td><input type="password" id="pw" name="motDePasse"><br></td>
					</tr>
					<tr>
						<td><label for="cls"></label><input type="reset" id="cls" value="Effacer"></td>
						<td><label for="submit"></label><input type="submit" id="submit" name="veutSeConnecter" value="Valider"></td>
					</tr>
				</table>
			</form>
			<a href="?action=veuxSInscrire">S'inscrire</a>
		</div>
	</main>
</body>
</html>
