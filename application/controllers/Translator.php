<?php
/**
 * CodeIgniter Translator
 *
 * An open source application development framework for PHP 5.6 or newer
 *
 * Runs on CodeIgniter 3
 *
 * @package		CodeIgniter Translator
 * @author		Javier Mendoza
 * @version		1.0
 * @copyright	
 * @license		GPL
 * @link		https://www.jmendoza.es
 * @since		
 * @filesource  http://www.mrkirkland.com/CodeIgniter-Language-File-Translator
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Translator Class
 *
 * This class enables the creation of translations through a web interface
 *
 * @package		CodeIgniter Translator
 * @subpackage	
 * @category	
 * @author		
 * @link		
 

*/

class Translator extends CI_Controller {
	
	function __construct()
	{ 
		parent::__construct();
	
		$this->load->helper(array('form', 'url', 'file' ));
		
		$this->langDirs = array( BASEPATH . 'language', APPPATH . 'language' );

		$this->data[ 'postUniquifier' ] = $this->postUniquifier;
	
		$this->_configure();
	}

	/*------------- Start Configuration -------------*/

	/**
	 * Working language directory - defaults to normal CI application language path
	 */
	var $langDir;

	/**
	 * Master language (directory name)
	 */
	var $masterLang = 'spanish';

	/*------------- End Configuration ---------------*/

	/**
	 * Array of directories holding language files
	 */
	var $langDirs;

	/**
	 * Slave language (directory name)
	 */
	var $slaveLang;

	/**
	 * Language module to be translated
	 */
	var $langModule;

	/**
	 * Path to master language module
	 */
	var $masterModulePath;

	/**
	 * Path to slave language module
	 */
	var $slaveModulePath;
	
	/**
	 * Prefix added to language element identifiers to avoid namespace clashes in $_POST
	 */
	var $postUniquifier = 'ci_language_';
	
	/**
	 * Generic array for hidden form fields 
	 */
	var $hidden;

	/**
	 * Validation flag
	 */
	var $validated = TRUE;

	/**
	 * Configuration fields - defined in constructor or loaded from $_POST
	 */
	var $config_fields = array( 'langDir', 'masterLang', 'slaveLang', 'langModule' );

	/**
	 * Global holding translation data
	 */
	var $translations = array();

	/**
	 * Flag to backup translation files before saving new versions
	 */
	var $backupFlag = TRUE;
	
	/**
	 * Generic array for passing data to views
	 */
	var $data = array();

	/**
	 * Maximum length of form text input element. Text longer than this is entered in a textarea.
	 */
	var $textarea_line_break = 40;

