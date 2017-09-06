<?php
/*
  If you would like to edit this file, copy it to your current theme's directory and edit it there.
  WPUF will always look in your theme's directory first, before using this default template.
 */
?>
<div class="login" id="wpuf-login-form" style="width:70%;margin:0 auto;">
<div style="height: 10px;"></div>
    <?php
    $message = apply_filters( 'login_message', '' );
    if ( ! empty( $message ) ) {
        echo $message . "\n";
    }
    ?>

    <?php WPUF_Login::init()->show_errors(); ?>
    <?php WPUF_Login::init()->show_messages(); ?>

    <form name="loginform" class="wpuf-login-form" id="loginform" action="<?php echo $action_url; ?>" method="post">
        <p>
            <label style="color:#000000;" for="wpuf-user_login"><?php _e( 'Username', 'wpuf' ); ?></label>
            <input type="text" name="log" id="wpuf-user_login" class="input" value="" size="20" />
        </p>
        <p>
            <label style="color:#000000;" for="wpuf-user_pass"><?php _e( 'Password', 'wpuf' ); ?></label>
            <input type="password" name="pwd" id="wpuf-user_pass" class="input" value="" size="20" />
        </p>

        <?php do_action( 'login_form' ); ?>

        <p class="forgetmenot">
            <input name="rememberme" type="checkbox" id="wpuf-rememberme" value="forever" />
            <label style="color:#000000;font-size: 8px;" for="wpuf-rememberme"><?php esc_attr_e( 'Remember Me' ); ?></label>
        </p>

        <p class="submit">
            <input type="submit" name="wp-submit" id="wp-submit" style="background: #a054a4;" value="<?php esc_attr_e( 'Log In' ); ?>" />
            <input type="hidden" name="redirect_to" value="<?php echo WPUF_Login::get_posted_value( 'redirect_to' ); ?>" />
            <input type="hidden" name="wpuf_login" value="true" />
            <input type="hidden" name="action" value="login" />
            <input type="hidden" name="_wpnonce" id="_wpnonce" value="be84be29be">
            <input type="hidden" name="_wp_http_referer" value="/Personal-center/">
            <!-- <?php wp_nonce_field( 'wpuf_login_action' ); ?>  -->
        </p>
    </form>

    <?php echo WPUF_Login::init()->get_action_links( array( 'login' => false ) ); ?>
</div>
<div style="height:111px;"></div>