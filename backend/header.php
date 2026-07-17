<?php
global $addon_lang;
use Medoo\Medoo;

$mod_root = SE_ROOT.'plugins/alp/';
require __DIR__.'/functions.php';
$mod_db = SE_CONTENT.'/database/alp.sqlite3';

$db_alp = new Medoo([
    'type' => 'sqlite',
    'database' => $mod_db
]);

if(!isset($_SESSION['alp_mode'])) {
    $_SESSION['alp_mode'] = '1';
}

$addon_lang = se_return_addon_translations('alp');
$addon_settings = get_alp_preferences();