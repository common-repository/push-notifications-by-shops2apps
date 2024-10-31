<?php
if (!defined('ABSPATH')) exit;
/*
    Plugin Name: Push Notifications by Shops2Apps
    Description: The Best WordPress Push Notifications plugin for sending and scheduling push notifications from your Wordpress to iOS/Android devices. Get started with Shops2Apps for free.
    Author: Shops2Apps.com
    Author URI: shops2apps.com
    Version: 0.1.0
*/

$s2aPnRemoteServer = 'http://icontrustapp.com/rest.svrlic';
include(plugin_dir_path(__FILE__) . 'inc/function.php');

function shops2apps_pns_page()
{
    add_menu_page('Shops2Apps', 'Shops2Apps', 'manage_options', '#shops2apps', '', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAARCAYAAADdRIy+AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAACxMAAAsTAQCanBgAAAPSSURBVDhPrZN/TFNXFMfvlIFs5Uf73kOyLNkPo9G5LP6x7I9lBnBEA5tMRX5K0EYNiTISTCaL4Nj+cRhHFBnJFmdblUKKG5byo/S9thTaKrYKkyAKyF7fe6W/HhPCiNns8s7ua6px/rNk20m+ue+em/N555x7D3re0obYdMLi2xbf/ncjzGw2QQsLrxr45Ljr35nKGniLYIRsFeNvIGj+t7gbZxx8nbqxeICkhcP/JBXDVcaCSEZopdyLgIFRwur/g7q+BCTjPymfEQz/mdIRuYpM/CFk5NXI5DuEevz78V6d1O+rJi3csZgYoVbF8FeQkvGXkLYAEPbQh1Qfm6kwjFGEfX4f5XoI1FA4M9XCH0nq8+cCwEZJkrZhrcfajvVeLJtnTEnzO5BMJWl+FKuMdD+8QTjCjgwzl5tO8yHUwW1OpKWPznMrw1XOZmi6dw0qRs5Cjv0L2DnyNZyb6fdJUSk/zsMsoRBnyGlw/R7ct+m19wEyZwFSBnnjWlvkTmfg0bfaB6PDn04YIFG7C9411sKqi59AsrYQXry4CxI7i6Hx587fcbYbngJxj7QYNqqieTXpWvQQjogzyRze0TYzN1vsaYaMrgpIxsGqy2Ww1VQHaZdKYt+yXtbuhU10LYwG7597HjgpO54Y7tcHFe4zErqQD8pLpTFt/vEo5JkbIVVX/BQoK9VQDi2TPR45TmHhdyMM0+LsbmN6JV6rUV/k4Kmpu/fW/XQA0nCwDEjVlcCW7hrI6v0cUnRFfwMmXN4DdTd1ARm4eoDb+QR4i7BwVQlmviHHC1+2To8vke1FsZJe0VfGeqbQFoECw4hnYAp8/kbXYahxf/cYV5WOTGxerGScnTfBJNScmVvWtosTsJWpB/R9PuQPfgVX5hjIszaCsr0cl14GL2F4omY3pOtKgeiogBPjeigcPg34Yt5BRi4XkTSnx1mOIdP8Rn3gcZY16G27MGUcJ/SlUO/5IXp9SWK7I9BUaW9aknuaM1AP5UPfwCpNARy/qQ05A3dDB2c1IEWX9yIDn4UI/HDxVEQpG7dO7oNsPQvSngbWCm9274e6SZd4/hexasDn0rxmOQL9PmdHaCXcssV1AmzCrWb5yUjSihqvKUm9bLZ8pS8QVqGXcv4qj14Yj144mQn+WT/zyNgycdWjdp+FDnYExhd8cx97T0EbKw6enrpte999HFqm73hRr1iHjP5ja8z8SdIqGOM54Zl1iAWUdb6asgePUsPz2+NuhP/8thiVCjyRGatmenDIIS639vBTTPNE11if/8G1DJtYQND+mCh59P5fQ+gvNutxOFSbbgwAAAAASUVORK5CYII=', 99.151121);
    add_submenu_page('#shops2apps', '', '', 'manage_options', '#shops2apps', '');
    $hook = add_submenu_page('#shops2apps', 'Push Notification Scheduler', 'Push Notification Scheduler', 'manage_options', 'shops2apps-pn-scheduler', 'shops2apps_pns_callback');

    // load only on viewed page
    add_action("load-$hook", 'shops2apps_pns_add_option');
    add_action("load-$hook", 'shops2apps_pns_load_css');
    add_action("load-$hook", 'shops2apps_pns_load_js');
}

add_action('admin_menu', 'shops2apps_pns_page');

function shops2apps_pns_callback()
{
    /*
     *	API Registration
     **/
    global $s2aPnSetting, $s2aPnRemoteServer;
    if (empty($s2aPnSetting['APIkey'])) {
        $status = 0;

        // try to validate APIkey first
        $APIkey = '';
        if (isset($_POST['apikey'])) {
            $try = shops2apps_pns_check_validity(sanitize_text_field($_POST['apikey']));

            if ($try['response']['code'] === 201) {
                $try = json_decode($try['body']);
                $status = 1;
                $APIkey = $try->apikey;
                $s2aPnSetting['APIkey'] = $APIkey;
                shops2apps_pns_write_json_file($s2aPnSetting);

                // get activation content
                shops2apps_pns_activate(1);
            }
        }

        // get registration form (local)
        include 'inc/api-registration-form.php';
    } else {
        /*
         *	Registered API
         */
        $user = wp_get_current_user();
        $roles = (array)$user->roles;
        if (in_array('administrator', $roles) || in_array('author', $roles)) {
            global $wpdb;

            $uri = '?page=shops2apps-pn-scheduler';
            $table_name = $wpdb->prefix . "appsmoment_pns";
        } else {
            exit('<div class="wrap"><p>Sorry what we expect is <b>Administrator</b> and <b>Author</b> role!</p></div>');
        }

        include('main.php');
    }
}

function shops2apps_pns_shortcode($atts)
{
    include(plugin_dir_path(__FILE__) . 'inc/views/shortcode.php');
}

add_shortcode('shops2apps_pns_form', 'shops2apps_pns_shortcode');

function shops2apps_pns_check_pushable_notification()
{
    global $wpdb;

    $table_name = $wpdb->prefix . "appsmoment_pns";
    $currentdate = date('Y-m-d H:i');
    $sql = "SELECT id, pns_username, pns_password, pns_publisher_id, pns_app_id, pns_url, pns_text FROM $table_name WHERE is_active = 'Y' AND is_push = 'N' AND pns_push_date <= '$currentdate'";

    $results = $wpdb->get_results($sql, OBJECT);
    foreach ($results as $result) {
        // post request
        $data = array('publisherid' => $result->pns_publisher_id, 'username' => $result->pns_username, 'pass' => $result->pns_password, 'appid' => $result->pns_app_id, 'pushmessage' => $result->pns_text);

        // use key 'http' even if you send the request to https
        $options = array(
            'http' => array(
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'method' => 'POST',
                'content' => http_build_query($data),
            )
        );
        $context = stream_context_create($options);
        $response = file_get_contents($result->pns_url, false, $context);

        if (strlen($response) > 100) {
            $response = 'Please check your configuration! Access blocked!';
        }

        // update database
        $wpdb->update(
            $table_name,
            array(
                'is_push' => 'Y',
                'pns_status' => $response
            ),
            array('id' => $result->id),
            array('%s', '%s'),
            array('%d')
        );
    }
}

add_action('appsmoment_pns_cron', 'shops2apps_pns_check_pushable_notification');

function shops2apps_pns_add_option()
{
    /*
     *	Screen Option
     */
    global $s2aPnSetting;
    if (!isset($_GET['action']) && !empty($s2aPnSetting['APIkey'])) {
        add_screen_option(
            'per_page',
            array(
                'label' => __('Maximum rows per page'),
                'default' => 10,
                'option' => 'pns_per_page')
        );
    }
}

function shops2apps_pns_set_option($status, $option, $value)
{
    if ('pns_per_page' == $option) return $value;
    return $status;
}

add_filter('set-screen-option', 'shops2apps_pns_set_option', 10, 3);

function shops2apps_pns_action_links($links)
{
    $links[] = '<a href="http://shops2apps.com" target="_blank">shops2apps.com</a>';

    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'shops2apps_pns_action_links');

function shops2apps_pns_activate($activate = 0)
{
    if ($activate) {
        global $wpdb;

        $table_name = $wpdb->prefix . "appsmoment_pns";
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				pns_publisher_id varchar(50) DEFAULT '' NOT NULL,
				pns_username varchar(50) DEFAULT '' NOT NULL,
				pns_password varchar(50) DEFAULT '' NOT NULL,
				pns_app_id varchar(50) DEFAULT '' NOT NULL,
				pns_device char(10) DEFAULT '' NOT NULL,
				pns_url varchar(50) DEFAULT '' NOT NULL,
				pns_topic varchar(100) DEFAULT '' NOT NULL,
				pns_text text DEFAULT '' NOT NULL,
				pns_push_date datetime NOT NULL,
				pns_status text,
				created_date timestamp DEFAULT CURRENT_TIMESTAMP,
				is_active char(1) DEFAULT 'N' NOT NULL,
				is_push char(1) DEFAULT 'Y' NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // scheduled event
        wp_schedule_event(time(), 'hourly', 'appsmoment_pns_cron');
    } else {
        // make sure clean everything
        shops2apps_pns_reset_setting();
    }
}

register_activation_hook(__FILE__, 'shops2apps_pns_activate');

function shops2apps_pns_deactivate()
{
    global $wpdb;

    // reset setting
    shops2apps_pns_reset_setting();

    $table_name = $wpdb->prefix . "appsmoment_pns";
    $wpdb->query("DROP TABLE IF EXISTS $table_name;");

    // scheduled event
    wp_clear_scheduled_hook('appsmoment_pns_cron');
}

register_deactivation_hook(__FILE__, 'shops2apps_pns_deactivate');

// style and script enqueue function if any
function shops2apps_pns_load_css() {
    wp_enqueue_style('font-awesome.min', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css', null, '4.5.0', 'screen');

    wp_enqueue_style('jquery-ui-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.css', null, '1.8.2', 'screen');
    wp_enqueue_style('jquery-ui-timepicker-addon', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.min.css', null, '1.4.5', 'screen');
}

function shops2apps_pns_load_js() {
    wp_enqueue_script('jquery-ui-timepicker-addon', plugin_dir_url(__FILE__) . 'js/jquery-ui-timepicker-addon.min.js', array('jquery-ui-datepicker'), '1.4.5', false);
}

?>