<?php

require __DIR__.'/header.php';

if(isset($_GET['reset'])) {
    header('HX-Redirect: /admin/addons/plugin/alp/start/');
}

// nav
if($_GET['show'] == 'nav') {

    $alp_mode = (int) $_SESSION['alp_mode'];
    $vals = ['csrf_token' => $_SESSION['token']];

    echo '<nav hx-target="this">';
    echo '<button hx-post="/admin-xhr/addons/plugin/alp/write/" hx-swap="none" hx-vals=\''.json_encode($vals).'\' name="set_mode" value="1" class="btn btn-default '.($alp_mode == 1 ? 'active' :'').'">alp.mod</button>';
    echo '<button hx-post="/admin-xhr/addons/plugin/alp/write/" hx-swap="none" hx-vals=\''.json_encode($vals).'\' name="set_mode" value="2" class="btn btn-default '.($alp_mode == 2 ? 'active' :'').'">system</button>';
    echo '</nav>';
    exit;
}

// show the entries
if($_GET['show'] == 'entries') {

    global $db_alp;

    echo '<div class="scroll-container mt-2">';
    if($_SESSION['alp_mode'] == 1) {
        $lang_entries = $db_alp->select("entries", "*");



        echo '<table class="table table-sm">';
        echo '<thead><tr><th></th><th>Shorthand</th><th>Text</th><th></th></tr></thead>';
        foreach($lang_entries as $entry) {

            $hx_vals = [
                "csrf_token"=> $_SESSION['token'],
                "alp_id" => $entry['alp_id']
            ];

            //$entry['alp_lang']
            $flag = '<img src="/admin-xhr/images/?src=languages/'.$entry['alp_lang'].'/flag.png" width="15">';

            echo '<tr>';
            echo '<td>'.$flag.'</td>';
            echo '<td><code>'.$entry['alp_shorthand'].'</code></td>';
            echo '<td>'.$entry['alp_text'].'</td>';
            echo '<td class="text-end">';
            echo '<button class="btn btn-sm btn-default me-1" name="edit_alp" hx-vals=\''.json_encode($hx_vals).'\' hx-get="/admin-xhr/addons/plugin/alp/read/?show=form" hx-target="#alp_form">'.$icon['edit'].'</button>';
            echo '<button class="btn btn-sm btn-default text-danger" name="delete_alp" hx-confirm="'.$lang['msg_confirm_delete'].'" hx-vals=\''.json_encode($hx_vals).'\' hx-post="/admin-xhr/addons/plugin/alp/write/">'.$icon['trash_alt'].'</button>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';

    } else {
        $lang_entries = $lang;

        echo '<table class="table table-sm">';
        echo '<thead><tr><th>Shorthand</th><th>Text</th><th></th></tr></thead>';
        foreach($lang_entries as $k => $v) {

            $hx_vals = [
                "csrf_token"=> $_SESSION['token'],
                "duplicate_key" => $k
            ];

            echo '<tr>';
            echo '<td>'.$k.'</td>';
            echo '<td>'.$v.'</td>';
            echo '<td>';
            echo '<button class="btn btn-sm btn-default" name="edit_alp" hx-vals=\''.json_encode($hx_vals).'\' hx-get="/admin-xhr/addons/plugin/alp/read/?show=form" hx-target="#alp_form">'.$lang['btn_duplicate'].'</button>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';

    }
    echo '</div>';
    exit;
}

if($_GET['show'] == 'form') {

    $tplform = file_get_contents(SE_ROOT."/plugins/alp/templates/acp_form.tpl");

    if(isset($_GET['alp_id'])) {
        $alp_id = (int)$_GET['alp_id'];
        $edit_data = get_alp_entry($alp_id);
        $sel_lang = $edit_data['alp_lang'];
        $btn_text = $lang['btn_update'];
        $btn_reset = '<button class="btn btn-default ms-auto" hx-get="/admin-xhr/addons/plugin/alp/read/?reset">'.$lang['reset'].'</button>';
    } else {
        $sel_lang = $languagePack;
        $btn_reset = '';
        $btn_text = $lang['btn_save'];
    }

    if(isset($_GET['duplicate_key'])) {
        $sel_lang = $languagePack;
        $btn_reset = '<button class="btn btn-default ms-auto" hx-get="/admin-xhr/addons/plugin/alp/read/?reset">'.$lang['reset'].'</button>';
        $edit_data['alp_shorthand'] = $_GET['duplicate_key'];
        $edit_data['alp_text'] = $lang[$_GET['duplicate_key']];
        $edit_data['alp_id'] = 'duplicate';
        $btn_text = $lang['btn_duplicate'];
    }



    $select_lang = '<select name="alp_lang" id="alp_lang" class="form-control">';
    foreach($langs as $lang) {
        $sel_this = '';
        if($lang == $sel_lang) {
            $sel_this = ' selected';
        }
        $select_lang .= '<option value="'.$lang.'" '.$sel_this.'>'.$lang.'</option>';
    }
    $select_lang .= '</select>';

    if(is_array($edit_data)) {
        $tplform = str_replace('{mode}', 'edit', $tplform);
        $tplform = str_replace('{alp_id}', $edit_data['alp_id'], $tplform);
        $tplform = str_replace('{alp_shorthand}', $edit_data['alp_shorthand'], $tplform);
        $tplform = str_replace('{alp_text}', $edit_data['alp_text'], $tplform);
        $tplform = str_replace('{btn_value}', $btn_text, $tplform);
        $tplform = str_replace('{select_alp_langs}', $select_lang, $tplform);
        $tplform = str_replace('{btn_reset}', $btn_reset, $tplform);
    } else {
        $tplform = str_replace('{mode}', 'new', $tplform);
        $tplform = str_replace('{alp_id}', '', $tplform);
        $tplform = str_replace('{alp_shorthand}', '', $tplform);
        $tplform = str_replace('{alp_text}', '', $tplform);
        $tplform = str_replace('{btn_value}', $btn_text, $tplform);
        $tplform = str_replace('{select_alp_langs}', $select_lang, $tplform);
        $tplform = str_replace('{btn_reset}', $btn_reset, $tplform);
    }




    foreach($addon_lang as $k => $v) {
        $tplform = str_replace('{'.$k.'}', $v, $tplform);
    }

    $tplform = str_replace('{token}', $_SESSION['token'], $tplform);

    echo $tplform;
}

