<?php


require __DIR__.'/header.php';

$checked_extend = 'checked';


// delete language files -> /data/lang_*.php
if(isset($_POST['del_lang_files'])) {
	delete_advanced_lf();
}

/* WRITE THE DATA */
if(isset($_POST['save'])) {

    $prefs_modus = 'overwrite';
    if($_POST['prefs_modus'] == 'extend') {
        $prefs_modus = 'extend';
    }

    $db_alp->update("prefs",[
        "prefs_modus" => "$prefs_modus"
    ],[
        "prefs_status" => "active"
    ]);
}

$prefs = get_alp_preferences();


foreach($prefs as $k => $v) {
   $$k = stripslashes($v);
}

if($prefs_modus == 'overwrite') {
	$checked_overwrite = 'checked';
	$checked_extend = '';
} else {
	$checked_overwrite = '';
}

echo '<div class="row">';
echo '<div class="col-md-6">';


echo '<form action="/admin/addons/plugin/alp/prefs/" class="form-horizontal" method="POST">';

echo '<div class="card">';
echo '<div class="card-header">' . $addon_lang['label_modus'] . '</div>';
echo '<div class="card-body">';
echo '<div class="form-check">';
echo '<input id="mode_e" class="form-check-input" type="radio" name="prefs_modus" value="extend" '.$checked_extend.'>';
echo '<label class="form-check-label" for="mode_e">';
echo '<strong>'.$addon_lang['label_modus_extend'].'</strong><br>'.$addon_lang['modus_extend_tip'];
echo '</label>';
echo '</div>';

echo '<div class="form-check">';
echo '<input id="mode_o" class="form-check-input" type="radio" name="prefs_modus" value="overwrite" '.$checked_overwrite.'>';
echo '<label class="form-check-label" for="mode_o">';
echo '<strong>'.$addon_lang['label_modus_overwrite'].'</strong><br>'.$addon_lang['modus_overwrite_tip'];
echo '</label>';
echo '</div>';

echo '</div>';
echo '</div>';

echo '<input class="btn btn-save" type="submit" name="save" value="'.$lang['save'].'">';
echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo '</form>';


echo '</div>'; // col
echo '<div class="col-md-6">';

echo '<div class="alert alert-info">';

echo '<form action="/admin/addons/plugin/alp/prefs/" class="form-horizontal" method="POST">';
echo '<p>'.$addon_lang['delete_lang_files'].'</p>';

$alf = get_advanced_lf();
if((is_array($alf)) && (count($alf)>0)) {
    echo '<p>';
	foreach($alf as $f) {
		echo '<span class="badge badge-default">' . basename($f).'</span> ';
	}
    echo '</p>';
	echo '<p><input class="btn btn-dark text-danger" type="submit" name="del_lang_files" value="'.$lang['delete'].'"></p>';
} else {
	echo '<p class="alert-info p-2">'.$addon_lang['no_alp_files'].'</p>';
}

echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';

echo '</form>';

echo '</div>'; // alert

echo '</div>'; // col
echo '</div>'; // row