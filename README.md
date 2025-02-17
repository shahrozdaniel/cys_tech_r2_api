
# Laravel API Project

## Overview

This is a RESTful API built with Laravel, featuring user authentication using Laravel Sanctum, and CRUD operations on a `Transaction` resource. The API allows users to register, log in, and manage their own transactions.

## Features

- User Registration
- User Login
- Create and Read operations on Transactions
- User-specific data access (transactions are linked to the authenticated user)
- Secure API with token-based authentication using Sanctum
- Logging and error handling

---

## Requirements

- PHP >= 8.1
- Composer
- MySQL or SQLite (for local development)

---

## Setup Instructions

### 1. Clone the Repository

First, clone the repository to your local machine.

```bash
git clone https://github.com/shahrozdaniel/cys_tech_r2_api.git
cd cys_tech_r2_api
```

### 2. Install Dependencies

Run the following command to install all PHP dependencies:

```bash
composer install
```

### 3. Create `.env` File

Copy the example `.env` file and configure it for your local environment:

```bash
cp .env.example .env
```

### 4. Configure the Database

In the `.env` file, configure your database settings:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

If you're using SQLite, you can configure it like this:

```ini
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database/database.sqlite
```

### 5. Generate Application Key

Laravel requires an application key for encryption. Generate it by running the following command:

```bash
php artisan key:generate
```

### 6. Migrate the Database

Run the database migrations to create the necessary tables (e.g., `users`, `transactions`, etc.):

```bash
php artisan migrate
```

### 7. Install Sanctum

To use Laravel Sanctum for API authentication, install it and publish the Sanctum configuration:

```bash
composer require laravel/sanctum
php artisan sanctum:install
```

Add the Sanctum middleware in `app/Http/Kernel.php`:

```php
'api' => [
		\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
		'throttle:api',
		\Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

Publish the Sanctum configuration:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## Running the Application Locally

### 1. Serve the Application

To start the development server, run the following command:

```bash
php artisan serve
```

This will serve the application at `http://127.0.0.1:8000`.

### 2. Access the API Endpoints

Here are the key API endpoints available:

- **POST `/api/register`** – Register a new user
- **POST `/api/login`** – Login and receive an API token
- **POST `/api/transactions`** – Create a new transaction
- **GET `/api/transactions`** – Retrieve a list of transactions for the authenticated user
- **GET `/api/transaction/{id}`** – Retrieve a specific transaction by ID

To use these endpoints, include the API token in the `Authorization` header as a Bearer token after logging in.

Example header for authentication:

```bash
Authorization: Bearer YOUR_API_TOKEN
```

---

## Testing the API

### 1. Test Registration

Send a `POST` request to `/api/register` with the following JSON body:

```json
{
		"name": "John Doe",
		"email": "john@example.com",
		"password": "password123"
}
```

### 2. Test Login

Send a `POST` request to `/api/login` with the following JSON body:

```json
{
		"email": "john@example.com",
		"password": "password123"
}
```

The response will include an API token that you can use for subsequent requests.

### 3. Test Create and Read Operations for Transactions

- **Create a transaction:**

Send a `POST` request to `/api/transactions` with the following JSON body (authenticated):

```json
{
		"title": "Transaction 1",
		"description": "Description of transaction"
}
```

- **Retrieve a list of transactions:**

Send a `GET` request to `/api/transactions`.

- **Retrieve a specific transaction by ID:**

Send a `GET` request to `/api/transaction/{id}`.

---

## Additional Information

- **API Authentication:** This project uses Laravel Sanctum for token-based authentication. You can use the token received during login in the `Authorization` header for all subsequent requests.
- **Error Handling:** The API includes detailed error messages and appropriate HTTP status codes for edge cases (e.g., invalid credentials, missing data).

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Conclusion

You can now run the Laravel API locally and interact with it using Postman or any other API testing tool. Make sure to follow the steps in the setup instructions to get everything working correctly.
