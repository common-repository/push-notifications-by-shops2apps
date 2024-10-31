<?php
if (!defined('ABSPATH')) exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $wpdb;
    $table_name = $wpdb->prefix . "appsmoment_pns";
    shops2apps_pns_do_save($wpdb, $table_name);

    echo '<p>Your request is being processed</p>';
}

wp_enqueue_style('jquery-ui-css', plugin_dir_url(__FILE__) . '../../css/jquery-ui.css', null, '1.8.2', 'screen');
wp_enqueue_style('jquery-ui-timepicker-addon', plugin_dir_url(__FILE__) . '../../css/jquery-ui-timepicker-addon.min.css', null, '1.4.5', 'screen');

wp_enqueue_script('jquery-ui-timepicker-addon', plugin_dir_url(__FILE__) . '../../js/jquery-ui-timepicker-addon.min.js', array('jquery-ui-datepicker'), '1.4.5', false);

$date = shops2apps_pns_convert_date_time(date('Y-m-d H:00:00'), 'client');
?>
<style>
    .pns-form {
        width: 400px;
    }

    .pns-form input {
        border-radius: 3px;
        float: left;
        margin-bottom: 5px;
        padding: 8px;
    }

    .pns-form textarea {
        border-radius: 3px;
        margin-bottom: 5px;
        width: 100%;
    }
</style>

<div class="pns-wrapper" style="text-align: center; margin-bottom: 150px;">
    <div style="display: inline-block; position: relative; transform: translateY(-100%); margin-right: 20px;">
        <span style="color: rgb(131, 127, 126); font-size: 2.5em;">Push</span>
        <span style="color: rgb(106, 127, 194); font-size: 2.5em;">Notification</span>
    </div>
    <div style="display: inline-block;" class="pns-form">
        <form name="push" method="post">
            <input type="hidden" id="publisher-id" name="publisher-id" value="<?php echo esc_attr($atts['pubid']); ?>">
            <input type="hidden" id="username" name="username" value="<?php echo esc_attr($atts['u']); ?>">
            <input type="hidden" id="password" name="password" value="<?php echo esc_attr($atts['p']); ?>">
            <input type="hidden" id="app-id" name="app-id" value="<?php echo esc_attr($atts['appid']); ?>">
            <input type="hidden" id="device" name="device" value="<?php echo esc_attr($atts['d']); ?>">
            <input id="push-date" name="push-date" type="text"
                   value="<?php echo esc_attr(date_format($date, 'm-d-Y H:00')); ?>"
                   placeholder="Date and Time" required>
            <?php wp_nonce_field('create_shops2apps_pns'); ?>

            <p style="float:right; margin-right: -10px;" class="form-submit">
                <input type="submit" value="Push" class="submit" style="cursor: pointer">
            </p><br>
            <textarea id="text" name="text" rows="5" placeholder="Push Message" required></textarea>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        var yourDate = new Date();
        jQuery('#push-date').datetimepicker({
            dateFormat: 'mm-dd-yy',
            timeFormat: 'HH:mm',
            changeMonth: true,
            changeYear: true,
            minDate: new Date(yourDate.getFullYear(), yourDate.getMonth(), yourDate.getDate(), 0, 0, 0, 0),
            showButtonPanel: false,
            showTime: false,
            showMinute: false,
            showSecond: false,
            minuteMax: 0
        });
    });
</script>