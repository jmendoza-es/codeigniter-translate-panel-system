<?php

/* Forms */

for($i = 0; $i < count($languages); $i++) {
	?>
	<div class="col-md text-center">
	<?php
	echo form_open('translator', 'id="'.$languages[$i].'"', $hidden );
	
	echo form_button('selector', ($i == 0) ? ucfirst($languages[$i]) . ' (Master)': ucfirst($languages[$i]), ' value="'.$languages[$i].'" onclick="document.getElementById(\''.$languages[$i].'\').submit()" class="btn btn-secondary btn-block"');
	?>
	
	<input type="hidden" name="slaveLang" value="<?=$languages[$i]?>" />
	<?php
	echo form_close();
	?>
	</div>
	<?php
	
}

?>

