<?php

/* Forms */

foreach ( $languages as $language ) {
	?>
	<div class="col-md text-center">
	<?php
	echo form_open('translator', '', $hidden );
	
	echo form_submit('masterLang', $language ' class="btn btn-secondary btn-block"');
	
	echo form_close();
	?>
	</div>
	<?php
}

?>
