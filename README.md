# How to install the project

1. Make Sure You Have Composer Installed, Php, Node/NPM to run this project, if you dont have one click the link below to install.

-   https://getcomposer.org/ - Composer
-   https://www.apachefriends.org/ - XAMPP
-   https://nodejs.org/en - Node/NPM

2. Open the project in any preferred ide and open terminal within the project.

3. Rename .env-example to .env and then open the env file.

4. In .env file, configure the MAIL settings to accordingly to your mail provider.

5. Run composer install to install the composer dependencies in the project and wait for it to finish.

6. Run npm install to install npm dependencies in the project and wait for it to finish.

7. In order to use the system, you must seed the data first to have the login credentials for administrator,
   just type php artisan db:seed or migrate and seed, php artisan migrate:fresh --seed/php artisan migrate --seed

<p>
    Administrator Default Account
    username: admin
    password: password
</p>

8. Run php artisan key:generate to generate application key.

9. Run php artisan config:clear and php artisan config:cache to save changes in .env.

9. Open browser and type localhost:8000 or 127.0.0.1:8000

-   <p>Note: If error occured, try to delete this file under bootstrap/cache/config.php and then go to terminal and type php artisan config:cache, and run again</p>
