
# Reporting System

This is a Laravel-based reporting system where users can sign up, choose supervisors, define projects, assign users to projects, set deadlines for reports, and communicate via messaging.

## Features

-   **User Management:**
    
    -   Users can sign up and log in.
    -   Users can choose one or more supervisors during registration.
-   **Project Management:**
    
    -   Supervisors (normal users) can define projects.
    -   Supervisors can assign users to specific projects.
-   **Reporting System:**
    
    -   Supervisors can set deadlines for reports within each project.
    -   Assigned users must submit reports by the specified deadlines.
-   **Messaging:**
    
    -   All users can communicate with each other via messaging.
-   **Feature Tests:**
    
    -   Included feature tests to ensure the functionality of key features.

## Installation

Follow these steps to set up the project locally:

1.  **Clone the repository:**
    
    `git clone https://github.com/amirr0z/ReportMe.git` 
    
2.  **Navigate into the project directory:**
    
    `cd ReportMe` 
    
3.  **Install composer dependencies:**
    
    
    `composer install` 
    
4.  **Copy the example environment file and configure your environment variables:**
    
    
    `cp .env.example .env` 
    
    Update `.env` with your local configuration (e.g., database settings).
    
5.  **Generate the application key:**
    
    
    `php artisan key:generate` 
    
6.  **Run database migrations and seeders:**
    
    
    `php artisan migrate --seed` 
    
    This will create necessary database tables and seed sample data.
7.  **Link storage:**


`php artisan storage:link` 


8.  **Start the development server:**
    
    
    `php artisan serve` 
    
    The application will be available at `http://localhost:8000`.
    The application API Document will be available at `http://localhost:8000/request-docs`.
    

## Usage

-   Register a new account or log in with an existing one.
-   Choose supervisors during registration.
-   Supervisors can define projects and assign users to them.
-   Set deadlines for reports within each project.
-   Communicate with other users using the messaging feature.

## Testing

To run feature tests, use the following command:

`php artisan test` 

This will execute the feature tests included in the `tests/Feature` directory.

## Contributing

Contributions are welcome! If you'd like to contribute to this project, please follow these steps:

1.  Fork the repository.
2.  Create a new branch (`git checkout -b feature/my-feature`).
3.  Make your changes.
4.  Commit your changes (`git commit -am 'Add new feature'`).
5.  Push to the branch (`git push origin feature/my-feature`).
6.  Create a new Pull Request.

## License

This project is open-source and available under the MIT License.
