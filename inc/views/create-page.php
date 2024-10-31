<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap">
    <h2>
        Create Push Notification Scheduler Page
        <a class="add-new-h2" href="<?php echo $uri; ?>">Back to List</a>
    </h2>

    <p>Please take a note, <b style="color: red;">same page title will update the page not create new one</b></p>

    <form action="<?php echo $uri; ?>" method="post">
        <input id="action" name="action" type="hidden" value="save-page">
        <input id="publisher-id" name="publisher-id" type="hidden" value="tashlik">
        <?php wp_nonce_field('create_shops2apps_pns_page'); ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" style="position: relative;">
                    <table class="form-table">
                        <tbody>
                        <tr class="form-field">
                            <th><label for="page-title">Page Title <span class="description">(required)</span></label>
                            </th>
                            <td><input id="page-title" name="page-title" type="text" placeholder="Page Title" autofocus
                                       required></td>
                        </tr>
                        <tr class="form-field">
                            <th><label for="username">Username <span class="description">(required)</span></label></th>
                            <td><input id="username" name="username" type="text" placeholder="Username" required></td>
                        </tr>
                        <tr class="form-field">
                            <th><label for="password">Password <span class="description">(required)</span></label></th>
                            <td><input id="password" name="password" type="text" placeholder="Password" required></td>
                        </tr>
                        <tr class="form-field">
                            <th><label for="app-id">App Id <span class="description">(required)</span></label></th>
                            <td><input id="app-id" name="app-id" type="text" placeholder="App Id" required></td>
                        </tr>
                        <tr class="form-field">
                            <th><label for="device">Device</label></th>
                            <td>
                                <select id="device" name="device">
                                    <option value="Android">Android</option>
                                    <option value="iOS">iOS</option>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <input type="submit" value="Create Page" class="button button-primary" id="save" name="save">
            </div>
            <br class="clear">
        </div>
    </form>
</div>
