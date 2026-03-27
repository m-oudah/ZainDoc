# 📂 ZainDoc – Intelligent Archiving & Document Management System

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![AlpineJS](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**ZainDoc** is an enterprise-grade, bilingual document management and archiving system. Built for speed, security, and scalability, it offers a seamless way for organizations to store, version, categorize, and securely share their digital assets. 

Engineered with a native RTL-first architecture and a modern, high-density UI, ZainDoc delivers a premium experience for both global and MENA-region users.

---

## ✨ Key Features

* **🧠 Intelligent File Management:** Full support for document version control, bulk uploads with progress tracking, and seamless drag-and-drop functionality.
* **🔐 Advanced Permissions (RBAC):** Granular Role-Based Access Control allowing administrators to define precise view, edit, delete, and share permissions at the system, folder, or document level.
* **🔍 Powerful Discovery:** Global full-text search powered by Laravel Scout, indexing document titles, descriptions, custom metadata, and tags.
* **📂 Smart Collections:** Auto-categorization rules that dynamically group documents based on metadata (e.g., Year, Department, Type).
* **🔗 Secure Guest Share:** Generate password-protected, time-limited external links for secure document sharing without requiring user accounts.
* **🛡️ Comprehensive Audit Trails:** Automated, read-only logging of all system actions (CRUD operations, access logs, guest views) for strict security compliance.
* **🌍 Dynamic Localization & RTL:** Database-driven translations with a UI manager. Native RTL support utilizing CSS logical properties, complemented by optimized **Cairo** typography for an elegant Arabic reading experience.

---

## 🎨 UI/UX Philosophy: Modern & Light
ZainDoc's interface is designed for high data density and reduced cognitive load:
* **High-Contrast Minimalism:** A crisp white, cool grey, and vibrant blue color palette.
* **Compact & Sharp:** Minimized border radii, compact padding, and scaled-down typography (`text-sm`) to display maximum data without clutter.
* **Focused Workflows:** Heavy use of slide-overs, modals, and sticky headers to keep users in their functional context.

---

## 🛠️ Tech Stack & Requirements

### Core Stack
* **Backend:** Laravel 13.x
* **Frontend:** Tailwind CSS, Alpine.js, Blade Components
* **Database:** MySQL / PostgreSQL
* **Search:** Laravel Scout
* **Icons & Fonts:** FontAwesome 6, Cairo (Arabic), Inter (English)

### Server Requirements
* PHP >= 8.3
* BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML PHP Extensions
* Composer

---

## 🚀 Installation & Setup

ZainDoc features a built-in **Web Setup Wizard** to make deployment effortless.

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/your-username/zaindoc.git](https://github.com/your-username/zaindoc.git)
   cd zaindoc