	/**
	 * Number rows in textara input elements
	 */
	var $textarea_rows = 3;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	 
	

	
	/**
	 * Class handler
	 *
	 * @return void
	 */
	function index() {

		if ( $this->_configured() ) {

			$this->masterModulePath = $this->langDir . '/' . $this->masterLang . '/' . $this->langModule;
			$this->slaveModulePath = $this->langDir . '/' . $this->slaveLang . '/' . $this->langModule;

			$this->_load_master_module_data();

			if ( $this->input->post('SaveLang') || $this->input->post('ConfirmSaveLang') ) {
				$this->_load_post_data();
			} else {
				$this->_load_slave_module_data();
			}
			
			$this->_validate_translations();

			$this->data['moduleData' ] = $this->translations;
			$this->data['textarea_line_break'] = $this->textarea_line_break;
			$this->data['textarea_rows'] = $this->textarea_rows;


			if ( $this->validated && $this->input->post('ConfirmSaveLang') ) {
				if ( $this->_translation_saved( $result ) ) {
					$this->data['saved_data'] = $result;
					$this->data['page_content'] = 'translator/confirmLangSaved';
					$this->data['page_title'] = 'Cambios guardados';
				} else {
					$this->data['page_content'] = 'translator/saveFailed';
					$this->data['page_title'] = 'Se encontró un problema';
				}
			}
			elseif ( $this->validated &&  $this->input->post('SaveLang') ) {
				$this->data['page_content'] = 'translator/saveLang';
				$this->data['page_title'] = '¿Confirmas los cambios?';
			}
			else {
				$this->data['page_content'] = 'translator/translateLang';
				$this->data['page_title'] = 'Traduciendo  ('.$this->slaveLang.'): ' . $this->langModule;
			}

		}
		
		$this->load->view('translator/container', $this->data);
		
	}
	
	
	/**
	 * Attempt to set paths, languages and language module
	 *
	 * Use hard-coded values if they exist otherwise attempt to extract from $_POST
	 * Add all values to 'hidden' array for use in forms.
	 *
	 * @return void
	 */
	function _configure() {

		$this->langDir = APPPATH . 'language';

		$this->data[ 'config_fields' ] = $this->config_fields;
		foreach ( $this->config_fields as $field ) {
			if ( $this->$field ) {
			} elseif ( mb_strlen( $this->input->post( $field ) ) > 0 ) {
				$this->$field = $this->input->post( $field );
				$this->data[ 'hidden' ][ $field ] = $this->$field;
			}
			$this->data[ $field ] = $this->data[ 'hidden' ][ $field ] = $this->$field;
		}

	}

	
	/**
	 * Check configuration of paths, languages and language module
	 *
	 * Use hard-coded values if the exist otherwise attempt to extract from $_POST
	 * Add all values to 'hidden' array for use in forms.
	 *
	 * @return boolean
	 */
	function _configured() {
	
		if ( $this->langDir && $this->masterLang && $this->slaveLang && $this->langModule ) {
			return TRUE;
		}
	
		if ( ! $this->langDir ) {
			$this->data['langdirs'] = $this->langDirs;
			$this->data['page_content'] = 'translator/selectLangDirectory';
			$this->data['page_title'] = "Selecciona directorio de idioma";
		}		
		elseif ( ! $this->masterLang ) {
			$this->data['languages'] = $this->ListLanguages();
			$this->data['page_content'] = 'translator/selectMasterLang';
			$this->data['page_title'] = "Selecciona idioma principal";
		}		
		elseif ( ! $this->input->post('slaveLang') ) {
			// $this->data['languages'] = $this->_list_languages( $this->masterLang );
			$this->data['languages'] = $this->_list_languages();
			$this->data['page_content'] = 'translator/selectSlaveLang';
			$this->data['page_title'] = "Selecciona idioma";
		}		
		elseif ( ! $this->langModule ) {
			$this->data['masterModules'] = $this->_list_modules( $this->masterLang );
			$this->data['slaveModules'] = $this->_list_modules( $this->slaveLang );
			$this->data['page_content'] = 'translator/selectModule';
			$this->data['page_title'] = "Selecciona módulo de traducción (" . $this->slaveLang .")" ;
		}		
		
		return FALSE;
	
	}
	
	
	/**
	 * Load master language
	 *
	 * @return void
	 */
	function _load_master_module_data() {

		$masterModule = $this->_load_module( $this->masterModulePath );
		$masterModule = array_reverse($masterModule);
		foreach ( $masterModule as $lineNumber => $line ) {
			// Extract each key and value
			if ( $this->_is_lang_key( $line ) ) {
				$key = $this->_get_lang_key( $line );
				$this->translations[ $key ][ 'master' ] = $this->_get_lang( $line );
				$this->translations[ $key ][ 'slave' ] = NULL;
			}
		}
		$this->translations = array_reverse($this->translations);

	}
	
	
	/**
	 * Load slave language
	 *
	 * If the slave language file is nonexistent load use the master file
	 *
	 * @return void
	 */
	function _load_slave_module_data() {
	
		$slaveModule = NULL;
		if ( ! is_file( $this->slaveModulePath ) ) {
			$slaveModule = $this->_load_module( $this->masterModulePath );
		} else {
			$slaveModule = $this->_load_module( $this->slaveModulePath );
		}
		
		foreach ( $slaveModule as $line ) {
			// Extract each key and value
			if ( $this->_is_lang_key( $line ) ) {
				$key = $this->_get_lang_key( $line );
				if ( ! array_key_exists( $key, $this->translations ) ) {
					$this->translations[ $key ][ 'master' ] = NULL;
				}
				if ( ! array_key_exists( 'master', $this->translations[ $key ] ) ) {
					$this->translations[ $key ][ 'master' ] = NULL;
				}
				
				$this->translations[ $key ][ 'slave' ] = $this->_get_lang( $line );
				
			}
		}
		$this->translations = array_reverse($this->translations);

	}
	

