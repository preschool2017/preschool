<?php

/**
 * Content Restriction
 *
 * @since 2.4
 */
class WPUF_Content_Restriction {

    public function __construct() {
        // admin settings
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_post_meta' ), 10, 2 );

        // content restriction
        add_action( 'the_content', array( $this, 'the_content' ) );
        add_shortcode( 'wpuf_restrict', array( $this, 'shortcode_filter' ) );
    }

    /**
     * Meta box for all Post types
     *
     * Registers a meta box in public post types for
     * content restriction settings
     *
     * @return void
     */
    function add_meta_boxes() {
        $post_types = get_post_types( array('public' => true) );

        foreach ($post_types as $post_type) {
            add_meta_box( 'wpuf-content-restriction', __( 'WPUF Content Restriction', 'wpuf' ), array( $this, 'restriction_form' ), $post_type, 'normal', 'high' );
        }
    }

    public function restriction_form( $post ) {
        global $post;

        $display_to             = get_post_meta( $post->ID, '_wpuf_res_display', true );
        $selected_plans         = get_post_meta( $post->ID, '_wpuf_res_subscription', true );

        $display_to             = !empty( $display_to ) ? $display_to : 'all';
        $subscriptions          = WPUF_Subscription::init()->get_subscriptions();
        $selected_subscriptions = is_array( $selected_plans ) ? $selected_plans : array();

        // var_dump( $selected_subscriptions );
        // var_dump( $display_to );
        ?>

        <table class="form-table" id="wpuf-content-restriction-table">
            <tbody>
                <tr>
                    <th><?php _e( 'Display to', 'wpuf' ); ?></th>
                    <td>
                        <label><input type="radio" name="_wpuf_res_display" value="all" <?php checked( $display_to, 'all' ); ?>><?php _e( 'Everyone', 'wpuf' ); ?></label>&nbsp;
                        <label><input type="radio" name="_wpuf_res_display" value="loggedin" <?php checked( $display_to, 'loggedin' ); ?>><?php _e( 'Logged in users only', 'wpuf' ); ?></label>&nbsp;
                        <label><input type="radio" name="_wpuf_res_display" value="subscription" <?php checked( $display_to, 'subscription' ); ?>><?php _e( 'Subscription users only', 'wpuf' ); ?></label>
                    </td>
                </tr>

                <tr class="show-if-wpuf-res-subscription">
                    <th><?php _e( 'Subscription Plans', 'wpuf' ); ?></th>
                    <td>
                    <?php
                    if ( $subscriptions ) {
                        foreach ($subscriptions as $pack) {
                            ?>
                            <label>
                                <input type="checkbox" name="_wpuf_res_subscription[]" <?php checked( in_array( $pack->ID, $selected_subscriptions ) ); ?> value="<?php echo $pack->ID; ?>"><?php echo $pack->post_title; ?>
                            </label>&nbsp;
                            <?php
                        }

                        printf( '<p class="description">%s</p>', __( 'Members subscribed to these subscription plans will be able to view this page.', 'wpuf' ) );
                    } else {
                        _e( 'No subscription plan found.', 'wpuf' );
                    }
                    ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="_wpuf_res_nonce" id="_wpuf_res_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

        <script type="text/javascript">
            jQuery(function($){

                $('input[name="_wpuf_res_display"][type=radio]').change(function() {
                    var radio = $(this).val();

                    // console.log(radio);

                    if ( radio === 'subscription' ) {
                        $('.show-if-wpuf-res-subscription').show();
                    } else {
                        $('.show-if-wpuf-res-subscription').hide();
                    }
                }).filter(':checked').trigger('change');

            });
        </script>
        <?php
    }

    /**
     * Save the restriction settings
     *
     * @param  int $post_id
     * @param  WP_Post $post
     *
     * @return void
     */
    public function save_post_meta( $post_id, $post ) {
        // check the nonce
        if ( !isset( $_POST['_wpuf_res_nonce'] ) || !wp_verify_nonce( $_POST['_wpuf_res_nonce'], plugin_basename( __FILE__ ) ) ) {
            return;
        }

        // post type capability checking
        $post_type = get_post_type_object( $post->post_type );

        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
            return;
        }

        $display_to    = in_array( $_POST['_wpuf_res_display'], array( 'all', 'loggedin', 'subscription' ) ) ? $_POST['_wpuf_res_display'] : 'all';
        $subscriptions = is_array( $_POST['_wpuf_res_subscription'] ) ? array_map( 'intval', $_POST['_wpuf_res_subscription'] ) : array();

        update_post_meta( $post_id, '_wpuf_res_display', $display_to );

