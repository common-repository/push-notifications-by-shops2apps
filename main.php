<?php
if (!defined('ABSPATH')) exit;

if (isset($_POST['action'])) {
    switch (sanitize_text_field($_POST['action'])) {
        case 'save':
            shops2apps_pns_do_save($wpdb, $table_name);
            break;
        case 'save-page':
            shops2apps_pns_do_save_page();
            break;
        case 'edit':
            shops2apps_pns_do_edit($wpdb, $table_name);
            break;
    }
}

if (!isset($_GET['action'])) {
    include('inc/views/read.php');
} else {
    switch (sanitize_text_field($_GET['action'])) {
        case 'create':
            include('inc/views/create.php');
            break;
        case 'create-page':
            include('inc/views/create-page.php');
            break;
        case 'update':
            include('inc/views/update.php');
            break;
        case 'delete':
            shops2apps_pns_do_delete($wpdb, $table_name);
            echo '<div class="wrap">Data deleted ....<br><br><input type="button" value="Back to List" class="button action" onclick="window.location.replace(\'' . $uri . '\');"></div>';
            break;
    }
}
?>