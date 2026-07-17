<?php


if(!defined('SE_ROOT')) {
	die("No access");
}

function get_alp_entry($id) {
    global $db_alp;
    $get_data = $db_alp->get("entries", "*", ["alp_id" => $id]);
    return $get_data;
}


/**
 * get the installed language files from ../core/lang/
 */
 
function get_system_languages($d='../core/lang') {

	$cntLangs = 0;
	$scanned_directory = array_diff(scandir($d), array('..', '.','.DS_Store'));
	
	foreach($scanned_directory as $lang_folder) {
		if(is_file("$d/$lang_folder/index.php")) {
			include("$d/$lang_folder/index.php");
			include("$d/$lang_folder/dict-frontend.php");

            $json_frontend = file_get_contents("$d/$lang_folder/frontend.json");
            $json_dict = file_get_contents("$d/$lang_folder/dictionary.json");
            $json_backend = file_get_contents("$d/$lang_folder/backend.json");
            $json_install = file_get_contents("$d/$lang_folder/install.json");

            $data_frontend = json_decode($json_frontend,true);
            $data_dict = json_decode($json_dict,true);
            $data_backend = json_decode($json_backend,true);
            $data_install = json_decode($json_install,true);
            $lang_data = array_merge($data_frontend,$data_dict,$data_backend,$data_install);

			$arr_lang[$cntLangs]['lang_sign'] = $lang_sign;
			$arr_lang[$cntLangs]['lang_desc'] = $lang_desc;
			$arr_lang[$cntLangs]['lang_folder'] = $lang_folder;
			$arr_lang[$cntLangs]['lang_contents'] = $lang_data;
			$cntLangs++;
		}
	}
	
	return($arr_lang);
}

/**
 * get preferences
 */

function get_alp_preferences() {
	global $db_alp;
    $prefs = $db_alp->get("prefs", "*", [
        "prefs_status" => 'active'
    ]);
	return $prefs;
}

/**
 * build language files
 * store them into the plugin directory
 */

function build_advanced_lf($array) {

    $scan_dir = SE_ROOT.'languages';

	$scanned_directory = array_diff(scandir("$scan_dir"), array('..', '.','.DS_Store','index.php'));
	$plugin_header = file_get_contents(SE_ROOT.'/plugins/alp/templates/plugin_header.tpl');
	
	$lang_str = '';
	foreach($scanned_directory as $lang_folder) {
		$lang_str = "<?php\r\n";
		$lang_str .= "$plugin_header\r\n\r\n";
		$lang_str .= "if(SE_SECTION == 'frontend') {\r\n";
		foreach ($array as $entry) {
			if($entry['alp_lang'] == $lang_folder) {
				$lang_str .= "\$lang['".$entry['alp_shorthand']."'] = '".addslashes(str_replace("\"","&quot;",$entry['alp_text']))."';\r\n";
			}
			
		}
		$lang_str .= "}\r\n";
		
		$file = SE_CONTENT.'/includes/lang_'.$lang_folder.'.php';
		if(file_put_contents($file, $lang_str, LOCK_EX)) {
			echo 'Stored file <code>'.basename($file).'</code><br>';
		} else {
            echo '<span class="text-danger">Error: '.basename($file).'</span>';
        }
		chmod("$file", 0777);
		
	}
}

/**
 * get language files from the plugin directory
 */

function get_advanced_lf() {
	$list = glob(SE_CONTENT."/includes/lang_*.php");
	return $list;
}

/**
 * delete language files from the plugin directory
 */
 
function delete_advanced_lf() {
	$list = get_advanced_lf();
	if(is_array($list)) {
		foreach($list as $f) {
			unlink($f);
		}
	}
}

?>