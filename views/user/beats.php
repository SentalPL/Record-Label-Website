<?php $page_title = 'Podkłady muzyczne'; ?>
<div class="row user beats">
	<div class="col-md-12">
		<h2>Przeglądaj bity:</h2>
		<p>Baza bitów to nasza skarbnica oryginalnych podkładów, a więc takich do których
			  mamy pełne prawo do użytku, na wyłączność. Wiele bitów wymaga arażancji, niektóre
			  z nich są jedynie próbką. Jeśli więc chcesz wykorzystać podkład, ale nie odpowiada on do końca
			  Twojej koncepcji, prawdopodobnie jest możliwa jego aranżacja. W takim przypadku skontaktuj się
			  z Viperem lub bezpośrednio z producentem, jeśli masz taką możliwość.</p>
		<p>Możesz także oceniać bity, aby najlepsze z nich były najbardziej widoczne.<br>
			<b>Jeśli wykorzystałeś bit, poinformuj o tym Vipera. Bit zostanie usunięty z bazy.</p>
		<h3>Szukaj:</h3>
		<form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
			<input type="text" name="phrase"><br>
			<input type="submit" class="btn btn-primary" value="Szukaj"><br>
			<input type="submit" name="newest" class="btn btn-primary" value="Wyświetl najnowsze"><br>
		</form>
	</div>
</div>
<div class="row user beats">
	<?php
		if (isset ($_SESSION['p_info'])){
			echo '<p class="p_info">Ocena została przesłana.</p>';
			unset ($_SESSION['p_info']);
		}
		$beats = $viewmodel[0];
		if ($beats == FALSE){
			echo 'Brak wyników.';
		}else{
			foreach ($beats as $beat){
				if (isset($beat['my_rate'])){
					$rate = $beat['my_rate'];
				}
				echo '<div class="col-md-12 beat">
							<h4>'.$beat['name'].'<span>prod. '.$beat['producer'].'</span></h4>
							Punkty: '.$beat['allrate'].'<br>
						<audio class="audio" src="'.ROOT_URL.'assets/beats/'.$beat['name'].'  prod. '.$beat['producer'].'.mp3" controls></audio>
						<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" method="post">
							<input type="hidden" name="id" value="'.$beat['id'].'">
							<select name="rate">
								<option value="0">Nie oceniono</option>
								<option value="1"';if (@$rate == 1){echo ' selected';}echo '>Może być</option>
								<option value="2"';if (@$rate == 2){echo ' selected';}echo '>Fajny</option>
								<option value="3"';if (@$rate == 3){echo ' selected';}echo '>Sztos</option>
							</select>
							<input type="submit" class="btn btn-primary" value="Oceń"><br>
						</form>
						</div>';
				unset ($rate);	
			}
		}
	?>
	
</div>