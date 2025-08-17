# Lunch Box - Setup Guide

Welcome to the Lunch Box project! Follow these steps to set up and run the application.

## Prerequisites

- [XAMPP](https://www.apachefriends.org/index.html) installed
- PHP >= 7.x
- MySQL

## 1. Clone the Repository

```bash
git clone <repository-url>
```
Or download and extract the project files to `C:/xampp/htdocs/lunch_box`.

## 2. Start XAMPP

- Open XAMPP Control Panel.
- Start **Apache** and **MySQL**.

## 3. Configure the Database

1. Open [phpMyAdmin](http://localhost/phpmyadmin).
2. Create a new database (e.g., `lunch_box`).
3. Import the SQL file:
    - Click on the database.
    - Go to **Import** tab.
    - Select the provided `.sql` file (usually in the project folder).
    - Click **Go**.

## 4. Configure Database Connection Maruf

- Edit the database configuration file (e.g., `config.php`).
- Set your database name, username, and password:
  ```php
  $db = mysqli_connect('localhost', 'root', '', 'lunch_box');
  ```

## 5. Run the Application

- Open your browser.
- Go to [http://localhost/lunch_box](http://localhost/lunch_box).

## 6. Login/Register

- Use the registration page to create a new user account.
- Login with your credentials.

## Troubleshooting

- Ensure Apache and MySQL are running.
- Check file permissions.
- Verify database credentials.

---

**You're ready to use Lunch Box!**