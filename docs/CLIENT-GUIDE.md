# Client Guide - HIPAA POC Secure Messaging Application

## 🌐 Your Application is Live!

**Application URL**: https://taxplanner.app

Your HIPAA-compliant secure messaging application is deployed and ready to use.

---

## Table of Contents

1. [Quick Start](#quick-start)
2. [Initial Setup - Google OAuth](#initial-setup---google-oauth)
3. [How to Use the Application](#how-to-use-the-application)
4. [User Management](#user-management)
5. [Security Features](#security-features)
6. [Monitoring & Maintenance](#monitoring--maintenance)
7. [Troubleshooting](#troubleshooting)
8. [Support](#support)

---

## Quick Start

### Step 1: Configure Google OAuth (One-time setup)

Before users can log in, you need to configure Google OAuth:

1. **Go to Google Cloud Console**
   - Visit: https://console.cloud.google.com/apis/credentials
   - Sign in with your Google account

2. **Select Your Project**
   - Choose the project that has your OAuth credentials

3. **Configure OAuth Client**
   - Click on your OAuth 2.0 Client ID
   - Under "Authorized redirect URIs", click "+ ADD URI"
   - Add this exact URL:
     ```
     https://taxplanner.app/login.php
     ```
   - Click "SAVE"

4. **Wait 5 Minutes**
   - Google needs a few minutes to update the settings
   - After 5 minutes, your application will accept logins

### Step 2: Test the Application

1. Open your browser and go to: **https://taxplanner.app**
2. You should see the login page with a "Sign in with Google" button
3. Click the button to test the login

---

## Initial Setup - Google OAuth

### What is Google OAuth?

Google OAuth allows users to sign in to your application using their Google accounts. This means:
- ✅ Users don't need to create new passwords
- ✅ Secure authentication through Google
- ✅ Only authorized Google accounts can access the system

### Detailed Setup Instructions

#### 1. Access Google Cloud Console

1. Go to: https://console.cloud.google.com/
2. Sign in with your Google account (the one that manages your project)

#### 2. Navigate to Credentials

1. Click the hamburger menu (☰) in the top-left
2. Go to: **APIs & Services** → **Credentials**
3. You should see your OAuth 2.0 Client IDs listed

#### 3. Edit OAuth Client

1. Find your OAuth 2.0 Client ID in the list
2. Click the edit icon (✏️) next to it
3. Scroll down to "Authorized redirect URIs"

#### 4. Add Redirect URI

1. Click **"+ ADD URI"** button
2. Enter exactly:
   ```
   https://taxplanner.app/login.php
   ```
3. **Important**: Make sure there are no spaces before or after the URL
4. Click **"SAVE"** at the bottom

#### 5. Verify Configuration

After saving, you should see the new URI in the list:
```
Authorized redirect URIs:
  https://taxplanner.app/login.php
```

#### 6. Wait for Propagation

- Google's systems need **5-10 minutes** to update
- During this time, logins may show an error
- After 10 minutes, try logging in again

---

## How to Use the Application

### For End Users

#### Logging In

1. **Visit the Application**
   - Go to: https://taxplanner.app
   - You'll see the login page

2. **Sign in with Google**
   - Click the "Sign in with Google" button
   - Choose your Google account
   - Click "Allow" to grant permissions

3. **Access Dashboard**
   - After login, you'll see your dashboard
   - From here you can send and receive messages

#### Sending Messages

1. **Navigate to Messages**
   - Click on "Send Message" or "New Message"

2. **Choose Recipient**
   - Select the recipient from the dropdown list
   - Only authorized users will appear

3. **Compose Message**
   - Type your message in the text box
   - Messages are automatically encrypted

4. **Send**
   - Click "Send" button
   - The recipient will receive the encrypted message

#### Reading Messages

1. **View Inbox**
   - Click on "Inbox" or "Messages"
   - You'll see a list of your messages

2. **Open Message**
   - Click on any message to read it
   - Messages are automatically decrypted for display

3. **Reply (if available)**
   - Click "Reply" to respond to a message

#### Logging Out

1. Click on your profile icon or name
2. Select "Logout"
3. You'll be redirected to the login page

---

## User Management

### How Users Are Created

- **Automatic Creation**: Users are automatically created when they first log in with Google
- **Email Requirement**: Only users with authorized Google accounts can log in
- **No Manual Registration**: There's no separate registration process

### Authorizing Users

To control who can access your application:

#### Option 1: Google Workspace (Recommended)

If you have Google Workspace (formerly G Suite):

1. Go to: https://admin.google.com/
2. Navigate to **Apps** → **Web and mobile apps**
3. Find your application
4. Configure who can access it:
   - Everyone in organization
   - Specific groups
   - Specific users

#### Option 2: OAuth Consent Screen

1. Go to: https://console.cloud.google.com/apis/credentials/consent
2. Under "Test users", add the email addresses of authorized users
3. Save changes

### Removing Users

To remove a user's access:

1. Remove them from authorized users in Google (see above)
2. They will no longer be able to log in
3. Their existing data remains in the system (for audit purposes)

---

## Security Features

Your application includes these HIPAA-compliant security features:

### Data Encryption

✅ **In Transit**
- All data transmitted over HTTPS/TLS 1.3
- 256-bit encryption between users and server

✅ **At Rest**
- Database stored on encrypted EBS volumes
- Backups encrypted in S3 with AES-256

### Access Control

✅ **Authentication**
- Google OAuth 2.0 authentication
- No passwords stored in the system

✅ **Session Management**
- Secure session cookies
- Automatic logout after inactivity

### Infrastructure Security

✅ **Network Security**
- Application hosted in private subnet
- Access only through load balancer
- Security groups restrict traffic

✅ **Audit Logging**
- All access logged to CloudWatch
- Logs retained for compliance

### Data Backup

✅ **Automated Backups**
- Daily backups at 2:00 AM UTC
- Encrypted and stored in S3
- 30-day retention (configurable)

---

## Monitoring & Maintenance

### Checking Application Status

#### Quick Health Check

Visit: https://taxplanner.app

If you see the login page, the application is running.

#### Detailed Status

Contact your technical administrator to:
- View CloudWatch logs
- Check target health
- Review SSL certificate expiration

### Regular Maintenance

Your application requires minimal maintenance:

#### Monthly Tasks

- [ ] Verify application is accessible
- [ ] Test login functionality
- [ ] Review access logs (if needed for compliance)

#### Quarterly Tasks

- [ ] Review authorized user list
- [ ] Update Google OAuth settings if needed
- [ ] Verify backups are running

#### Annual Tasks

- [ ] Review HIPAA compliance checklist
- [ ] Update Business Associate Agreement (if applicable)
- [ ] Review and update security policies

---

## Troubleshooting

### Login Issues

#### Error: "Redirect URI Mismatch"

**Problem**: Google OAuth redirect URI not configured correctly

**Solution**:
1. Go to: https://console.cloud.google.com/apis/credentials
2. Edit your OAuth Client
3. Verify the redirect URI is exactly:
   ```
   https://taxplanner.app/login.php
   ```
4. Make sure there are no spaces
5. Save and wait 10 minutes

#### Error: "Access Blocked: This app's request is invalid"

**Problem**: User's email not authorized

**Solution**:
1. Add user to authorized list in Google Console
2. OR configure OAuth consent screen to allow your organization

#### Can't See "Sign in with Google" Button

**Problem**: Browser cache or JavaScript disabled

**Solution**:
1. Clear browser cache: `Ctrl+Shift+Delete` (or `Cmd+Shift+Delete` on Mac)
2. Ensure JavaScript is enabled
3. Try a different browser (Chrome recommended)
4. Try incognito/private mode

### Application Issues

#### Page Not Loading

**Problem**: DNS or SSL certificate issue

**Solutions**:
1. Wait 5 minutes and try again (DNS propagation)
2. Clear browser cache
3. Try: http://taxplanner.app (should redirect to https)
4. Check if you can access: https://google.com (verify your internet)

#### "502 Bad Gateway" Error

**Problem**: Application backend temporarily unavailable

**Solution**:
1. Wait 2-3 minutes and refresh
2. If problem persists, contact technical support

#### Messages Not Sending

**Problem**: Database connection issue

**Solution**:
1. Try logging out and logging back in
2. Clear browser cookies
3. If problem persists, contact technical support

### Performance Issues

#### Slow Loading

**Possible Causes**:
- High traffic volume
- Internet connection issues
- Browser cache full

**Solutions**:
1. Clear browser cache
2. Close other browser tabs
3. Check your internet speed
4. Try accessing during off-peak hours

---

## Support

### Technical Support

For technical issues, contact your system administrator with:

1. **Error Description**
   - What were you trying to do?
   - What error message did you see?
   - Screenshot of the error (if possible)

2. **Environment Information**
   - What browser are you using? (Chrome, Firefox, Safari, etc.)
   - What device? (Desktop, laptop, tablet, mobile)
   - Operating system? (Windows, Mac, Linux, iOS, Android)

3. **Steps to Reproduce**
   - What steps did you take before the error?
   - Can you reproduce the error consistently?

### Emergency Contact

For urgent security or compliance issues:

- **Security Incident**: Contact immediately if you suspect:
  - Unauthorized access
  - Data breach
  - Security vulnerability

### Self-Help Resources

**Application URL**: https://taxplanner.app

**Google Cloud Console**: https://console.cloud.google.com/

**Documentation**: See other files in the `/docs` folder

---

## Quick Reference

### Important URLs

| Purpose | URL |
|---------|-----|
| **Application** | https://taxplanner.app |
| **Google Console** | https://console.cloud.google.com/apis/credentials |
| **OAuth Redirect URI** | `https://taxplanner.app/login.php` |

### Quick Commands for Administrators

```bash
# Check application status
curl -I https://taxplanner.app

# View recent logs (requires AWS access)
aws logs tail /hipaa-poc/application --follow --region us-east-2

# Connect to server (requires AWS access)
aws ssm start-session --target i-04c7660dd799eda07 --region us-east-2
```

### Browser Compatibility

✅ **Recommended**: Google Chrome (latest version)
✅ Firefox (latest version)
✅ Safari (latest version)
✅ Microsoft Edge (latest version)

❌ **Not Supported**: Internet Explorer

### Mobile Access

✅ **iOS**: Safari or Chrome
✅ **Android**: Chrome or Firefox

---

## Appendix: Google OAuth Setup Screenshots

### Finding OAuth Credentials

1. **Google Cloud Console Home**
   ```
   https://console.cloud.google.com/
   ```

2. **Navigation Path**
   ```
   ☰ Menu → APIs & Services → Credentials
   ```

3. **OAuth Client ID Location**
   - Look for section: "OAuth 2.0 Client IDs"
   - Your client will be listed there
   - Click the edit icon (✏️)

### Adding Redirect URI

1. **Authorized Redirect URIs Section**
   - Scroll down to find this section
   - Click "+ ADD URI"

2. **Enter the URI**
   ```
   https://taxplanner.app/login.php
   ```

3. **Save**
   - Scroll to bottom
   - Click "SAVE" button
   - Wait for confirmation message

---

## FAQ (Frequently Asked Questions)

### General

**Q: Do I need to create an account?**
A: No. You log in with your Google account. Access is granted based on your email.

**Q: Can I use my personal Gmail account?**
A: Only if your administrator has authorized your specific email address.

**Q: Is this application HIPAA compliant?**
A: Yes. The application is designed with HIPAA compliance in mind, including encryption, access controls, and audit logging.

**Q: Where is my data stored?**
A: Data is stored in AWS (Amazon Web Services) US-East region with encryption.

### Technical

**Q: What browsers are supported?**
A: Chrome, Firefox, Safari, and Edge (latest versions). Internet Explorer is not supported.

**Q: Can I access this from my phone?**
A: Yes. The application works on mobile browsers.

**Q: Do I need to install anything?**
A: No. It's a web application - just use your browser.

**Q: Why does Google ask for permissions?**
A: The application needs to verify your identity through Google. It only requests your email and basic profile information.

### Security

**Q: Is my data encrypted?**
A: Yes. All data is encrypted in transit (HTTPS) and at rest (encrypted storage).

**Q: Can the administrator see my messages?**
A: System administrators have access to encrypted data for maintenance and compliance purposes.

**Q: How long are messages stored?**
A: Messages are stored indefinitely unless deleted. Backups are retained for 30 days.

**Q: What happens if I forget to log out?**
A: Sessions automatically expire after a period of inactivity for security.

---

## Glossary

**OAuth**: A secure method to log in using your Google account without sharing your password

**HTTPS**: Secure connection that encrypts data between your browser and the server

**SSL/TLS**: Security protocol that protects data in transit

**Two-Factor Authentication**: Extra security layer requiring two forms of verification (if enabled)

**Session**: Your logged-in period from login to logout

**Redirect URI**: The URL Google sends you back to after login

**Encryption**: Scrambling data so only authorized parties can read it

**EBS**: Amazon's encrypted storage system

**S3**: Amazon's file storage service for backups

**CloudWatch**: Amazon's logging and monitoring service

**Load Balancer**: Distributes traffic and ensures high availability

---

## Document Version

**Version**: 1.0
**Last Updated**: February 3, 2026
**Application URL**: https://taxplanner.app
**Support Contact**: [Your Support Email/Contact]

---

## Need Help?

If you need assistance with any of these steps:

1. **Review this guide carefully** - Most issues are covered in the Troubleshooting section
2. **Check Google's documentation** - https://support.google.com/cloud/
3. **Contact your technical administrator** - Provide specific error messages and screenshots
4. **For emergencies** - Use your established emergency contact procedures

**Remember**: Your data security is our priority. Never share your login credentials with anyone.

---

**End of Client Guide**
