# Symfony Destination Management

## Prerequisites

- **Docker**: Ensure Docker is installed and running.
- **Git**: Ensure Git is installed.

## Setup Instructions

1. **Clone the Repository**

   ```bash
   git clone <repository-url>
   cd <repository-folder>
    ```
## Setup Instructions

2. **Start the Docker Containers**

   Run the following command in the root of the project folder to start the Docker containers:

   ```bash
   docker-compose up -d
   ```
## Access the Application

- Visit `http://localhost` to view the destinations.
- For admin access, navigate to `/login`.
    - Use the following credentials:
        - **Username**: `admin@example.com`
        - **Password**: `admin`
## API Endpoints

- **List Destinations**: `http://localhost/api/destinations`
- **Filter by Name**: `http://localhost/api/destinations?name=<destination-name>`
- **Filter by ID**: `http://localhost/api/destinations?id=<destination-id>`

## Export Destinations to CSV

Run the following command to export destinations to a CSV file:

```bash
bin/console app:export-destinations
```
The CSV file will be saved in the `var/export` directory.

## Run Tests

To run the test cases, use:

```bash
php bin/phpunit
```
This will execute the tests, including those verifying if the admin section is under authentication.

## Stop the Docker Containers
```bash
docker-compose down
```
