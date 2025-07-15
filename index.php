<?php
/*
Plugin Name: LeadSync for ESE CRM
Plugin URI: https://webbrella.com
Description: Seamlessly integrates Contact Form 7, Elementor Pro Forms, and custom forms with ESE CRM to send leads directly to your CRM system.
Description: Sends Contact Form 7, Elementor, and custom form data to ESE CRM API using token.
Version: 1.1.1
Author: Sr. Vivek Raj
Author URI: https://vivzon.in
*/

// Core function to send data to CRM API
function ese_crm_send_to_api($data) {
    $token = get_option('ese_crm_token');
    if (empty($token)) {
        //error_log('[ESE CRM] ERROR: Token not set.');
        return;
    }

    $url = "https://esecrm.com/api/v1/enquiry?token={$token}";

    $response = wp_remote_post($url, [
        'method' => 'POST',
        'body' => $data, // form-urlencoded (not JSON)
    ]);

    if (is_wp_error($response)) {
        //error_log('[ESE CRM] API Error: ' . $response->get_error_message());
    } else {
        //error_log('[ESE CRM] API Response Code: ' . wp_remote_retrieve_response_code($response));
        //error_log('[ESE CRM] API Response Body: ' . wp_remote_retrieve_body($response));
    }

    //error_log('[ESE CRM] Data Sent: ' . print_r($data, true));
}

// Admin Menu and Settings
add_action('admin_menu', 'ese_crm_menu');
add_action('admin_init', 'ese_crm_settings');

function ese_crm_menu() {
    add_options_page('LeadSync Settings', 'LeadSync Settings', 'manage_options', 'leadsync-settings', 'ese_crm_settings_page');
}

function ese_crm_settings() {
    register_setting('ese_crm_group', 'ese_crm_token');
}

function ese_crm_settings_page() {
    ?>
    <div class="wrap">
        <h2>LeadSync Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('ese_crm_group'); ?>
            <table class="form-table">
                <tr>
                    <th>CRM Token</th>
                    <td><input type="text" name="ese_crm_token" value="<?php echo esc_attr(get_option('ese_crm_token')); ?>" size="40" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <hr>

        <h3>📖 Documentation</h3>
        <p>
            For complete setup instructions and form integration details, please refer to the
            <a href="<?php echo plugins_url('installation.html', __FILE__); ?>" target="_blank">
                Installation & Configuration Guide
            </a>.
        </p>
    </div>
    <?php
}

// Contact Form 7 Integration
add_action('wpcf7_mail_sent', 'ese_crm_c7_submission');
function ese_crm_c7_submission($contact_form) {
    $submission = WPCF7_Submission::get_instance();
    if (!$submission) return;
    $data = $submission->get_posted_data();

    ese_crm_send_to_api([
        'name' => $data['your-name'] ?? '',
        'email' => $data['your-email'] ?? '',
        'mob' => $data['your-phone'] ?? '',
        'subject' => $data['your-subject'] ?? '',
        'message' => $data['your-message'] ?? '',
        'website' => home_url()
    ]);
}

// Elementor Pro Forms Integration
add_action('elementor_pro/forms/new_record', 'ese_crm_elementor_submission', 10, 2);
function ese_crm_elementor_submission($record, $handler) {
    $fields = $record->get('fields');

    ese_crm_send_to_api([
        'name' => $fields['name']['value'] ?? '',
        'email' => $fields['email']['value'] ?? '',
        'mob' => $fields['phone']['value'] ?? '',
        'subject' => $fields['subject']['value'] ?? '',
        'message' => $fields['message']['value'] ?? '',
        'website' => home_url()
    ]);
}

// Optional: For custom or shortcode-based form submissions
// Usage: Call ese_crm_send_to_api($data) where appropriate
