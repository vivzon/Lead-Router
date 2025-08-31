<?php
/*
Plugin Name: LeadRouter
Plugin URI: https://vivzon.in//plugins/lead-router/index.html
Description: Route leads from Contact Form 7, Elementor, and custom forms directly to your Vivzon Browser CRM system with ease.
Version: 1.0
Author: Sr. Vivek Raj
Author URI: https://vivzon.in
*/

// Core function to send data to Vivzon Browser CRM API
function vivzon_crm_send_to_api($data) {
    $token = get_option('vivzon_crm_token');
    if (empty($token)) return;

    $url = "https://business.vivzon.in/api/v1/save-lead/{$token}";

    $response = wp_remote_post($url, [
        'method' => 'POST',
        'body' => $data, // form-urlencoded
    ]);
}

// Admin Menu and Settings
add_action('admin_menu', 'vivzon_crm_menu');
add_action('admin_init', 'vivzon_crm_settings');

function vivzon_crm_menu() {
    add_options_page('LeadRouter Settings', 'LeadRouter Settings', 'manage_options', 'leadrouter-settings', 'vivzon_crm_settings_page');
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
                    <th>CRM Token</th>
                    <td><input type="text" name="vivzon_crm_token" value="<?php echo esc_attr(get_option('vivzon_crm_token')); ?>" size="40" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <hr>

        <h3>📖 Documentation</h3>
        <p>
            For complete setup instructions and form integration details, please refer to the
            <a href="https://vivzon.in//plugins/lead-router/documentation.html" target="_blank">
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

    vivzon_crm_send_to_api([
		'name'    => $data['your-name'] 
						?? $data['name'] 
						?? trim(($data['your-firstname'] ?? $data['firstname'] ?? $data['first_name'] ?? '') 
							. ' ' . 
							($data['your-lastname'] ?? $data['lastname'] ?? $data['last_name'] ?? '')),

		'email'   => $data['your-email'] 
						?? $data['email'] 
						?? $data['user_email'] 
						?? '',

		'mob'     => $data['your-phone'] 
						?? $data['phone'] 
						?? $data['your-mob'] 
						?? $data['your-mobile'] 
						?? $data['mobile'] 
						?? $data['contact'] 
						?? '',

		'subject' => $data['your-subject'] 
						?? $data['subject'] 
						?? '',

		'message' => $data['your-message'] 
						?? $data['message'] 
						?? $data['comments'] 
						?? $data['enquiry'] 
						?? '',

		'website' => home_url()
	]);
}

// Elementor Pro Forms Integration
add_action('elementor_pro/forms/new_record', 'vivzon_crm_elementor_submission', 10, 2);
function vivzon_crm_elementor_submission($record, $handler) {
    $fields = $record->get('fields');

    vivzon_crm_send_to_api([
		// NAME: use 'name', or combine 'firstname' + 'lastname'
		'name'    => isset($fields['name']['value']) && !empty(trim($fields['name']['value']))
						? trim($fields['name']['value'])
						: trim(
							($fields['firstname']['value'] ?? $fields['first_name']['value'] ?? $fields['your-firstname']['value'] ?? '') 
							. ' ' . 
							($fields['lastname']['value'] ?? $fields['last_name']['value'] ?? $fields['your-lastname']['value'] ?? '')
						),

		// EMAIL: assume multiple variations
		'email'   => strtolower(trim(
						$fields['email']['value'] 
						?? $fields['your-email']['value'] 
						?? $fields['user_email']['value'] 
						?? ''
					)),

		// PHONE: assume multiple variations and normalize
		'mob'     => preg_replace('/\D+/', '', (
						$fields['phone']['value'] 
						?? $fields['your-phone']['value'] 
						?? $fields['mobile']['value'] 
						?? $fields['your-mobile']['value'] 
						?? $fields['your-mob']['value'] 
						?? $fields['contact']['value'] 
						?? ''
					)),

		// SUBJECT: assume variations
		'subject' => trim(
						$fields['subject']['value'] 
						?? $fields['your-subject']['value'] 
						?? $fields['topic']['value'] 
						?? ''
					),

		// MESSAGE: assume multiple message/enquiry fields, strip HTML
		'message' => strip_tags(trim(
						$fields['message']['value'] 
						?? $fields['your-message']['value'] 
						?? $fields['comments']['value'] 
						?? $fields['enquiry']['value'] 
						?? ''
					)),

		// WEBSITE: always your site
		'website' => home_url()
	]);
}

// Optional: For custom or shortcode-based form submissions
// Usage: Call vivzon_crm_send_to_api($data) where appropriate
