# 💬 Connectify-PHP

### Real-Time Chat Application using PHP & MySQL

A web-based messaging application developed using **PHP, MySQL, HTML, CSS, and JavaScript**. Connectify-PHP includes user authentication, registration, and messaging functionality. This project was developed with **AI-assisted coding** as a hands-on learning experience in PHP web application development.

---

## 🛠️ Tech Stack

<p align="center">
  <img src="https://skillicons.dev/icons?i=php,mysql,html,css,js" alt="PHP, MySQL, HTML, CSS and JavaScript" />
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/HTML5-Markup-E34F26?style=for-the-badge&logo=html5&logoColor=white" alt="HTML5" />
  <img src="https://img.shields.io/badge/CSS3-Styling-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS3" />
  <img src="https://img.shields.io/badge/JavaScript-Frontend-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript" />
</p>

**Tools:** Visual Studio Code · Git · GitHub · MySQL Workbench

---

## ✨ Features

- 🔐 **User Authentication** — User registration and login.
- 💬 **Messaging** — Chat functionality between users.
- 👤 **User Accounts** — Store user details in a MySQL database.
- 🗂️ **Conversation Management** — Organize conversations and their members.
- 🔔 **Notifications** — Database support for notifications.
- ⌨️ **Typing Status** — Database support for typing indicators.
- 🔒 **Environment Configuration** — Store database credentials in a local `.env` file.
- 🧩 **Modular Database Connection** — Reusable PDO connection configuration.

_Feature availability depends on the implemented application code and its current state._

---

## 📁 Project Structure

```text
Connectify-PHP/
├── api/                  # Application API endpoints
├── assets/               # CSS, JavaScript and other assets
├── config/
│   └── database.php      # Database connection using .env
├── uploads/              # Uploaded files, if supported
├── .env                  # Local environment variables (not committed)
├── .env.example          # Example environment configuration
├── .gitignore            # Files excluded from Git
├── database.sql          # Database schema
├── register.php          # User registration
├── login.php             # User login
├── chat.php              # Main chat interface
└── README.md             # Project documentation
```

---

## 🚀 Getting Started

### 1. Prerequisites

Install the following:

- PHP with the `pdo_mysql` extension enabled
- MySQL Server
- MySQL Workbench (recommended for database management)
- Visual Studio Code or another code editor
- Git (optional, for cloning the repository)

### 2. Clone the Repository

```bash
git clone https://github.com/YOUR_USERNAME/Connectify-PHP.git
cd Connectify-PHP
```

Alternatively, download the repository ZIP and extract it.

### 3. Create the Database

Open MySQL Workbench and execute the SQL script provided in `database.sql`.

The application uses the database:

```sql
CREATE DATABASE IF NOT EXISTS chatapp_db;
```

If the SQL file already creates this database and its tables, run the provided script instead of recreating the tables manually.

### 4. Configure Environment Variables

Create a `.env` file in the project root:

```dotenv
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=chatapp_db
DB_USER=root
DB_PASS=your_mysql_password
```

Replace `your_mysql_password` with your local MySQL password.

**Important:** Never commit your actual `.env` file or expose database credentials in public repositories.

### 5. Configure PHP Database Access

The `config/database.php` file reads the environment variables and establishes a PDO connection to MySQL.

Ensure the PHP `pdo_mysql` extension is enabled:

```bash
php -m
```

Confirm that `PDO` and `pdo_mysql` appear in the output.

### 6. Run the Application

From the project root, start PHP's development server:

```bash
php -S 127.0.0.1:8000
```

Open the application in your browser:

- Registration: http://127.0.0.1:8000/register.php
- Login: http://127.0.0.1:8000/login.php
- Chat: http://127.0.0.1:8000/chat.php

Register a test account or use an existing account with its valid password.

> The PHP built-in server is intended for local development, not public production hosting.

---

## 🔐 Security Practices

- Store database credentials in `.env`, excluded through `.gitignore`.
- Use PDO prepared statements for database queries.
- Hash user passwords using PHP's `password_hash()`.
- Verify passwords using `password_verify()`.
- Validate user input and enforce authentication and authorization.
- Protect sessions and uploaded files.
- Use HTTPS and a restricted database account when deploying publicly.

---

## 🎯 Project Objective

The objective of Connectify-PHP is to explore server-side web development, relational database integration, user authentication, and messaging workflows using PHP and MySQL.

The project was built with AI-assisted coding and is also intended to support practical learning and understanding of PHP application development.

---

## 🔮 Future Enhancements

- Real-time messaging using WebSockets or another suitable communication mechanism
- Group chat and improved conversation management
- Message delivery and read receipts
- Image and file sharing
- Improved responsive UI
- Deployment to a production hosting platform

---

## 👨‍💻 Development

**Project:** Connectify-PHP  
**Category:** Full-Stack Web Application  
**Backend:** PHP  
**Database:** MySQL  
**Development approach:** AI-assisted coding and hands-on learning

---

⭐ If you find this project useful, consider giving the repository a star!
