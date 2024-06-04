## Setup:

All you need is to run these commands:

```bash
composer install # Install backend dependencies

cp .env.example .env # Update database credentials configuration

php artisan key:generate # Generate new keys for Laravel

# I used sqlite to save time on database configuration 
give DB_DATABASE in .env absolute path of /database/links.db 

php artisan migrate # Run migrations

php artisan serve # Run the server 
```