	/**
	 * Load slave language details from $_POST
	 *
	 * @return void
	 */
	 
	function _load_post_data() {
	
		$prefix_len = mb_strlen( $this->postUniquifier );
		
		foreach ( $_POST as $post_key => $post_value ) {
			if(str_replace("ci_language_", "", $post_key) != "") {
				if ( strncmp( $this->postUniquifier, $post_key, $prefix_len ) == 0 ) {
					
					$key = mb_substr( $post_key, $prefix_len );
					if ( ! array_key_exists( $key, $this->translations ) ) {
						$this->translations[ $key ][ 'master' ] = NULL;
					}
					$this->translations[ $key ][ 'slave' ] = "'".$post_value."'";
					$this->data[ 'hidden' ][ $post_key ] = $post_value;
				}
			}		
		}
		$this->translations = array_reverse($this->translations);
		
	}
	
	
	/**
	 * Determine if a line of PHP code contains a translation key
	 *
	 * @param $line string
	 * @return boolean
	 */
	 
	function _is_lang_key( $line ) {
		$line = trim($line);
		if(empty($line) || mb_stripos( $line , '$lang[' ) === FALSE ) {
				return FALSE;
		}
		return TRUE;
	}

	
	/**
	 * Extract translation key from a line of PHP code
	 *
	 * @param $line string
	 * @return string
	 */
	 
	function _get_lang_key( $line ) {
		// Trim forward to the first quote mark
		$line = trim( mb_substr( $line, mb_strpos( $line, '[' ) + 1 ) );
		// Trim forward to the second quote mark
		$line = trim( mb_substr( $line, 0, mb_strpos( $line, ']' ) ) );
		return mb_substr( $line, 1, mb_strlen( $line ) - 2 );
	}
	
	
	/**
	 * Extract translation string from a line of PHP code
	 *
	 * @param $line string
	 * @return string
	 */
	 
	function _get_lang( $line ) {
	
		// Trim forward to the first quote mark
		$line = trim( mb_substr( $line, strpos( $line, '=' ) + 1 ) );
		
		// Trim backward from the semi-colon
		$line = mb_substr( $line, 0, mb_strrpos( $line, ';' ) );

		
		return $this->_escape_templates( $line );

	}


	/**
	 * Validate translations
	 *
	 * "Notes" are for information
	 * "Errors" must be fixed before submission
	 * 
	 * @return void
	 */
	 
	function _validate_translations() {

		foreach ( $this->translations as $key => $translation ) {
			$this->translations[ $key ][ 'note' ] = NULL;

			$this->translations[ $key ][ 'error' ] = $this->_validate_line( $translation[ 'slave' ] );

			// Force blank translations to master value
			if ( mb_strlen( trim( $translation[ 'slave' ] ) ) == 0 ) {
				$this->translations[ $key ][ 'slave' ] = '';
			}

			if ( ! $this->translations[ $key ][ 'master' ] ) {
				$this->translations[ $key ][ 'note' ] = "Mismatch - does not exist in master translation";
			}

		}
			
	}


	/**
	 * Check for errors
	 *
	 * @param $line string
	 * @return mixed
	 */
	 
	function _validate_line( $line ) {

		if ( $this->_invalid_quotation_marks( $line ) ) {
			$this->validated = FALSE;
			return  "Invalid syntax - check for unbalanced quotation marks";
		}

		/*if ( mb_strlen( trim( $line ) ) == 0 ) {
			$this->validated = FALSE;
			return  "Entry cannot be blank. Defaulted to master translation.";
		}*/
		
		if ( $this->_invalid_php_translation( $line ) ) {
			$this->validated = FALSE;
			return  "Invalid PHP syntax ";
		}

		return NULL;

	}
	

