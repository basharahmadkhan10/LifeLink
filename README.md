<h1 align="center">🩸 LifeLink — Privacy-First Blood Donation Platform</h1>

<p align="center">
  A next-generation, highly secure, and gamified blood donation network connecting donors, patients, and hospitals during critical and routine medical needs. Built with Laravel, MongoDB, and TailwindCSS.
</p>

---

## 🌟 Overview

LifeLink revolutionizes how blood donation networks operate by prioritizing **donor privacy**, **real-time emergency response**, and **community engagement** through gamification. 

Unlike traditional open directories that expose personal information, LifeLink utilizes an event-driven, secure inbox system. Patients can find nearby donors using advanced Geospatial queries, but contact details remain entirely hidden until a donor explicitly accepts a request.

## 🚀 Core Features

*   **🔒 Secure Donor Directory:** Search for donors by Blood Group and City. Contact details (phone, email, address) are strictly hidden. Connection is established via an internal Request System.
*   **🚨 Emergency Broadcast System:** High-priority alerts pushed to nearby, eligible donors using MongoDB `$centerSphere` geo-queries (50km radius).
*   **⚡ Concurrency Control (Zero Duplicacy):** When a patient accepts a donor for an emergency, the system automatically declines competing offers and hides the broadcast to prevent multiple donors from arriving at the hospital unnecessarily.
*   **🏆 Gamification & Leaderboard:** Donors earn points for their heroism (+50 for normal requests, +100 for emergencies). A dynamic city-filtered leaderboard highlights top community contributors.
*   **⏱️ Medical Cooldown Enforcement:** Automatic 90-day medical lockout following a successful donation to ensure donor health and safety.
*   **🏥 Hospital & Blood Bank Portals:** Dedicated dashboards for hospitals to manage blood stock inventories and request resources.
*   **🛡️ Admin Control Panel:** Comprehensive user management, ban enforcement, and reward administration.
*   **📱 Secure OTP Verification:** Built-in Email and Phone OTP verification during registration (Currently configured for Mailtrap for presentation purposes; production-ready for Twilio SMS).

## 🏗️ Architecture Design

LifeLink is built on a modern, robust, and highly scalable stack:

*   **Backend Framework:** Laravel 11 (PHP)
*   **Database:** MongoDB Atlas (NoSQL) via `mongodb/laravel-mongodb`.
    *   *Indexing:* Utilizes `2dsphere` indexes on user locations for rapid proximity matching. Compound indexes on `points`, `city`, and `blood_group` ensure the leaderboard scales to hundreds of thousands of users.
*   **Frontend:** Laravel Blade, TailwindCSS, Vanilla JavaScript.
    *   *Design System:* Features a premium glassmorphism aesthetic, dynamic gradients, dark/light mode toggling, and micro-animations for high user engagement.
*   **Authentication & Authorization:** Custom Role-Based Access Control (RBAC) extending Laravel Breeze (Roles: `user`, `hospital`, `admin`).
*   **Communication:** Internal asynchronous messaging and notification engine.

## 🗺️ Routing Structure

The application routes are logically separated by user roles and feature domains:

```text
Public & Auth
├── /                     # Landing & Marketing Page
├── /login, /register     # Authentication & Registration
└── /otp/*                # Email & Phone Verification Endpoints

User / Donor Space (Requires Auth & Verification)
├── /dashboard            # Personalized feed (Stats, active requests, nearby emergencies)
├── /donors               # Secure Donor Directory (City & Blood Group filters)
├── /emergency            # Emergency Broadcast creation & management
├── /inbox                # Centralized notification and messaging hub
├── /chat/{id}            # Secure 1-to-1 communication for accepted requests
└── /leaderboard          # Gamified ranking system (City-filterable)

Hospital Space (Requires 'hospital' Role)
├── /hospital/dashboard   # Hospital overview
└── /hospital/stocks      # Blood inventory management

Admin Space (Requires 'admin' Role)
├── /admin/dashboard      # System metrics
├── /admin/users          # User moderation (Ban/Unban)
└── /admin/rewards        # Gamification reward distribution
```

## 🛠️ Installation & Initialization

Follow these steps to deploy LifeLink locally or on a production server.

### Prerequisites
*   PHP 8.2+
*   Composer
*   Node.js & npm
*   MongoDB Instance (Local or Atlas)
*   Mailtrap Account (for OTP testing)

### Setup Commands

```bash
# 1. Clone the repository
git clone https://github.com/basharahmadkhan10/LifeLink.git
cd LifeLink

# 2. Install PHP Dependencies
composer install --optimize-autoloader --no-dev

# 3. Install Node Dependencies & Build Assets
npm install
npm run build

# 4. Environment Configuration
cp .env.example .env
php artisan key:generate

# 5. Configure your .env file
# Open .env and set your MongoDB URI and Mailtrap credentials:
# DB_CONNECTION=mongodb
# DB_URI="mongodb+srv://<user>:<password>@cluster.mongodb.net/LifeLink"
# MAIL_HOST=sandbox.smtp.mailtrap.io
# MAIL_USERNAME=your_username
# MAIL_PASSWORD=your_password

# 6. Cache Configuration (Production Only)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Start the Application (Local Development)
php artisan serve
```

## 🛡️ Security & Privacy Note
This platform handles sensitive medical and location data. All location matching is done server-side using MongoDB geospatial queries. Exact coordinates are **never** exposed to the frontend clients. The OTP system prevents automated spam registration, and the secure inbox prevents unsolicited direct contact.

---
*Built to save lives.* ❤️
