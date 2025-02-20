# YupChat Teste Prático Back-end

## Getting Started

Follow these instructions to get a copy of the project up and running on your local machine.

### Prerequisites

- PHP >= 8.2
- Composer
- A running database (MySQL, MariaDB, etc...)

### Installation


1. **Clone the repository:**
   ```bash
   git clone https://github.com/menegass0/teste-pratico-yupchat.git
   cd teste-pratico-yupchat
2. **Install the vendor folder:**
   ```bash
   composer install
3.  **Copy the .env.example file and set up your environment**:
    ```bash
    copy .env.example .env
    ```
    If you are on a Unix-based system, use cp .env.example .env.
    ```bash
    cp .env.example .env
    ```

4.  **Set up your .env file with your database connection details**
    ```bash.
    
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=password

5. **Generate the JWT secret key:**
    ```bash.
    php artisan jwt:secret
6. **Run the database migrations:**
    ```bash
    php artisan migrate
7. **Running the Application**
To start the application, run:
    ```bash
    php artisan serve
    ```
    Visit http://localhost:8000 in your browser to see the application in action.

Built With
Laravel - The PHP framework for web artisans
Authors
Victor Henrique Menegasso - Initial work - Menegasso



