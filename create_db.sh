#!/bin/bash

# Variables from .env file
DB_HOST="localhost"
DB_PORT="3306"
DB_DATABASE="wave"
DB_USERNAME="root"
DB_PASSWORD=""

# Function to check if the database exists
check_database_existence() {
    RESULT=$(mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "SHOW DATABASES LIKE '$DB_DATABASE';")
    if [[ "$RESULT" == *"$DB_DATABASE"* ]]; then
        echo "Database '$DB_DATABASE' already exists."
        return 0
    else
        echo "Database '$DB_DATABASE' does not exist."
        return 1
    fi
}

# Function to create the database
create_database() {
    echo "Creating database '$DB_DATABASE'..."
    mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $DB_DATABASE;"
    if [ $? -eq 0 ]; then
        echo "Database '$DB_DATABASE' created successfully."
    else
        echo "Failed to create database '$DB_DATABASE'."
    fi
}

# Main script execution
if ! check_database_existence; then
    create_database
fi
