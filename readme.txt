
=== FormYar – Form Builder, Surveys & Polls ===
Contributors: dangoweb
Donate link: https://dangoweb.ir/
Tags: form builder, contact form, poll, survey, persian
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create and manage forms, surveys, and polls in WordPress with entry management, file uploads, Jalali date picker, and RTL support.

== Description ==

FormYar is a flexible form builder for WordPress that helps you create and manage forms without writing code.

Build contact forms, surveys, polls, and other types of forms directly from your WordPress dashboard. Customize form fields, configure notifications, manage submissions, and display your forms on any post or page using a simple shortcode.

FormYar combines an intuitive form-building interface with useful features such as file uploads, submission management, email notifications, and a built-in Jalali date picker.

Whether you are creating a simple contact form or collecting information through a survey, FormYar provides the essential tools to manage your forms and submissions.

**Key Features**

* **Visual Form Builder:** Create and configure forms directly from your WordPress dashboard.
* **Multiple Field Types:** Choose from Text, Textarea, Number, Email, Date, Jalali Date, Select, Radio, Checkbox, and File Upload fields.
* **Jalali Date Picker:** Allow visitors to select dates using the Persian calendar.
* **RTL Support:** Designed to support right-to-left languages.
* **File Uploads:** Let visitors attach files through upload fields with preview support.
* **File Manager:** Manage uploaded files from your WordPress dashboard.
* **Email Notifications:** Receive email notifications when visitors submit a form.
* **Auto-Reply:** Send automatic email responses to form submitters.
* **Submission Limits:** Control submissions based on user, IP address, or cookie.
* **Entry Management:** View and manage form submissions from the dashboard.
* **Search and CSV Export:** Search submitted entries and export them as CSV files.
* **Google reCAPTCHA v3:** Add an extra layer of spam protection.
* **Shortcode Support:** Easily embed forms in posts and pages.
* **Responsive Forms:** Forms adapt to different screen sizes.
* **Translation Ready:** Ready for localization and translation.

**Form Management**

* Create and manage multiple forms.
* Duplicate existing forms.
* Import and export forms in bulk.
* Start with ready-made form templates.
* Configure form settings and email notifications.
* Manage submissions from a dedicated dashboard.

**Available Templates**

* Contact Form
* Survey Form
* Employment Form
* Reservation Form

**Additional Features**

* Complete Form template with 10 predefined fields.
* Modern card-based form layout.
* Frontend dark mode.
* Form progress bar.
* Field completion indicators.
* Toast notifications.
* Custom form logo.
* Floating help widget.
* Accessibility improvements.

**Free Version Limits**

The free version allows you to create unlimited forms, with a maximum of 15 fields per form.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/formyar` directory, or install the plugin through the WordPress Plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to FormYar in your WordPress dashboard.
4. Create a new form and add the fields you need.
5. Configure your form settings and email notifications.
6. Copy the generated shortcode and place it in any post or page.

== Frequently Asked Questions ==

= How can I display a form on my website? =

After creating your form, copy its shortcode and paste it into any post or page.

Example:

`[fmyar_form id="123"]`

Replace `123` with your form ID.

= Can I create surveys and polls? =

Yes. You can create different types of forms, including surveys and polls, using the available field types.

= Does FormYar support the Persian calendar? =

Yes. FormYar includes a Jalali date picker that allows visitors to select dates using the Persian calendar.

= Does FormYar support right-to-left languages? =

FormYar includes RTL support for right-to-left languages.

= Can visitors upload files? =

Yes. You can add a File Upload field to your forms and allow visitors to attach files.

= Where are form submissions stored? =

Form submissions are stored in your WordPress database and can be managed from your WordPress dashboard.

= Can I export form submissions? =

Yes. You can search submitted entries and export them as CSV files.

= Can I limit form submissions? =

Yes. FormYar provides submission limits based on user, IP address, or cookie.

= Does FormYar support spam protection? =

Yes. You can optionally enable Google reCAPTCHA v3 by configuring your site and secret keys in the plugin settings.

== Privacy ==

FormYar stores form submissions in your WordPress database. Form submission data is not sent to a FormYar-operated external server.

If Google reCAPTCHA v3 is enabled, the plugin loads Google's reCAPTCHA service on pages containing forms. When a form is submitted, the reCAPTCHA token and relevant request information are sent to Google for verification.

Uploaded files are protected using server-side access restrictions and secure download tokens.

Please review your website's privacy policy and configure third-party services according to your privacy requirements.

== Screenshots ==

1. Forms list and management.
2. Form builder interface.
3. Field settings and configuration.
4. Form settings.
5. Form entries and submissions.
6. Frontend form display.
7. File manager.
8. Plugin settings.

== Changelog ==

= 1.1.0 =
* Added complete form template with 10 predefined fields.
* Added bulk form export and import.
* Added form duplication.
* Added Contact, Survey, Employment, and Reservation templates.
* Added floating help widget.
* Added custom form logo.
* Added toast notifications.
* Added form progress bar.
* Added field completion indicators.
* Added frontend dark mode.
* Improved form layout with modern card design.
* Improved accessibility.
* Improved JavaScript localization.
* Added admin notices and welcome/about page.
* Fixed various issues and improved plugin stability.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.1.0 =
This update introduces new form templates, bulk import/export, form duplication, and several usability improvements.

== Credits ==

JalaliDatePicker by Majid Hosseini.
License: MIT.
GitHub: https://github.com/majidh1/JalaliDatePicker
