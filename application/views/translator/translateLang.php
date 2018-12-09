<div class="col-md-12">
<?php

echo form_open('translator', '', $hidden );

?>
<table class="table table-striped">
		<thead class="thead">
			<tr>
				<th colspan="2" class="translator_table_header"><?php echo $this->lang->line('translate_actions'); ?><?php  if($slaveLang == $masterLang) { ?> (<b>Master</b>)<?php } ?></th>
			</tr>
		</thead>
		<tr>
		<td width="40%">
			<?= form_submit('SaveLang', $this->lang->line('translate_save'), 'class="btn btn-secondary col-md-4"'); ?>
			<?php  if($slaveLang == $masterLang) { ?>
			<a href="javascript:void(0)" id="add_new" class=" col-md-6 btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('translate_add_entry'); ?> </a>
			<?php } ?>
		</td>
		<td width="60%">
		<div class="row">
			<label for="filter" class="col-md-4 col-form-label text-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('translate_search'); ?></label>
			<div class="col-md-8"><input class="form-control float-right" id="filter" type="text" placeholder="<?php echo $this->lang->line('translate_search_label'); ?>" /></div>
		</div>
		</td>
	</tr>
</table>
<table class="table table-striped table-hover sortable">

<?php

echo '<thead class="thead-dark">';
echo '<tr>';
echo '<th class="translator_table_header" width="25%">' . 'Key' . '</td>';
echo '<th class="translator_table_header" width="33%">' . ucwords( $masterLang ) . '</td>';
echo '<th class="translator_table_header" width="34%"">' . ucwords( $slaveLang ) . '</td>';
echo '</tr>';
echo '</thead>';
$count = 1;
foreach ( $moduleData as $key => $line ) {
	$linea = str_replace('\'','',$line[ 'master' ]);
	$linea = str_replace('"','', $linea);
	echo '<tr id="row'.$count.'" valign="center" class="searchable">';
	echo '<td class="markable"><div class="actions"><i class="fa fa-times"></i></div><span class="valor" data-parent="'.$count.'">' . $key . '</span></td>';
	echo '<td class="markable">' . $linea . '</td>';
	
	if ( mb_strlen( $line[ 'slave' ] ) > $textarea_line_break ) {
		echo '<td>' . form_textarea( array( 'name' => $postUniquifier . $key,
											'value' => str_replace("'","",$line[ 'slave' ]),
											'rows' => $textarea_rows,
											'class' => "form-control"
											)
									);
	} else {
		echo '<td>' . form_input( $postUniquifier . $key, str_replace("'","",$line[ 'slave' ]) , 'class="form-control"');
	}

	if ( strlen( $line[ 'error' ] ) > 0 ) {
		echo '<br /><span class="translator_error">' . $line[ 'error' ] . '</span>';
	}

	if ( strlen( $line[ 'note' ] ) > 0 ) {
		echo '<br /><span class="translator_note">' . $line[ 'note' ] . '</span>';
	}

	echo '</td>';
	echo '</tr>';

	$count++;
}

?>

</table>

<?php

echo form_submit('SaveLang', $this->lang->line('translate_save') ,'class="btn btn-block btn-secondary"');
		
echo form_close();
	
?>
<br />
<br />
<br /></div>