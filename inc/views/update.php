<?php
if (!defined('ABSPATH')) exit;

$id = intval(sanitize_text_field($_GET['id']));
$pns = shops2apps_pns_get_notification($wpdb, $table_name, $id);

// empty return must die
if ($pns === null)
    die('<div class="wrap">ID Not found<br><br><input type="button" value="Back to List" class="button action" onclick="window.location.replace(\'' . $uri . '\');"></div>');

// format the date
$date = date_format(shops2apps_pns_convert_date_time($pns->pns_push_date, 'client'), 'm-d-Y H:i');
?>
<style>
    .ui-datepicker-current {
        display: none !important;
    }
</style>
<div class="wrap">
    <h2>Update Push Notification Scheduler</h2>

    <form action="<?php echo $uri; ?>" method="post">
        <input id="action" name="action" type="hidden" value="edit">
        <input id="id" name="id" type="hidden" value="<?php echo $id ?>">
        <input id="publisher-id" name="publisher-id" type="hidden" value="<?php echo $pns->pns_publisher_id ?>">
        <?php wp_nonce_field('update_shops2apps_pns_' . $id); ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" style="position: relative;">
                    <table class="form-table">
                        <tbody>
                        <tr class="form-field">
                            <th><label for="username">Username <span class="description">(required)</span></label></th>
                            <td><input id="username" name="username" type="text"
                                       value="<?php echo $pns->pns_username ?>" placeholder="Username" autofocus
                                       required></td>
                        </tr>
                        <tr class="form-field">
                            <th><label for="password">Password <span class="description">(required)</span></label></th>
                            <td><input id="password" name="password" type="text"
                                       value="<?php echo $pns->pns_password ?>" placeholder="Password" required></td>
                        </tr>
                        <tr class="form-field">
                            <th><label for="app-id">App Id <span class="description">(required)</span></label></th>
                            <td><input id="app-id" name="app-id" type="text" value="<?php echo $pns->pns_app_id ?>"
                                       placeholder="App Id" required></td>
                        </tr>
                        <tr class="form-field">
                            <th><label for="text">Notification <span class="description">(required)</span></label></th>
                            <td><textarea id="text" name="text" placeholder="Push Message"
                                          required><?php echo $pns->pns_text ?></textarea></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div id="postbox-container-1" class="postbox-container">
                    <div id="submitdiv" class="stuffbox">
                        <h3 class="hndle"><span>Post Schedule</span></h3>

                        <div class="inside">
                            <div class="submitbox">
                                <div id="minor-publishing">
                                    <div class="misc-pub-section">
                                        <label for="device" style="float: left;">Device: </label>

                                        <div style="text-align: right;">
                                            <select id="device" name="device">
                                                <option value="Android">Android</option>
                                                <option value="iOS">iOS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="misc-pub-section">
                                        <label for="push-date" style="float: left;">Date: </label>

                                        <div style="text-align: right;"><input id="push-date" name="push-date"
                                                                               type="text" value="<?php echo $date ?>"
                                                                               placeholder="Date and Time" required>
                                        </div>
                                    </div>
                                </div>
                                <div id="major-publishing-actions">
                                    <div id="delete-action"><a href="<?php echo $uri; ?>" class="submitdelete deletion">Back
                                            to List</a></div>
                                    <div id="publishing-action">
                                        <span class="spinner"></span>
                                        <input type="submit" value="Update" class="button button-primary" id="save"
                                               name="save">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </form>
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
        jQuery('#ui-datepicker-div').hide();

        // update
        jQuery('#device').val('<?php echo $pns->pns_device ?>');
    });
</script>