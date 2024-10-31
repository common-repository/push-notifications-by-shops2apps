<style>
    .svrlic {
        margin: 0 auto;
        width: 1000px;
    }

    .svrlic-logo {
        margin-bottom: 50px;
    }

    .svrlic h2 {
        float: right;
        font-weight: 600;
        line-height: 70px;
        padding-right: 0;
        text-transform: uppercase;
    }

    .svrlic .svrlic-message {
        background-color: #ffffff;
        border-left: 4px solid #00a0d2;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        clear: left;
        padding: 12px;
    }

    .svrlic .svrlic-message-success {
        background-color: #ffffff;
        border-left: 4px solid #00C800;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        padding: 12px;
    }

    .svrlic .svrlic-form {
        background-color: #ffffff;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        display: inline-block;
        margin: 0 0 15px;
        padding: 12px;
        width: 500px;
    }

    .svrlic .svrlic-form input {
        font-size: small;
        padding: 10px;
        width: 100%;
    }

    .svrlic .svrlic-form input[type="submit"] {
        width: auto;
    }

    /* CSS | steps instruction */
    .svrlic-steps {
        display: inline-block;
        float: right;
        vertical-align: top;
        width: 400px;
    }

    .svrlic-steps-l {
        float: left;
        width: 80%;
    }

    .svrlic-steps-r {
        color: #00b2b4;
        float: right;
        text-align: center;
        width: 19%;
    }

    .svrlic-steps-l ol {
        list-style: outside none none;
        margin-left: 15px;
    }

    .svrlic-steps-l li {
        counter-increment: customlistcounter;
    }

    .svrlic-steps-l ol > li::before {
        content: counter(customlistcounter, decimal) " ";
        float: left;
        font-weight: bold;
        margin-left: -15px;
    }
</style>
<div class="wrap">
    <div class="svrlic">
        <div class="svrlic-logo">
            <h2>API-key Registration</h2>
            <a href="http://shops2apps.com" target="_blank"><img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/logo.png'); ?>" alt="shops2apps-logo"></a>
            <div>The best Done For You mobile solution! Transfer your store or site Into iOS, Android, Kindle, Windows Apps and Facebook Stores! Start and test our <a href="http://shops2apps.com" target="_blank">system for free</a>!</div>
        </div>

        <form class="svrlic-form" method="post">
            <p>
                <label for="apikey">
                    <b style="font-size: 120%;">API-KEY</b>
                    <input id="apikey" class="input" type="text" name="apikey" <?php echo intval(sanitize_text_field($_GET['s'])) == 0 ? 'required' : 'value="' . $APIkey . '" disabled' ?>>
                </label>
            </p>
            <p style="text-align: right;"><input type="submit" value="Register" class="button button-primary button-large" id="register" name="register" <?php echo intval(sanitize_text_field($_GET['s'])) == 0 ? '' : 'disabled' ?>></p>
        </form>

        <div class="svrlic-steps">
            <div class="svrlic-steps-l">
                <b>Instructions:</b>
                <ol>
                    <li><a href="http://shops2apps.com" target="_blank">Select your plan at Shop2Apps</a> and get your API</li>
                    <li>Enter given API-KEY</li>
                    <li>Select "ADD NEW" for sending push notifications by yourself. Or select "CREATE PAGE" for creating page where your clients can send push notificaations.</li>
                </ol>
            </div>

            <div class="svrlic-steps-r">
                <i class="fa fa-asterisk fa-5x"></i>
            </div>
        </div>

        <p class="<?php echo intval(sanitize_text_field($_GET['s'])) == 0 ? 'svrlic-message' : 'svrlic-message-success' ?>">
            <?php echo intval(sanitize_text_field($_GET['s'])) == 0 ? '<span>For more information, see our video tutorial <a target="_blank" href="http://shops2apps.com/faq">here</a> and for any questions contact us directly.</span>' : 'Congratulation on your plugin activation. Reload this page to continue.' ?><br>
        </p>
    </div>
</div>