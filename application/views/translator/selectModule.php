<?php

/* Forms */

foreach ( $masterModules as $masterModule ) {
	?>
	<div class="col-md text-center">
	<?php
	echo form_open('translator', '', $hidden );
	
	echo form_submit('langModule', $masterModule, ' class="btn btn-secondary btn-block"');
	
	if ( ! in_array( $masterModule, $slaveModules ) ) {
		echo $slaveLang . " module not found";
	}
	
	echo form_close();
	?>
	</div>
	<?php
	
}

?>