	/**
	 * Check PHP syntax of a single translation
	 *
	 * Returns FALSE if no errors found otherwise returns the line number of the
	 * error with the error message and bad code in variables passed by reference
	 *
	 * @param $line string
	 * @return int
	 */
	 
	function _invalid_php_translation( $line, &$err = '', &$bad_code = '' ) {
	
		// Insert translation into a dummy php string
		$line = '$dummy_variable = ' . $line . ';';
		return $this->_invalid_php_syntax( $line, $err, $bad_code );
	}


	/**
	 * Check PHP syntax
	 *
	 * Returns FALSE if no errors found otherwise returns the line number of the
	 * error with the error message and bad code in variables passed by reference
	 *
	 * @param $php string
	 * @return int
	 */
	 
	function _invalid_php_syntax( $php, &$err = '', &$bad_code = '' ) {

		// Remove opening and closing PHP tags
		$php = str_replace( '<?php', '', $php );
		$php = str_replace( '?>', '', $php );
		$php = str_replace( ' = ;', "= '';", $php );
		$php = str_replace( ' = ;', "= '';", $php );
		
		// Evaluate the code
		ob_start();
		@eval( $php );
		$err = ob_get_contents();
		ob_end_clean();

		if(!empty($err))
		{
			if ( mb_stripos( $err, 'Parse error' ) == FALSE ) {
				return FALSE;
			}
		}
		// Remove any html tags returned in error message
		$err_text = strip_tags( $err );

		// Get the line number
		$line = (int) trim( substr( $err_text, strripos( $err_text, ' ' ) ) );

		$php = explode( "\n", $php );

		$bad_code = $php[ max( 0, $line - 1 ) ];

		return $line;

	}


	/**
	 * Check for unbalanced quotation marks
	 *
	 * @return boolean
	 */
	 
	function _invalid_quotation_marks( $line ) {

		/* TODO - pure regex version */ 

		// Strip escaped quote marks
		$line = str_replace( "\'", '', $line );
		$line = str_replace( '\"', '', $line );

		// Remove text enclosed by paired quotation marks
		$line = preg_replace( '/[\']{1}[^\']*[\']|["]{1}[^"]*["]/', '', $line );
		
		// Return failed result if any quotation marks remain
		if ( mb_strpos( $line, '\'' ) !== FALSE || mb_strpos( $line, '"' ) !== FALSE ) {
			return TRUE;
		}
		
		return FALSE;

	}
	
	
	/**
	 * Escape template tags
	 *
	 * @return string
	 */
	 
	function _escape_templates( $line ) {
		return preg_replace( '/{(.*)}/', '\\{$1\\}', $line  );
	}

	
	/**
	 * Unescape template tags
	 *
	 * @return string
	 */
	 
	function _unescape_templates( $line ) {
		return preg_replace( '/\\\{(.*)\\\}/', '{$1}', $line  );
	}
	
	
	/**
	 * Load a translation module
	 *
	 * @return array
	 */
	 
	function _load_module( $modulePath ) {

		/* TODO: Add error checking for non-existent files? */

		$module = @file( $modulePath );
		
		return $module;

	}
	
	
	/**
	 * Save translation module
	 *
	 * @return mixed
	 */
	 
