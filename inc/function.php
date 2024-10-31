<?php
if (!defined('ABSPATH')) exit;

/*
 *	Include other vendor library
 */
require('vendor/skyverge.com/functions.php');
$s2aPnSetting = json_decode(file_get_contents(__DIR__ . '../../setting.json'), true);

function shops2apps_pns_count_items($wpdb, $table_name)
{
    return $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE is_active = 'Y'");
}

function shops2apps_pns_convert_date_time($datetime, $direction)
{
    /*
     *	Please note the push date need to be converted to WP Server time
     * 	Also when reading the data don't forget to include the time-zone
     */
    $time_zone = shops2apps_pns_wp_get_timezone_string();

    if ($direction === 'server') {
        $date = DateTime::createFromFormat('m-d-Y H:i e', $datetime . ' ' . $time_zone);
        $date->setTimeZone(new DateTimeZone('UTC'));
    } elseif ($direction === 'client') {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        $date->setTimeZone(new DateTimeZone($time_zone));
    }

    return $date;
}

function shops2apps_pns_do_save($wpdb, $table_name)
{
    // validate nonce
    check_admin_referer('create_shops2apps_pns');

    $wpdb->insert(
        $table_name,
        array(
            'pns_username' => sanitize_text_field($_POST['username']),
            'pns_password' => sanitize_text_field($_POST['password']),
            'pns_publisher_id' => sanitize_text_field($_POST['publisher-id']),
            'pns_app_id' => sanitize_text_field($_POST['app-id']),
            'pns_device' => sanitize_text_field($_POST['device']),
            'pns_url' => shops2apps_pns_get_post_uri(sanitize_text_field($_POST['device'])),
            'pns_text' => sanitize_text_field($_POST['text']),
            'pns_push_date' => date_format(shops2apps_pns_convert_date_time(sanitize_text_field($_POST['push-date']), 'server'), 'Y-m-d H:i'),
            'is_active' => 'Y',
            'is_push' => 'N'
        )
    );
}

function shops2apps_pns_do_save_page()
{
    // validate nonce
    check_admin_referer('create_shops2apps_pns_page');

    $new_page_title = sanitize_text_field($_POST['page-title']);
    $new_page_content = '[shops2apps_pns_form d="' . sanitize_text_field($_POST['device']) . '" pubid="' . sanitize_text_field($_POST['publisher-id']) . '" u="' . sanitize_text_field($_POST['username']) . '" p="' . sanitize_text_field($_POST['password']) . '" appid="' . sanitize_text_field($_POST['app-id']) . '"]';

    //don't change the code bellow, unless you know what you're doing
    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
        'post_type' => 'page',
        'post_title' => $new_page_title,
        'post_content' => $new_page_content,
        'post_status' => 'publish',
        'post_author' => 1
    );

    if (!isset($page_check->ID)) {
        $new_page_id = wp_insert_post($new_page);
        add_post_meta($new_page_id, '_et_pb_page_layout', 'et_full_width_page', true);
    } else {
        $new_page['ID'] = $page_check->ID;
        wp_update_post($new_page, true);
    }
}

function shops2apps_pns_do_edit($wpdb, $table_name)
{
    $id = intval(sanitize_text_field($_POST['id']));

    // validate nonce
    check_admin_referer('update_shops2apps_pns_' . $id);

    $wpdb->update(
        $table_name,
        array(
            'pns_username' => sanitize_text_field($_POST['username']),
            'pns_password' => sanitize_text_field($_POST['password']),
            'pns_publisher_id' => sanitize_text_field($_POST['publisher-id']),
            'pns_app_id' => sanitize_text_field($_POST['app-id']),
            'pns_device' => sanitize_text_field($_POST['device']),
            'pns_url' => shops2apps_pns_get_post_uri(sanitize_text_field($_POST['device'])),
            'pns_text' => sanitize_text_field($_POST['text']),
            'pns_push_date' => date_format(shops2apps_pns_convert_date_time(sanitize_text_field($_POST['push-date']), 'server'), 'Y-m-d H:i')
        ),
        array('id' => $id),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),
        array('%d')
    );
}

function shops2apps_pns_do_delete($wpdb, $table_name)
{
    // validate nonce
    if (!wp_verify_nonce(sanitize_text_field($_GET['_wpnonce']), 'delete_shops2apps_pns')) {
        wp_nonce_ays('delete_shops2apps_pns');
    }

    $wpdb->update(
        $table_name,
        array(
            'is_active' => 'N',
            'pns_status' => 'Deleted by ' . wp_get_current_user()->user_login
        ),
        array('id' => intval(sanitize_text_field($_GET['id']))),
        array('%s', '%s'),
        array('%d')
    );
}

function shops2apps_pns_get_list($wpdb, $table_name, $paged, $show)
{
    $initial = (($paged - 1) * $show);
    $sql_data = "SELECT id, pns_username, pns_password, pns_publisher_id, pns_app_id, pns_device, pns_text, pns_push_date, pns_status, is_push FROM $table_name WHERE is_active = 'Y' ORDER BY FIELD(is_push, 'N', 'Y'), pns_push_date DESC LIMIT $initial, $show";
    $results = $wpdb->get_results($sql_data, OBJECT);

    return $results;
}

function shops2apps_pns_get_notification($wpdb, $table_name, $id)
{
    $result = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT pns_username, pns_password, pns_publisher_id, pns_app_id, pns_device, pns_text, pns_push_date FROM $table_name WHERE id = %d",
            $id
        )
        , OBJECT);

    return $result;
}

function shops2apps_pns_get_post_uri($device)
{
    if ($device === 'Android')
        $url = 'http://apps.tashlik.org/c2dm_send.ashx';
    else
        $url = 'http://apps.tashlik.org/push.ashx';

    return $url;
}

function shops2apps_pns_check_validity($apikey)
{
    global $s2aPnRemoteServer;
    $try = wp_remote_post("$s2aPnRemoteServer/c/r/", array(
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
            'X-SVR-LIC' => 1),
        'body' => array(
            'p' => 'shops2apps-pn-scheduler',
            'k' => $apikey)
    ));

    return $try;
}

function shops2apps_pns_reset_setting()
{
    global $s2aPnSetting;
    $s2aPnSetting['APIkey'] = '';
    shops2apps_pns_write_json_file($s2aPnSetting);
}

function shops2apps_pns_write_json_file($json)
{
    $fp = fopen(plugin_dir_path(__FILE__) . '../setting.json', 'w');
    fwrite($fp, json_encode($json));
    fclose($fp);
}

?>