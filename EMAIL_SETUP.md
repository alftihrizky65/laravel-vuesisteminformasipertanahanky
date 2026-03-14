Quick guide — Configure Laravel to send feedback emails to Gmail

Option A — Use Gmail SMTP (recommended for testing with your Gmail inbox)
1. Enable 2FA on your Google account (if not already).
2. Create an App Password in Google Account > Security > App passwords. Choose "Mail" and the device "Other" and copy the generated password.
3. Update your project's .env with the following (use your Gmail address and the App Password):

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yourgmail@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=yourgmail@gmail.com
MAIL_FROM_NAME="Admin SIP"
ADMIN_EMAIL=alftihrizky65@gmail.com

4. Clear config cache and test:
   php artisan config:clear
   php artisan cache:clear
   Visit: http://127.0.0.1:8000/mail/test — the page will report success or an error message.

Notes:
- Gmail enforces security; using the App Password is required when 2FA is enabled.
- Setting MAIL_FROM_ADDRESS to your Gmail reduces likelihood of being blocked.

Option B — Use Mailtrap / MailHog (safer for development)
- Mailtrap: set MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD to Mailtrap credentials.
- MailHog: run locally and set MAIL_HOST=127.0.0.1, MAIL_PORT=1025.

If emails still don't arrive to Gmail, try:
- Check storage/logs/laravel.log for errors
- Open the test route /mail/test and paste the exact error message here and I will help fix it
- Consider using a transactional email provider (SendGrid, Mailgun) for reliable delivery in production

If you want, I can help set this up step-by-step and verify delivery to your Gmail account.