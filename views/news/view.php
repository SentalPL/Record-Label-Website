<div class="row news">
	<div class="col-sm-12 col-md-8 view">
	<?php
		$article = $viewmodel[0];
		
		$page_title = $article[0]['title'];
		$page_description = $article[0]['description'];
		$page_keywords = $article[0]['tags'];
		
		echo '<h2>'.$article[0]['title'].'</h2>
				<img src="'.ROOT_URL.'assets/articles/'.$article[0]['id'].'.png"
								class="img-fluid" alt="Responsive image">
				<p class="description">'.$article[0]['description'].'</p>
				<p class="content">'.$article[0]['content'].'</p>
				<small>Liczba odsłon: '.$article[0]['views'].'</small><br>
				<small>'.$article[0]['date_creation'].'</small>';
	?>
	</div>
	<div class="col-sm-12 col-md-4 similar">
		<h3>Zobacz podobne artykuły:</h3>
		<?php
			$similar = $viewmodel[1];
			if (!empty($similar)){
				$i = 0;
				foreach ($similar as $article){
					if ($i == 10){
						break;
					}
					echo '<a href="'.ROOT_URL.'news/view/'.$article['id'].'">'.$article['title'].'</a><br>';
					$i++;
				}
			}else{
				echo 'Brak podobnych artykułów.';
			}
		?>
	</div>
</div>