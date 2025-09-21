# LeadRouter – WordPress Plugin

**Version**: 1.1
**Author**: Sr. Vivek Raj  
**Plugin URI**: [https://vivzon.in/plugins/lead-router/index.html](https://vivzon.in/plugins/lead-router/index.html)  
**License**: GPLv2 or later  
**Requires at least**: WordPress 5.0  
**Tested up to**: 6.5


## 📌 Description

**LeadRouter** is a lightweight and powerful WordPress plugin that routes form submissions from:

-   Contact Form 7
-   Elementor Pro Forms
-   Custom forms (via PHP)

...directly into the **Vivzon Business CRM**.

No need for third-party integrations or complex configurations. Just install, activate, and add your CRM token.


## 🚀 Features

-   🔒 Secure API submission using your CRM token.
-   ⚡ Fast integration with no overhead.
-   🔧 **Smart Mapping:** Automatically detects and maps common field names from your forms.
-   🏢 **B2B Ready:** Captures essential lead data including `name`, `email`, `phone`, `company`, `subject`, and `message`.
-   📝 **Robust Logging:** Logs API errors to your server's error log for easy debugging.
-   🛠️ Simple settings page for easy CRM token setup.
-   📄 Custom form support via a simple PHP function call.

---

## 📥 Installation

1.  Upload the `lead-router` folder to your WordPress site's `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Navigate to `Settings > LeadRouter`.
4.  Enter your **Vivzon CRM Token** and save changes.
5.  That's it! Your forms are now connected.

---

## ✉️ Form Integrations

### ✅ Contact Form 7 - Zero Config!

The plugin automatically detects common field names. For best results, use standard names like:

-   **Name**: `your-name`, `name`, or a combination of `firstname` and `lastname`.
-   **Email**: `your-email`, `email`.
-   **Phone**: `your-phone`, `phone`, `mobile`.
-   **Company**: `your-company`, `company`, `organization`.
-   **Subject**: `your-subject`, `subject`.
-   **Message**: `your-message`, `message`, `enquiry`.

Upon submission, data is automatically sent to your Vivzon CRM.

---

### ✅ Elementor Pro Forms - Zero Config!

Ensure your form fields have an **ID** that matches common standards. The plugin will automatically find them.

-   **Name**: `name`, or a combination of `firstname` and `lastname`.
-   **Email**: `email`, `your-email`.
-   **Phone**: `phone`, `mobile`, `contact`.
-   **Company**: `company`, `organization`, `company_name`.
-   **Subject**: `subject`, `topic`.
-   **Message**: `message`, `comments`, `enquiry`.

Submissions will automatically route to your CRM.

---

### ✅ Custom Forms

You can also send data from any custom PHP code using the `vivzon_crm_send_to_api()` function.

```php
if (function_exists('vivzon_crm_send_to_api')) {
    vivzon_crm_send_to_api([
        'name'    => 'John Doe',
        'email'   => 'john@example.com',
        'mob'     => '9876543210',
        'company' => 'ACME Corporation',
        'subject' => 'Custom Form Submission',
        'message' => 'Hello, this came from my custom form.',
        'website' => home_url()
    ]);
}
```

---

## 📜 Changelog

### 1.2
*   **New**: Added support for capturing the 'company' field for B2B leads.
*   **Improvement**: Added robust error handling and logging for API requests to help diagnose connection issues.
*   **Improvement**: Expanded the automatic field mapping to support more common field names out-of-the-box.
*   **Docs**: Updated README with more detailed integration instructions and features.

### 1.1
*   Initial public release.
*   Support for Contact Form 7 and Elementor Pro Forms.
*   Admin settings page for CRM token management.