        if ( 'subscription' == $display_to ) {
            update_post_meta( $post_id, '_wpuf_res_subscription', $subscriptions );
        } else {
            delete_post_meta( $post_id, '_wpuf_res_subscription' );
        }
    }

    /**
     * Get content restriction error messages
     *
     * @return array
     */
    public function get_restriction_errors() {
        return array(
            'login'        => sprintf( __( 'You must be %s to view the content.', 'wpuf' ), sprintf( '<a href="%s">%s</a>', wp_login_url( get_permalink( get_the_ID() ) ), __( 'logged in', 'wpuf' ) ) ),
            'sub_limit'    => __( 'This content is restricted for your subscription package.', 'wpuf' ),
            'invalid_pack' => __( 'You don\'t have a valid subscription package', 'wpuf' ),
            'expired'      => __( 'Your subscription pack is invalid or expired.', 'wpuf' ),
            'not_allowed'  => __( 'Your subscription pack is not allowed to view this content', 'wpuf' ),
            'role'         => __( 'This content is restricted for your user role', 'wpuf' )
        );
    }

    /**
     * Post content restriction
     *
     * @param  string $content
     *
     * @return string
     */
    public function the_content( $content ) {
        global $post;

        $display_to = get_post_meta( $post->ID, '_wpuf_res_display', true );

        // no restriction found
        if ( !in_array( $display_to, array( 'loggedin', 'subscription' ) ) ) {
            return $content;
        }

        $allowed_packs = get_post_meta( $post->ID, '_wpuf_res_subscription', true );

        return $this->content_filter( $display_to, $content, $allowed_packs );
    }

    /**
     * Shortcode support for content restriction
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function shortcode_filter( $atts, $content = '' ) {
        $atts = shortcode_atts( array(
            'type'     => 'loggedin',
            'pack_ids' => '',
            'role'     => ''
        ), $atts, 'wpuf_restrict' );

        if ( in_array( $atts['type'], array( 'loggedin', 'subscription' ) ) ) {
            $sub_packs = ( $atts['type'] == 'subscription' ) ? array_map( 'intval', explode(',', $atts['pack_ids'] ) ) : array();

            return $this->content_filter( $atts['type'], $content, $sub_packs );
        }

        if ( 'role' == $atts['type'] ) {

            $errors = $this->get_restriction_errors();

            if ( !is_user_logged_in() ) {
                return $this->wrap_error( $errors['login'] );
            }

            if ( !current_user_can( $atts['role'] ) ) {
                return $this->wrap_error( $errors['role'] );
            }
        }

        return $content;
    }

    /**
     * Filter the content and put restriction based on the type
     *
     * @param  string $type
     * @param  string $content
     * @param  array  $allowed_packs
     *
     * @return string
     */
    public function content_filter( $type, $content, $allowed_packs = array() ) {

        $errors = $this->get_restriction_errors();

        if ( 'loggedin' == $type && !is_user_logged_in() ) {
            return $this->wrap_error( $errors['login'] );
        }

        if ( 'subscription' == $type ) {

            if ( !is_user_logged_in() ) {
                return $this->wrap_error( $errors['login'] );
            }

            // respect the admins?
            if ( current_user_can( 'manage_options' ) ) {
                return $content;
            }

            $sub_pack = WPUF_Subscription::get_user_pack( get_current_user_id() );

            if ( !$sub_pack ) {
                return $this->wrap_error( $errors['sub_limit'] );
            }

            if ( ! $this->is_valid_subscription( $sub_pack ) ) {
                return $this->wrap_error( $errors['sub_limit'] );
            }

            $pack_id = is_array( $sub_pack ) ? intval( $sub_pack['pack_id'] ) : 0;

            if ( ! in_array( $pack_id, $allowed_packs ) ) {
                return $this->wrap_error( $errors['sub_limit'] );
            }
        }

        return $content;
    }

    /**
     * Check if the subscription is valid
     *
     * @param  array  $package
     *
     * @return boolean
     */
    public function is_valid_subscription( $package ) {
        $pack_id = is_array( $package ) ? intval( $package['pack_id'] ) : 0;

        if ( !$pack_id ) {
            return false;
        }

        // check expiration
        $expire = isset( $package['expire'] ) ? $package['expire'] : 0;

        if ( strtolower( $expire ) == 'unlimited' || empty( $expire ) ) {
            $has_expired = false;
        } else if ( ( strtotime( date( 'Y-m-d', strtotime( $expire ) ) ) >= strtotime( date( 'Y-m-d', time() ) ) ) ) {
            $has_expired = false;
        } else {
            $has_expired = true;
        }

        if ( $has_expired ) {
            return false;
        }

        return true;
    }

    /**
     * Print restriction message
     *
     * @param  string $text
     *
     * @return string
     */
    public function wrap_error( $text ) {
        return sprintf( '<div class="wpuf-info wpuf-restrict-message">%s</div>', $text );
    }

}
