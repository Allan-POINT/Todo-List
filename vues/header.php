<?php $nomDuSite="SUPER MEGA TODO LIST";?>
<header>
	<nav class="navbar navbar-exand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<a class="navbar-brand" href="?"><?=$nomDuSite?></a>
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link dropdown-toggle" href="#" role="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">Liste</a>
					<ul class="dropdown-menu" aria-labellledby="navbarDropdown">
						<li><a class="dropdown-item" href="?acction=addList">Ajouter une liste</a></li>
						<li><a class="dropdown-item" href="?acction=modifyList">Modifier des listes</a></li>

					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
