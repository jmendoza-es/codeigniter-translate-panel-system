<?php

/* Forms */
die(print_r($langdirs));
foreach ( $langdirs as $langdir ) {
	
	echo form_open('translator');
	
	echo form_submit('langDir', $langdir);
	
	echo form_close();
	
}

?>
