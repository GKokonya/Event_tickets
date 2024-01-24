#!/bin/sh
# Start the service
service supervisor start

# Read the new config from supervisord file
supervisorctl reread

# Activate the configuration
supervisorctl update

# Start queue command for running jobs
supervisorctl start laravel-worker:* or laravel-worker

# Check the status of new config operation
supervisorctl status​