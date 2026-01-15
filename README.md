<p align="center">
  <h2 align="center">Dynamic Form Generator</h2>
  <p align="center">
    No-code web application for creating dynamic forms using JSON storage
  </p>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7-blue">
  <img src="https://img.shields.io/badge/Bootstrap-4-purple">
  <img src="https://img.shields.io/badge/Database-PostgreSQL-blue">
  <img src="https://img.shields.io/badge/Storage-JSON-orange">
</p>

---

## 🚀 Overview

**SmartPro** is a lightweight web application that allows users to create, manage, and render dynamic forms **without coding**.  
Form schemas and submissions are stored in **JSON**, while **PostgreSQL** is used for authentication.

---

## ✨ Features

- 🔐 User authentication (PostgreSQL)
- 🧩 Dynamic form builder
- ➕ Add new forms without coding
- 💾 JSON-based data storage
- ⚡ Lightweight & fast
- 📱 Responsive UI (Bootstrap)

---

## 🛠️ Tech Stack

| Layer | Technology |
|-----|-----------|
| Frontend | HTML, CSS, JavaScript, Bootstrap |
| Backend | PHP |
| Authentication | PostgreSQL |
| Form Storage | JSON |

---

## 📁 Folder Structure

smart_pro/
├── assets/
│ ├── css/
│ ├── js/
│ └── bootstrap/
│
├── page/
│ ├── dashboard.php
│ ├── form_builder.php
│ └── preview.php
│
├── db/
│ ├── forms.json
│ └── submissions.json
│
├── uploads/
│
├── index.php
├── login.php
├── login_proses.php
├── connection.example.php
├── .gitignore
└── README.md

---

## 🔄 Application Flow

1. User login & authentication
2. Authorized user accesses form builder
3. Form schema saved as JSON
4. Form rendered dynamically
5. Submission stored in JSON format

---



## 🔒 Security Notes

- Database credentials are excluded from repository
- `.gitignore` is used to protect sensitive files

---

## 📄 License

MIT License

## 📸 Screenshots

> Screenshots in `/docs` folder

### Login Page
![Login](docs/login.JPG)

### Dashboard
![Dashboard](docs/dashboard.JPG)

### Form Builder
![Form Builder](docs/generator.JPG)

### Report
![Report](docs/report.JPG)











