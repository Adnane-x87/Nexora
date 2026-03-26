# NEXORA — Next-Gen Electronics

**NEXORA** is a premium, next-generation e-commerce platform for high-end electronics. Built with a focus on speed, security, and a seamless user experience, it features a modern vanilla JS frontend and a robust Laravel 13 backend.

## 🚀 Key Features

- **🛒 Modern Shopping Experience**: Interactive product catalog with categories (Laptops, Gaming, Audio, etc.) and real-time search.
- **💳 Secure Payments**: Integrated with **Stripe** for seamless checkout, featuring PaymentIntent + Elements flow.
- **🔐 User Management**: Complete authentication system (Login/Register) with profile management and wishlists.
- **🛠️ Admin Dashboard**: Full-featured back-office to manage products, categories, orders, and users.
- **⚡ Performance**: High-performance frontend using Vanilla HTML/CSS/JS with smooth animations and glassmorphism design.
- **🛡️ Robust Backend**: Powered by Laravel 13 with secure API endpoints and optimized database management.

## 🛠️ Technology Stack

### Frontend
- **HTML5 & CSS3**: Modern layouts with Syne and DM Sans typography.
- **Vanilla JavaScript**: Pure JS for interactivity, cart logic, and API communication.
- **Styling**: Custom CSS with premium effects (gradients, glassmorphism, micro-animations).

### Backend
- **Framework**: [Laravel 13](https://laravel.com)
- **Language**: PHP 8.3+
- **Database**: SQLite (Default) / MySQL
- **Payments**: Stripe PHP SDK
- **Task Runner**: Vite (Backend assets)

## 📁 Project Structure

```text
Nexora/
├── frontend/           # Vanilla JS Frontend (Client-side)
│   ├── index.html      # Landing page
│   ├── shop.html       # Product catalog
│   ├── dashboard.html  # User dashboard
│   └── assets/         # Images and icons
└── nexora/             # Laravel 13 Backend (Server-side & API)
    ├── app/            # Logic & Models
    ├── routes/         # API & Web endpoints
    └── database/       # Migrations & Seeders
```

## ⚙️ Getting Started

### Prerequisites
- **PHP 8.3+**
- **Composer**
- **Node.js & npm** (for backend asset compilation)

### Backend Setup (Laravel)
1. Navigate to the backend directory:
   ```bash
   cd nexora
   ```
2. Install dependencies and set up the project:
   ```bash
   composer setup
   ```
   *Note: This command runs migrations, generates keys, and installs npm dependencies automatically.*
3. Start the development server:
   ```bash
   composer dev
   ```
   Your backend will be running at `http://localhost:8000`.

### Frontend Setup
1. Simply open `frontend/index.html` in your web browser.
2. (Optional) For a better experience, serve the `frontend` directory using a local server:
   ```bash
   # Using VS Code Live Server or python
   python -m http.server 8080
   ```

## 🔐 Environment Variables

Ensure your `nexora/.env` file is configured with your Stripe keys:
```env
STRIPE_KEY=your_public_key
STRIPE_SECRET=your_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret
```

