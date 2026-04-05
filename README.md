# MLUC-VoteSystem

A comprehensive voting system developed for **Don Mariano Marcos Memorial State University - Mid La Union Campus**.

## 👥 Team & Credits

This project was developed by an incredible team, spearheaded by:
*   **ScriptycaLiSA** (Montizon, Jake G.)
*   **CRULCOUNT** (Abuan, Alflorence)
*   **Steven Sevilla**
*   ...and other valuable contributors.

## 🚀 Tech Stack

*   **Backend:** [Laravel 8](https://laravel.com/)
*   **Frontend:** [Vue.js 3](https://vuejs.org/)
*   **Styling:** [Tailwind CSS](https://tailwindcss.com/)
*   **Build Tools:** Laravel Mix (Root) & Vite (`vue/` directory)

## 📋 Prerequisites

Before you begin, ensure you have the following installed on your local machine:
*   **PHP** (>= 7.3 or 8.x): [Download PHP](https://www.php.net/downloads.php) and ensure it's added to your system's PATH variable.
*   **Composer**: [Download Composer](https://getcomposer.org/download/) for managing PHP dependencies.
*   **Node.js & npm**: [Download Node.js](https://nodejs.org/) to manage frontend packages.
*   **Local Database Server**: e.g., MySQL/MariaDB via XAMPP, Laragon, HeidiSQL, or SQLYog.

## 🛠️ Installation & Setup

Follow these steps to set up the repository in your local environment:

### 1. Backend Setup (Laravel)

1.  **Clone the repository** (if you haven't already).
2.  Navigate to the root directory and install PHP dependencies:
    ```bash
    composer update
    ```
    *(Note: Using `composer update` is recommended here over `install` to resolve specific PHP version incompatibilities.)*
3.  Set up your environment variables:
    ```bash
    cp .env.example .env
    ```
    *Open the `.env` file and ensure `APP_ENV=local`. Update your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` to match your local database settings.*
4.  Generate the application key:
    ```bash
    php artisan key:generate --force
    ```

### 2. Frontend Setup (Vue.js)

The project utilizes two separate frontend package configurations (Root and `vue/` directory).

1.  **Root Directory Packages:**
    Run the following in the project's root folder:
    ```bash
    npm install
    npm run dev
    ```
    *(You may need to run `npm run dev` twice to fully compile initial mix assets.)*

2.  **Vue Directory Packages:**
    Navigate to the `vue` folder and install dependencies:
    ```bash
    cd vue
    npm install
    ```

### 3. Database Setup

1.  Create a new database in your local SQL server matching your `.env` configuration.
2.  Import the provided SQL dump files located in the root directory (e.g., `db_export.sql`, `db_colleges_export.sql`, `db_preregvoter_export.sql`) into your newly created database.

## 💻 Running the Application

To run the application locally, you will need to start both the Laravel development server and the Vite development server.

1.  **Start the Backend Server:**
    Open a terminal in the root directory and run:
    ```bash
    php artisan serve
    ```
    This will typically start the server at `http://127.0.0.1:8000`.

2.  **Start the Frontend Server:**
    Open a new, separate terminal, navigate to the `vue` folder, and start Vite:
    ```bash
    cd vue
    npm run dev
    ```
3.  **Access the App:**
    Check the terminal running your Vue server for the local URL (usually `http://localhost:3000` or similar). Open this URL in your browser to access the system. Enjoy! :))

## 📄 License

This project is licensed under the **MIT License**.

Copyright (c) 2022 Jake Montizon. See the `LICENSE` file for more details.