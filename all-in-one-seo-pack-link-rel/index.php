<?php
 /**
 * Plugin Name:       Set Link Rel
 * Plugin URI:        
 * Description:       Provides option to set alternate link 
 * Version:           1.0.0
 * Requires at least: 5.3
 * Author:            Adeel Sarfraz
 * Author URI:        https://www.adeelsarfaraz.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       aioseolr
 */
 
register_activation_hook( __FILE__, 'aioseolr_link_rel_activate' );
register_deactivation_hook( __FILE__, 'aioseolr_link_rel_deactivate' );

function aioseolr_link_rel_deactivate() {
//	delete_option('aioseolr_link_rel');
}
 
function aioseolr_link_rel_activate() {
//	add_option('aioseolr_link_rel', '');	
} 

add_action('wp_head', 'aioseolr_link_rel_head');

function aioseolr_link_rel_head() {
	if (is_front_page()) {
		$link_rel = get_option('aioseolr_options');
		echo '<link rel="alternate" href="'.get_home_url().'" hreflang="'.$link_rel['aioseolr_field_link_rel'].'" />';
	}	
}

/**
 * custom option and settings
 */
function aioseolr_settings_init() {
    // Register a new setting for "aioseolr" page.
    register_setting( 'aioseolr', 'aioseolr_options' );
 
    // Register a new section in the "aioseolr" page.
    add_settings_section(
        'aioseolr_section_developers',
        __( 'Settings', 'aioseolr' ), 'aioseolr_section_developers_callback',
        'aioseolr'
    );
 
    // Register a new field in the "aioseolr_section_developers" section, inside the "aioseolr" page.
    add_settings_field(
        'aioseolr_field_link_rel', // As of WP 4.6 this value is used only internally.
                               // Use $args' label_for to populate the id inside the callback.
            __( 'Link Rel', 'aioseolr' ),
        'aioseolr_field_link_rel_cb',
        'aioseolr',
        'aioseolr_section_developers',
        array(
            'label_for'         => 'aioseolr_field_link_rel',
            'class'             => 'aioseolr_row',
            'aioseolr_custom_data' => 'custom',
        )
    );
}
 
/**
 * Register our aioseolr_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'aioseolr_settings_init' );
 
 
/**
 * Custom option and settings:
 *  - callback functions
 */
 
 
/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function aioseolr_section_developers_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( '', 'aioseolr' ); ?></p>
    <?php
}
 
/**
 * Field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function aioseolr_field_link_rel_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'aioseolr_options' );
    ?>
    <input type="text" name="aioseolr_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>" />	
    <p class="description">
        <?php esc_html_e( 'Enter the link alternate URL here', 'aioseolr' ); ?>
    </p>
    <?php
}
 
/**
 * Add the top level menu page.
 */
function aioseolr_options_page() {
    add_menu_page(
        'SEO Extended Options',
        'Link Rel Options',
        'manage_options',
        'aioseolr',
        'aioseolr_options_page_html'
    );
}
 
 
/**
 * Register our aioseolr_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'aioseolr_options_page' );
 
 
/**
 * Top level menu callback function
 */
function aioseolr_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    // add error/update messages
    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'aioseolr_messages', 'aioseolr_message', __( 'Settings Saved', 'aioseolr' ), 'updated' );
    }
    // show error/update messages
    settings_errors( 'aioseolr_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "aioseolr"
            settings_fields( 'aioseolr' );
            // output setting sections and their fields
            // (sections are registered for "aioseolr", each field is registered to a specific section)
            do_settings_sections( 'aioseolr' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}
