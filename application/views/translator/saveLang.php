<?php

echo form_open('translator', '', $hidden );

echo form_submit('ConfirmSaveLang', 'Confirmar', 'class="btn btn-success btn-block"');
?>
<br />
<table class="table table-stripped">

<?php

echo '<tr>';
echo '<thead class="thead-dark">';
echo '<th class="translator_table_header" width="10%">' . 'Key' . '</td>';
echo '<th class="translator_table_header" width="45%">' . ucwords( $masterLang ) . '</td>';
echo '<th class="translator_table_header width="45%"">' . ucwords( $slaveLang ) . '</td>';
echo '</thead>';
echo '</tr>';

foreach ( $moduleData as $key => $line ) {
	echo '<tr>';
	echo '<td>' . $key . '</td>';
	echo '<td>' . htmlspecialchars( $line['master'] ) . '</td>';
	echo '<td>' . htmlspecialchars( $line['slave'] ) . '</td>';
	echo '</tr>';
}

?>

</table>

<?php

echo form_submit('ConfirmSaveLang', 'Confirmar', 'class="btn btn-success btn-block"');

echo form_close();
	
?>