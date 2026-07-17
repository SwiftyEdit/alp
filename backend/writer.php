<?php

require __DIR__.'/header.php';

// switch mode 1 = show alp entries, 2 = system entries, 3 = all entries
if(isset($_POST['set_mode'])) {
    $_SESSION['alp_mode'] = (int) $_POST['set_mode'];
    header( "HX-Trigger: update_alp_mode");
    exit;
}

if(isset($_POST['save_alp_entry'])) {

    $alp_shorthand = sanitizeUserInputs($_POST['alp_shorthand']);
    $alp_text = sanitizeUserInputs($_POST['alp_text']);
    $alp_lang = sanitizeUserInputs($_POST['alp_lang']);

    if(is_numeric($_POST['alp_id'])) {
        $write_data = $db_alp->update("entries",
            [
                "alp_shorthand" => $alp_shorthand,
                "alp_text" => $alp_text,
                "alp_lang" => $alp_lang
            ],
            ['alp_id' => (int) $_POST['alp_id']]
        );
    } else {
        $write_data = $db_alp->insert("entries",[
            "alp_shorthand" => $alp_shorthand,
            "alp_text" => $alp_text,
            "alp_lang" => $alp_lang
        ]);
    }

    if($addon_settings['prefs_modus'] == 'overwrite') {
        // build language files in /data/includes/
        $all_alp_entries = $db_alp->select("entries", "*");
        build_advanced_lf($all_alp_entries);
    }

    header("HX-Trigger: update_alp_list");
    exit;
}

if(isset($_POST['delete_alp'])) {
    $write_data = $db_alp->delete("entries",[
        "alp_id" => (int) $_POST['alp_id']
    ]);
    header("HX-Trigger: update_alp_list");
    exit;
}