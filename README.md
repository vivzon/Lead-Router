# LeadRouter – WordPress Plugin
**Version:** 1.2  
**Author:** Sr. Vivek Raj  
**Plugin URI:** [https://vivzon.in/plugins/lead-router/index.html](https://vivzon.in/plugins/lead-router/index.html)  
**License:** GPLv2 or later  
**Requires at least:** WordPress 5.0  
**Tested up to:** 6.5  

## 📌 Description
LeadRouter is a lightweight and powerful WordPress plugin designed to bridge the gap between your website and your sales team. It automatically routes form submissions from popular builders directly into the **Vivzon Business CRM**.

**New in 1.2:** LeadRouter now includes a customizable **WhatsApp Chat Widget**, allowing customers to reach out to you instantly via a floating chat button on your website.

---

## 🚀 Features
- **CRM Integration:** Securely sync leads from CF7 and Elementor using your unique CRM token.
- **WhatsApp Chat Widget:** A floating, high-visibility button with a "pulse" animation to increase engagement.
- **Smart Mapping:** Automatically detects and maps field names like Name, Email, Phone, Company, and Message.
- **B2B Ready:** Captures "Company/Organization" fields specifically for business leads.
- **Customizable Widget:** Control your WhatsApp number, default message, and button position (Bottom-Left or Bottom-Right).
- **Fast & Lightweight:** Optimized SVGs and minimal CSS to ensure your site remains lightning fast.
- **Robust Logging:** Built-in error logging to help debug API connectivity issues.

---

## 🛠️ Installation
1. Upload the `lead-router` folder to your site's `/wp-content/plugins/` directory.
2. Activate the plugin through the **'Plugins'** menu in WordPress.
3. Navigate to **Settings > LeadRouter**.
4. **CRM Setup:** Enter your Vivzon CRM Token.
5. **WhatsApp Setup:** Enable the widget, enter your phone number (with country code), and choose your preferred position.
6. Click **Save Changes**.

---

## ✉️ Form Integrations

### ✅ Contact Form 7
The plugin automatically detects the following field names:
- **Name:** `your-name`, `name`, `firstname` + `lastname`.
- **Email:** `your-email`, `email`.
- **Phone:** `your-phone`, `phone`, `mobile`.
- **Company:** `your-company`, `company`, `organization`.
- **Subject:** `your-subject`, `subject`.
- **Message:** `your-message`, `message`, `enquiry`.

### ✅ Elementor Pro Forms
Ensure your form field **IDs** match these common standards:
- **Name:** `name`, `first_name`, `last_name`.
- **Email:** `email`, `your-email`.
- **Phone:** `phone`, `mobile`, `contact`.
- **Company:** `company`, `company_name`, `organization`.
- **Subject:** `subject`, `topic`.
- **Message:** `message`, `comments`.

### ✅ Custom Forms (Developer Use)
Trigger a CRM lead manually from your custom PHP code:
```php
if (function_exists('vivzon_crm_send_to_api')) {
    vivzon_crm_send_to_api([
        'name'    => 'John Doe',
        'email'   => 'john@example.com',
        'mob'     => '919876543210',
        'company' => 'ACME Corp',
        'message' => 'Hello from custom code!',
        'website' => home_url()
    ]);
}
```

---

## 📜 Changelog

### 1.2 (Current)
- **New Feature:** Added a floating **WhatsApp Chat Widget** with customizable settings.
- **Customization:** Added options to set WhatsApp Number, Pre-filled Message, and Toggle Position (Left/Right).
- **UI Enhancement:** Added a "Pulse" animation to the WhatsApp icon and optimized SVG for better display across all themes.
- **B2B Update:** Added support for capturing the **'Company'** field across all form types.
- **Security:** Enhanced data sanitization and added `z-index` fixes to ensure the widget stays on top of all page elements.

### 1.1
- Initial public release.
- Support for Contact Form 7 and Elementor Pro Forms.
- Admin settings page for CRM token management.

---

## 📖 Documentation
For complete setup instructions and API details, please visit the [Installation & Configuration Guide](https://vivzon.in/plugins/lead-router/documentation.html).
