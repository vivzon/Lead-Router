# LeadRouter – WordPress Plugin

**Version**: 1.0  
**Author**: Sr. Vivek Raj  
**Plugin URI**: [https://vivzon.in/plugins/lead-router/index.html](https://vivzon.in/plugins/lead-router/index.html)  
**License**: GPLv2 or later  
**Requires at least**: WordPress 5.0  
**Tested up to**: 6.5

---

## 📌 Description

**LeadRouter** is a lightweight and powerful WordPress plugin that routes form submissions from:

- Contact Form 7  
- Elementor Pro Forms  
- Custom forms (via PHP)

...directly into the **Vivzon Business CRM**.

No need for third-party integrations or complex configurations. Just install, activate, and add your CRM token.

---

## 🚀 Features

- 🔒 Secure API submission using your CRM token
- ⚡ Fast integration with no overhead
- 🔧 Compatible with popular form plugins
- 🌐 Sends form data (name, email, phone, message, etc.) + your site URL
- 🛠️ Settings page for easy CRM token setup
- 📄 Custom form support (via function call)

---

## 📥 Installation

1. Upload the plugin to your WordPress site (`/wp-content/plugins/lead-router`)
2. Activate the plugin via the Plugins page.
3. Navigate to `Settings > LeadRouter Settings`
4. Enter your **Vivzon CRM Token**
5. Save changes.

---

## ✉️ Form Integrations

### ✅ Contact Form 7

No configuration needed! Just ensure your form includes fields with these names:

- `your-name`
- `your-email`
- `your-phone`
- `your-subject`
- `your-message`

Upon form submission, data is auto-sent to Vivzon CRM.

---

### ✅ Elementor Pro Forms

Ensure your form fields are named:

- `name`
- `email`
- `phone`
- `subject`
- `message`

Submissions will automatically route to your CRM.

---

### ✅ Custom Forms

You can also send data using the `vivzon_crm_send_to_api()` function.

```php
vivzon_crm_send_to_api([
    'name'    => 'John Doe',
    'email'   => 'john@example.com',
    'mob'     => '9876543210',
    'subject' => 'Custom Form Submission',
    'message' => 'Hello, this came from my custom form.',
    'website' => home_url()
]);