	function _translation_saved( &$result ) {

		// Backup original file
		if ( $this->backupFlag && is_file( $this->slaveModulePath ) ) {
			$slaveModule =  $this->_load_module( $this->slaveModulePath );
			$fp = fopen( $this->slaveModulePath . '.' . date( 'Y-M-d-H-i-s' ) . '.bak', 'w' );
			fwrite( $fp, implode( $slaveModule ) );
			fclose( $fp );
		}
		
		// Load the master file
		$master =  $this->_load_module( $this->masterModulePath );
		// Remove closing PHP tag if it exists - allows for easy addition of additonal lines
		if ( $master && mb_strpos( $master[ count( $master ) - 1 ], '?>' ) !== FALSE ) {
			unset( $master[ count( $master ) - 1 ] );
		}
		

		// Replace master translations with new slave translations (including duplicates)
		foreach ( $master as $line_number => $line ) {
			if ( $this->_is_lang_key( $line )) {
				
				
				$key = $this->_get_lang_key( $line );
				$translation = $this->_get_lang( $line );

				if($this->translations[ $key ][ 'slave' ] != "") {
					$master[ $line_number ] = str_replace( $translation, $this->_unescape_templates( $this->translations[ $key ][ 'slave' ] ) , $master[ $line_number ] );
				} else {
					$master[ $line_number ] = str_replace( $translation, $this->_unescape_templates( $this->translations[ $key ][ 'slave' ] ) , '' );
					$master[ $line_number ] = str_replace("\n", "", $master[ $line_number ]);
				}
			} 
			
		}

		// Delete translations common to both master and slave languages
		// Remainder will be vestigial slave language declarations
		foreach ( $master as $line_number => $line ) {
			if ( $this->_is_lang_key( $line ) || $this->_get_lang( $line ) == "") {
				$key = $this->_get_lang_key( $line );
				unset( $this->translations[ $key ] );
			}
		}

		// Append any unmatched translations originally in the slave file
		if ( count( $this->translations ) ) {
			
			foreach ( $this->translations as $key => $translation ) {
				if(trim($translation[ 'slave' ]) != "") {
					$master[] = '$lang[\'' . $key . '\'] = ' . $translation[ 'slave' ] . ';';
				}
			}
		
		}
		
		// Add closing PHP tag
		$master[] = "\n\n?>";
		
		// Clean up new line characters from textarea inputs
		foreach ( $master as $line_number => $line ) {
			$master[ $line_number ] = str_replace( "\n", '', $line );
			$master[ $line_number ] .= "\n";
		}

		
		// Check syntax and attempt to save file
		$php = implode( $master );
		if ( ! $this->_invalid_php_syntax( $php ) ) {
			$fp = @fopen( $this->slaveModulePath, 'w' );
			if ( fwrite( $fp, $php ) !== FALSE ) {
				fclose( $fp );
				unset( $_POST );
				
				foreach ( $master as $line ) {
					$result .= htmlspecialchars( $line ) . '<br />'; 
				}
				return $result;
			}
		}

		// Failed to save
		unset( $_POST[ 'ConfirmSaveLang' ] );
		return FALSE;
		
	}
	

	/**
	 * List languages in working directory
	 *
	 * @return array
	 */
	 
	function _list_languages( $ignore = NULL ) {

		$languages = array();
		
		$dir = $this->langDir;

		$d = @dir( $dir );
		if ( $d ) {
			while (false !== ($entry = $d->read())) {
			   if ( ( $entry != $ignore ) && ( $entry != '.' )  && ( $entry != '..' ) && ( $entry != 'CVS' ) && is_dir( $this->langDir . '/' . $entry) ) {
				$language = $entry;
				if(strtolower($language) != strtolower($this->masterLang)) {
					$languages[] = $language;
				} else {
					array_unshift($languages, $language);
				}
			   }
			}
			$d->close();
		} else {
			return FALSE;
		}
		
		return $languages;
		
	}
	
	
	/**
	 * List language files
	 *
	 * @return array
	 */
	 
	function _list_modules( $language ) {
		
		$modules = array();
		
		$dir = $this->langDir . '/' . $language;

		$d = @dir( $dir );

		if ( $d ) {
			while (false !== ($entry = $d->read())) {
			   	$file = $dir . '/' . $entry;
				if ( is_file( $file ) ) {
					$path_parts = pathinfo( $file );
					if ( $path_parts[ 'extension' ] == 'php' ) {
						$modules[] = $entry;
					}
			   }
			}
			$d->close();
		} else {
				return FALSE;
		}

		sort( $modules );
		
		return $modules;
		
	}

}

?>
