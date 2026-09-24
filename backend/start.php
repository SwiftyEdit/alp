<?php

global $addon_lang;

//error_reporting(E_ALL ^E_NOTICE ^E_WARNING);
if(!defined('SE_ROOT')) {
    die("No access to alp.mod start");
}


use Medoo\Medoo;

require SE_ROOT.'plugins/alp/install/installer.php';
require __DIR__.'/header.php';

// the overview loads everything via /admin-xhr/, which the core only routes
// to activated plugins - without this hint the page just stays empty
if(function_exists('se_is_plugin_activated') && !se_is_plugin_activated('alp')) {
    echo '<div class="alert alert-warning">'.$addon_lang['msg_not_activated'].'</div>';
    return;
}


echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<div hx-get="/admin-xhr/addons/plugin/alp/read/?show=nav" hx-trigger="load, update_alp_mode from:body"></div>';
echo '<div hx-get="/admin-xhr/addons/plugin/alp/read/?show=entries" hx-trigger="load, update_alp_mode from:body, update_alp_list from:body"></div>';

echo '</div>';
echo '<div class="col-md-6">';
echo '<div id="alp_form" hx-get="/admin-xhr/addons/plugin/alp/read/?show=form" hx-trigger="load, update_alp_entry from:body"></div>';
echo '</div>';
echo '</div>';