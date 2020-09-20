# INSTALLATION
- first of all we need to install all the packages:
    ```composer install```
- configure a database mysql
- duplicate .env.example into a .env file (and change the config for your database)
- DO NOT RUN MIGRATIONS
- run this command to load all the sql demo data:
    ```php artisan db:seed```

# ACCOUNT DEMO
- email: test@email.it
- password: Domenico20@

# LAST TIP
Visit the `{BASE_URL}/login` to do an access to the application.
