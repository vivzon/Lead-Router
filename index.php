<?php
/*
Plugin Name: LeadRouter
Plugin URI: https://vivzon.in/plugins/lead-router/index.html
Description: Route leads from Contact Form 7, Elementor, and custom forms directly to your Vivzon Browser CRM system with ease.
Version: 1.1
Author: Sr. Vivek Raj
Author URI: https://vivzon.in
*/

// Core function to send data to Vivzon Browser CRM API
function vivzon_crm_send_to_api($data) {
    $token = get_option('vivzon_crm_token');
    if (empty($token)) {
        error_log('LeadRouter Error: Attempted to send lead but CRM Token is not set in Settings > LeadRouter.');
        return;
    }

    $url = "https://business.vivzon.in/api/v1/save-lead/{$token}";

    $response = wp_remote_post($url, [
        'method'  => 'POST',
        'body'    => $data,
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        error_log("LeadRouter API WP_Error: " . $error_message);
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code >= 400) {
        $response_body = wp_remote_retrieve_body($response);
        error_log("LeadRouter API HTTP Error ({$response_code}): " . $response_body);
    }
}

// Admin Menu and Settings
add_action('admin_menu', 'vivzon_crm_menu');
add_action('admin_init', 'vivzon_crm_settings');

function vivzon_crm_menu() {
    add_options_page('LeadRouter Settings', 'LeadRouter', 'manage_options', 'leadrouter-settings', 'vivzon_crm_settings_page');
}

function vivzon_crm_settings() {
    register_setting('vivzon_crm_group', 'vivzon_crm_token');
}

function vivzon_crm_settings_page() {
    ?>
    <div class="wrap">
        <h2>LeadRouter Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('vivzon_crm_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="vivzon_crm_token">CRM Token</label></th>
                    <td><input type="text" id="vivzon_crm_token" name="vivzon_crm_token" value="<?php echo esc_attr(get_option('vivzon_crm_token')); ?>" size="40" />
                    <p class="description">Enter the API token provided by your Vivzon Browser CRM.</p></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr>
        <h3>📖 Documentation</h3>
        <p>
            For complete setup instructions and form integration details, please refer to the
            <a href="https://vivzon.in//plugins/lead-router/documentation.html" target="_blank" rel="noopener noreferrer">
                Installation & Configuration Guide
            </a>.
        </p>
    </div>
    <?php
}

// Contact Form 7 Integration
add_action('wpcf7_mail_sent', 'vivzon_crm_c7_submission');
function vivzon_crm_c7_submission($contact_form) {
    $submission = WPCF7_Submission::get_instance();
    if (!$submission) return;
    
    $data = $submission->get_posted_data();

    $payload = [
		'name'    => trim(($data['your-name'] ?? $data['name'] ?? '') . ' ' . ($data['your-lastname'] ?? $data['lastname'] ?? $data['last_name'] ?? ''))
						?: trim(($data['your-firstname'] ?? $data['firstname'] ?? $data['first_name'] ?? '') . ' ' . ($data['your-lastname'] ?? $data['lastname'] ?? $data['last_name'] ?? '')),
		'email'   => $data['your-email'] ?? $data['email'] ?? $data['user_email'] ?? '',
		'mob'     => $data['your-phone'] ?? $data['phone'] ?? $data['your-mob'] ?? $data['your-mobile'] ?? $data['mobile'] ?? $data['contact'] ?? '',
        'company' => $data['your-company'] ?? $data['company'] ?? $data['company_name'] ?? $data['organization'] ?? '', // <-- ADDED
		'subject' => $data['your-subject'] ?? $data['subject'] ?? '',
		'message' => $data['your-message'] ?? $data['message'] ?? $data['comments'] ?? $data['enquiry'] ?? '',
		'website' => home_url()
	];
    
    // Fallback for single name field if combined is empty
    if(empty(trim($payload['name']))) {
        $payload['name'] = $data['your-name'] ?? $data['name'] ?? '';
    }

    // Validate: Don't send if no contact info is present
    if (empty($payload['email']) && empty($payload['mob'])) {
        error_log('LeadRouter (CF7): Skipped submission, email and phone were empty.');
        return;
    }

    vivzon_crm_send_to_api($payload);
}

// Elementor Pro Forms Integration
add_action('elementor_pro/forms/new_record', 'vivzon_crm_elementor_submission', 10, 2);
function vivzon_crm_elementor_submission($record, $handler) {
    $fields = $record->get('fields');

    // Helper for easier access
    $get_field_value = function($keys) use ($fields) {
        foreach ((array)$keys as $key) {
            if (isset($fields[$key]['value']) && !empty($fields[$key]['value'])) {
                return $fields[$key]['value'];
            }
        }
        return '';
    };

    $first_name = $get_field_value(['firstname', 'first_name', 'your-firstname']);
    $last_name = $get_field_value(['lastname', 'last_name', 'your-lastname']);
    $full_name_combined = trim("{$first_name} {$last_name}");
    
    $payload = [
		'name'    => $get_field_value('name') ?: $full_name_combined,
		'email'   => strtolower(trim($get_field_value(['email', 'your-email', 'user_email']))),
		'mob'     => preg_replace('/\D+/', '', $get_field_value(['phone', 'your-phone', 'mobile', 'your-mobile', 'your-mob', 'contact'])),
        'company' => trim($get_field_value(['company', 'your-company', 'company_name', 'organization'])), // <-- ADDED
		'subject' => trim($get_field_value(['subject', 'your-subject', 'topic'])),
		'message' => strip_tags(trim($get_field_value(['message', 'your-message', 'comments', 'enquiry']))),
		'website' => home_url()
	];
    
    // Validate: Don't send if no contact info is present
    if (empty($payload['email']) && empty($payload['mob'])) {
        error_log('LeadRouter (Elementor): Skipped submission, email and phone were empty.');
        return;
    }
    
	vivzon_crm_send_to_api($payload);
}
