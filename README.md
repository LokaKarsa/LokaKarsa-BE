# LokaKarsa Backend API

### The Core Engine for Aksara Jawa Learning

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](../../LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

<br/>

## 📖 About The Project

This repository contains the backend API for **LokaKarsa**, our gamified application dedicated to teaching Aksara Jawa (Javanese script). Built with **Laravel**, this API serves as the robust core for managing all critical application logic, including user authentication, learning progress tracking, gamification mechanics (XP, streaks, leaderboard, badges and achievements), and content delivery for Aksara Jawa lessons. It interacts with a **MySQL** database for data persistence and can also communicate with the LokaKarsa ML Model API for character recognition needs.

This project was developed for the **GarudaHacks 6.0 Hackathon** on the "Connecting Culture" sub-theme.

---

## 🔥 Features

The LokaKarsa Backend API provides comprehensive functionalities:

-   **User Authentication & Management:** Secure registration, login, and user profile management.
-   **Learning Path & Lesson Management:** Serves structured content for various Aksara Jawa levels and lessons.
-   **Gamification System:**
    -   **Experience Points (XP):** Calculates and awards XP based on lesson completion and performance.
    -   **Streaks:** Tracks daily learning consistency.
    -   **Leaderboard:** Maintains and provides data for a global ranking system.
    -   **Achievements and Badges:** Manages and awards user achievements.
-   **Progress Tracking:** Records user progress through lessons and levels.
-   **Database Management:** Efficiently stores and retrieves all application data using MySQL.
-   **ML Model Integration:** Provides an interface (or orchestrates requests) to the separate Flask ML API for features like handwriting recognition.

---

## 🏛️ Architecture & Communication

This backend is a RESTful API designed to be consumed primarily by the LokaKarsa Frontend. It uses MySQL as its database and can interact with a separate Machine Learning API.

-   **Frontend Interaction:** Serves data to the [LokaKarsa Frontend](https://github.com/LokaKarsa/LokaKarsa-FE).
-   **Database:** Persists all application data in a **MySQL** database.
-   **ML Model API Integration:** Can send or receive requests from the [LokaKarsa ML Model API](https://github.com/LokaKarsa/LokaKarsa-Model-API) for specific tasks, like submitting drawn characters for recognition.

---

## 🛠️ Tech Stack

-   **Framework**: [Laravel](https://laravel.com/)
-   **Language**: PHP
-   **Database**: [MySQL](https://www.mysql.com/)
-   **Package Manager**: Composer

---

## 🚀 Getting Started

To get the LokaKarsa Backend API up and running on your local machine, follow these steps.

### Prerequisites

-   **PHP** (7.4+ recommended)
-   **Composer**
-   **MySQL Server** running locally or accessible.

### Installation

1.  **Clone the repository:**

    ```bash
    git clone [https://github.com/LokaKarsa/LokaKarsa-BE](https://github.com/LokaKarsa/LokaKarsa-BE)
    cd LokaKarsa-BE
    ```

2.  **Install Composer dependencies:**

    ```bash
    composer install
    ```

3.  **Set up environment variables:**
    Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

    Generate an application key:

    ```bash
    php artisan key:generate
    ```

4.  **Configure Database & ML API URL:**
    Open the newly created `.env` file and update the following:

    -   **Database Credentials**:
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=lokakarsa_db    # Choose your database name
        DB_USERNAME=root            # Your MySQL username
        DB_PASSWORD=                # Your MySQL password
        ```
    -   **ML API URL**: (If the backend needs to directly call the ML API)
        ```
        ML_API_URL=http://localhost:5000/api # Or your deployed ML API URL
        ```

5.  **Run database migrations:**

    ```bash
    php artisan migrate --seed # `--seed` is optional if you have seeders for initial data
    ```

6.  **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```
    The API will typically be available at `http://127.0.0.1:8000`.

---

## 📌 API Endpoints

All API endpoints are prefixed with `/api/v1`.

### Authentication

-   `POST /api/v1/register` - Register a new user.
-   `POST /api/v1/login` - Authenticate user and provide a token.
-   `POST /api/v1/logout` - Log out the authenticated user. (Requires authentication)

### User & Dashboard

-   `GET /api/v1/dashboard` - Retrieve dashboard summary for the authenticated user. (Requires authentication)
-   `GET /api/v1/profile` - Retrieve the authenticated user's profile details. (Requires authentication)
-   `PATCH /api/v1/profile` - Update the authenticated user's profile. (Requires authentication)

### Curriculum & Learning

-   `GET /api/v1/curriculum` - Retrieve the entire learning curriculum (levels, units). (Requires authentication)
-   `GET /api/v1/units/{unit}/questions` - Retrieve questions for a specific learning unit. (Requires authentication)
-   `POST /api/v1/answers` - Submit user answers for a question/quiz. (Requires authentication)

---

## 🤝 Contributing

We welcome contributions to the LokaKarsa Backend API! Please follow these steps to contribute:

1.  Fork this repository.
2.  Create a new branch (`git checkout -b feature/your-feature-name`).
3.  Make your changes.
4.  Commit your changes (`git commit -m 'Add new feature'`).
5.  Push to the branch (`git push origin feature/your-feature-name`).
6.  Create a Pull Request.

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE.md).

---

## 🙏 Acknowledgments

-   [LokaKarsa Frontend](https://github.com/LokaKarsa/LokaKarsa-FE)
-   [LokaKarsa ML Model API](https://github.com/LokaKarsa/LokaKarsa-Model-API)
-   Laravel Framework
