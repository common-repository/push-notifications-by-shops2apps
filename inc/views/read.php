<?php
if (!defined('ABSPATH')) exit;

/*
 *	Screen Options
 */
$user = get_current_user_id();
$screen = get_current_screen();
$option = $screen->get_option('per_page', 'option');
$per_page = get_user_meta($user, $option, true);
if (empty($per_page) || $per_page < 1) {
    $per_page = $screen->get_option('per_page', 'default');
}

/*
 *	Pagination
 */
$total = shops2apps_pns_count_items($wpdb, $table_name);
$show = $per_page;
$maxPaged = floor($total / $show) + 1;
$paged = isset($_GET['paged']) ? intval(sanitize_text_field($_GET['paged'])) : 1;
?>

<div class="wrap">
    <h2>
        Push Notification Scheduler
        <a class="add-new-h2" href="<?php echo "$uri&action=create"; ?>">Add New</a>
        <a class="add-new-h2" href="<?php echo "$uri&action=create-page"; ?>">Create Page</a>
    </h2>

    <p>Decide what time you need to push notification and let us handle the rest</p>

    <form id="posts-filter" method="get">
        <!-- NAVIGATION | TOP -->
        <div class="tablenav top">
            <div class="alignleft actions">
                <select id="action" name="action">
                    <option value="">-- Action --</option>
                    <option value="edit">Edit</option>
                    <option value="delete">Delete</option>
                </select>
                <input type="button" value="Apply" class="button action" id="doaction">
            </div>

            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo $total; ?> items</span>
				<span class="pagination-links"><a
                        href="<?php echo $paged === 1 ? 'javascript:void(0);' : $uri . '&paged=1' ?>"
                        title="Go to the first page"
                        class="first-page <?php echo $paged === 1 ? 'disabled' : '' ?>">&laquo;</a>
					<a href="<?php echo $paged === 1 ? 'javascript:void(0);' : $uri . '&paged=' . ($paged - 1) ?>"
                       title="Go to the previous page"
                       class="prev-page <?php echo $paged === 1 ? 'disabled' : '' ?>">&lsaquo;</a>
					<span class="paging-input">
						<label class="screen-reader-text" for="current-page-selector">Select Page</label>
						<input type="text" size="1" value="<?php echo $paged ?>" name="paged" title="Current page"
                               id="current-page-selector" class="current-page"> of <span
                            class="total-pages"><?php echo $maxPaged ?></span>
					</span>
					<a href="<?php echo $paged >= $maxPaged ? 'javascript:void(0);' : $uri . '&paged=' . ($paged + 1) ?>"
                       title="Go to the next page"
                       class="next-page<?php echo $paged >= $maxPaged ? ' disabled' : '' ?>">&rsaquo;</a>
					<a href="<?php echo $paged >= $maxPaged ? 'javascript:void(0);' : $uri . '&paged=' . $maxPaged ?>"
                       title="Go to the last page"
                       class="last-page<?php echo $paged >= $maxPaged ? ' disabled' : '' ?>">&raquo;</a>
				</span>
            </div>
        </div>

        <!-- DATAGRID -->
        <table class="wp-list-table widefat fixed striped posts">
            <thead>
            <th class="manage-column column-cb check-column" scope="col"></th>
            <th class="manage-column column-author" id="username" scope="col">Username</th>
            <th class="manage-column column-author" id="app-id" scope="col">App ID</th>
            <th class="manage-column column-author" id="device" scope="col">Device</th>
            <th class="manage-column" id="Text" scope="col">Text</th>
            <th class="manage-column" id="status" scope="col">Status</th>
            <th class="manage-column column-author" id="date" scope="col">Date</th>
            </thead>
            <tbody id="the-list">
            <?php
            if ($total === '0') {
                echo '<tr class="no-items"><td colspan="5" class="colspanchange">No pages found.</td></tr>';
            } else {
                $results = shops2apps_pns_get_list($wpdb, $table_name, $paged, $show);
                foreach ($results as $result) {
                    $date = date_format(shops2apps_pns_convert_date_time($result->pns_push_date, 'client'), 'm-d-Y H:i');
                    echo "<tr><th class=\"check-column\" scope=\"row\">" . ($result->is_push === 'N' ? "<input id=\"selected-row\" name=\"selected-row\" type=\"radio\" value=\"$result->id\">" : "") . "</th><td>$result->pns_username</td><td>$result->pns_app_id</td><td>$result->pns_device</td><td>" . nl2br($result->pns_text) . "</td><td>$result->pns_status</td><td>$date</td></tr>";
                }
            }
            ?>
            </tbody>
            <tfoot>
            <th class="manage-column column-cb check-column" scope="col"></th>
            <th class="manage-column" id="username" scope="col">Username</th>
            <th class="manage-column" id="app-id" scope="col">App ID</th>
            <th class="manage-column" id="device" scope="col">Device</th>
            <th class="manage-column" id="Text" scope="col">Text</th>
            <th class="manage-column" id="status" scope="col">Status</th>
            <th class="manage-column" id="date" scope="col">Date</th>
            </tfoot>
        </table>

        <!-- NAVIGATION | BOTTOM -->
        <div class="tablenav bottom">
            <div class="alignleft actions">
                <select id="action" name="action">
                    <option value="">-- Action --</option>
                    <option value="edit">Edit</option>
                    <option value="delete">Delete</option>
                </select>
                <input type="button" value="Apply" class="button action" id="doaction">
            </div>

            <div class="tablenav-pages">
                <span class="displaying-num"><?php echo $total; ?> items</span>
				<span class="pagination-links"><a
                        href="<?php echo $paged === 1 ? 'javascript:void(0);' : $uri . '&paged=1' ?>"
                        title="Go to the first page"
                        class="first-page <?php echo $paged === 1 ? 'disabled' : '' ?>">&laquo;</a>
					<a href="<?php echo $paged === 1 ? 'javascript:void(0);' : $uri . '&paged=' . ($paged - 1) ?>"
                       title="Go to the previous page"
                       class="prev-page <?php echo $paged === 1 ? 'disabled' : '' ?>">&lsaquo;</a>
					<span class="paging-input">
						<label class="screen-reader-text" for="current-page-selector">Select Page</label>
						<input type="text" size="1" value="<?php echo $paged ?>" name="paged" title="Current page"
                               id="current-page-selector" class="current-page"> of <span
                            class="total-pages"><?php echo $maxPaged ?></span>
					</span>
					<a href="<?php echo $paged >= $maxPaged ? 'javascript:void(0);' : $uri . '&paged=' . ($paged + 1) ?>"
                       title="Go to the next page"
                       class="next-page<?php echo $paged >= $maxPaged ? ' disabled' : '' ?>">&rsaquo;</a>
					<a href="<?php echo $paged >= $maxPaged ? 'javascript:void(0);' : $uri . '&paged=' . $maxPaged ?>"
                       title="Go to the last page"
                       class="last-page<?php echo $paged >= $maxPaged ? ' disabled' : '' ?>">&raquo;</a>
				</span>
            </div>
        </div>
    </form>

    <script>
        jQuery(document).ready(function () {
            jQuery(document).on('click', '#doaction', function () {
                var action = jQuery(this).prev().val();

                if (action === 'edit') {
                    if (confirm('Continue to update notification?'))
                        window.location.href = '<?php echo $uri ?>&action=update&id=' + jQuery('#selected-row:checked').val();
                } else if (action === 'delete') {
                    if (confirm('You are about to delete selected row! Continue?'))
                        window.location.href = '<?php echo $uri ?>&action=delete&id=' + jQuery('#selected-row:checked').val() + '&_wpnonce=<?php echo wp_create_nonce('delete_shops2apps_pns'); ?>';
                }
            });
        });
    </script>
</div>