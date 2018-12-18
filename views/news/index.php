<?php $page_title = 'Aktualności | Masarnia Records';
		  $page_description = 'Zobacz najnowsze informacje zza kulis Masarni Records i dowiedz się więcej o naszych działaniach.
									  Dowiedz się tego, czego nie dowiesz się z utworów czy Facebooka!';
		  $page_keywords = 'aktualności, Masarnia Records, news, artykuły, najnowsze informacje, posty, kulisy, blog, działania';
?>
<div class="row news">
	<div class="col-sm-12 col-md-9">
		<!--- This is none-self-made Slider working on Javasrcipt
				Treat it like a demo version with unknown bugs -->
		<div class="w3-content">
		<?php
			$news = $viewmodel[0];
			
			foreach($news as $article){
				echo '<div class="slide">
							<a href="'.ROOT_URL.'news/view/'.$article['id'].'">
							<img src="assets/articles/'.$article['id'].'.png"
									class="img-fluid" alt="Responsive image">
							</a>
							<aside>
								<h2>'.$article['title'].'</h2>
								<p>'.$article['description'].'</p>
								<a href="'.ROOT_URL.'news/view/'.$article['id'].'">Zobacz więcej</a>
							</aside>
						</div>';
			}
		?>
		<script>
			var myIndex = 0;
			carousel();
			
			function carousel() {
				var i;
				var x = document.getElementsByClassName("slide");
				for (i = 0; i < x.length; i++) {
				   x[i].style.display = "none";  
				}
				myIndex++;
				if (myIndex > x.length) {myIndex = 1}    
				x[myIndex-1].style.display = "block";  
				setTimeout(carousel, 4000);
			}
		</script>
		<!-- End of slider -->
		</div>		
	</div>
	<div class="col-sm-12 col-md-3 newSongs">
		<h2>Najnowsze utwory</h2>
		<table>
		<?php
			$newSongs = $viewmodel[1];
			if ($newSongs){
				foreach ($newSongs as $song){
					echo '<tr>									
								<td><a href="'.ROOT_URL.'music/song/'.$song['id'].'">'.$song['title'].'</a></td>
								<td><a href="'.ROOT_URL.'music/song/'.$song['id'].'">'.$song['id_artist'];if ($song['featuring']){echo ' (gośc. '.$song['featuring'].')';}echo '</a></td>
							</tr>';
				}
			}else{
				echo '<p>Brak dostępnych utworów.</p>';
			}
		?>
		</table>
	</div>
</div>
	<?php 
	// Wyświetlenie losowego utworu na YouTube
	
	$randomSong = $viewmodel[2];
	if ($randomSong){	
		echo '<div class="row news">
					<div class="col-sm-12 randomSong">
						<h4>'.$randomSong[0]['id_artist'].' - '.$randomSong[0]['title'].'</h4>
						<iframe allowfullscreen src="'.$randomSong[0]['youtube'].'">
						</iframe>
						<form action="'.ROOT_URL.'music/song/'.$randomSong[0]['id'].'" method="post">
							<input type="hidden" name="file" value="'.$randomSong[0]['id_artist'].' - '.
								$randomSong[0]['title']; if ($randomSong[0]['featuring']){ echo ' (ft. '.$randomSong[0]['featuring'].')';} echo '.mp3">';	
		echo '			<input type="submit" class="btn btn-primary" 
							name="download" value="Pobierz utwór">
						</form>
					</div>
				</div>';
	}
	?>