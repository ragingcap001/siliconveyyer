1. PHP version >= 8.1.0 (the curl and zip extensions are required)

2. Setup — the bundled installer (remotelywork/installer) has been removed, so the
   application is configured directly from the environment:

       composer install
       cp .env.example .env      # or create .env by hand, then edit it
       php artisan key:generate

   Fill in APP_URL plus your database, mail and broadcast credentials, then import the
   shipped dump and run the migrations:

       mysql -u USER -p DATABASE < DB/hyiprio.sql
       php artisan migrate

   NOTE: the database must exist and be reachable before any artisan command runs.
   Several service providers (settings, theme and language) query the database at boot,
   so `php artisan` on an empty or unreachable database will fail.

   If you edit composer.json, refresh the lock hash afterwards:

       composer update --lock

3. Make sure to set up the cron job for Task, Referral, and User Ranking. Failure to do
   so could result in tasks never closing and referral/ranking rewards never paying out.


Follow the instruction below:

 Go to your cPanel Cronjob section
 Use This command for Task: curl -s https://yoursite.com/cron-job/task
 Use This command for Referral: curl -s https://yoursite.com/cron-job/referral
 Use This command for User Ranking: curl -s https://yoursite.com/cron-job/user-ranking
 Use This command for Paypal Automatic withdraw: /usr/bin/php /path/to/your/laravel/project/artisan queue:work --daemon > /dev/null
