<?php $page_title = 'Panel administracyjny';?>
<div class="row admin">
	<div class="col-sm-6 col-md-3">
		<a href="<?php echo ROOT_URL.'admin/beats';?>">
			<h2><i class="icon-headphones"></i><br>Baza bitów</h2>
		</a>
	</div>
	<div class="col-sm-6 col-md-3">
		<a href="<?php echo ROOT_URL.'admin/songs';?>">
			<h2><i class="icon-music"></i><br>Utwory</h2>
		</a>
	</div>
	<div class="col-sm-6 col-md-3">
		<a href="<?php echo ROOT_URL.'admin/albums';?>">
			<h2><i class="icon-albums"></i><br>Albumy</h2>
		</a>
	</div>
	<div class="col-sm-6 col-md-3">
		<a href="<?php echo ROOT_URL.'admin/artists';?>">
			<h2><i class="icon-artists"></i><br>Artyści</h2>
		</a>
	</div>
</div>

<div class="row admin">
	<div class="col-sm-6">
		<a href="<?php echo ROOT_URL.'admin/articles';?>">
			<h2><i class="icon-projects"></i><br>Artykuły</h2>
		</a>
	</div>
	<div class="col-sm-6">
		<a href="<?php echo ROOT_URL.'admin/statistics';?>">
			<h2><i class="icon-statistics"></i><br>Statystyki i dane witryny</h2>
		</a>
	</div>
</div>
<div class="row admin">
	<div class="col-md-12">
		<h3 class="logout"><a href="<?php echo ROOT_URL.'admin/logout';?>">Wyloguj z trybu administratora</a></h3>
	</div>
</div>