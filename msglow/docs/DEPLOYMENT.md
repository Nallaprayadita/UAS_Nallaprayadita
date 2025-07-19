# Deployment Guide

## Overview
This guide provides the necessary steps to deploy the MS Glow Store application on a production server.

## Pre-requisites
Make sure the following software is installed and configured on your server:

1. **Web Server** (Apache or Nginx)
2. **PHP** (Version 7.4 or greater)
3. **MySQL** (Version 8.0 or greater)
4. **Git** (For deploying via Git)
5. **Composer** (For managing PHP dependencies)

## Deployment Steps

### 1. Set Up the Server
- Ensure the server has the necessary permissions and firewall settings.
- Create a new user for deployment.
- Secure the server with SSH authentication.

### 2. Clone the Repository
- Use Git to clone the MS Glow Store repository:
  ```bash
  git clone https://github.com/your-repo/msglow-store.git
  ```

- Change directory to the project folder:
  ```bash
  cd msglow-store
  ```

### 3. Configuration

#### Update Environment Configuration
- Copy `.env.example` to `.env` and update the following settings:
  ```
  APP_ENV=production
  APP_DEBUG=false
  DB_HOST=localhost
  DB_DATABASE=your_database
  DB_USERNAME=your_username
  DB_PASSWORD=your_password
  ```

#### Update Database Config
- Ensure database credentials are set in `msglow/config/database.php`.

### 4. Install Dependencies
Using Composer, install all PHP dependencies:
```bash
composer install --no-dev
```

### 5. Build Frontend Assets
- If using any CSS or JavaScript enhancements, compile them using tools like Webpack or Gulp:
  ```bash
  npm install
  npm run production
  ```

### 6. Set File Permissions
- Ensure the `storage` and `bootstrap/cache` directories are writable by the web server:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 7. Run Migrations
- Execute database migrations to set up the tables:
```bash
php artisan migrate --force
```

### 8. Configure Web Server
- Set up your web server to serve the application from the `public` directory.
- Example configuration for Apache:
  ```
  <VirtualHost *:80>
      ServerName msglow.example.com
      DocumentRoot /path/to/msglow-store/public

      <Directory /path/to/msglow-store/public>
          AllowOverride All
          Require all granted
      </Directory>

      ErrorLog ${APACHE_LOG_DIR}/error.log
      CustomLog ${APACHE_LOG_DIR}/access.log combined
  </VirtualHost>
  ```

### 9. Optimize the Application
- Optimize the application for speed and security:
```bash
php artisan optimize
```

### 10. Monitoring and Logs
- Set up monitoring for server uptime and performance.
- Check error logs regularly via
  ```
  tail -f /var/log/httpd/error.log
  ```

## Post-Deployment
- Test all major functionalities like user registration, product ordering, and payment processing.
- Confirm that emails are being sent correctly if using any mailing service.

---

*Last updated: July 2025*
*Version: 1.0.0*
