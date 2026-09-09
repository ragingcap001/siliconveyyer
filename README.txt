1. PHP version >= 8.1.0

2. Enter the random key on the license page to proceed with installation.

3. Make sure to set up the cron job for Investment, Referral, and User Ranking. Failure to do so could result in problems with returns.




follow the instruction below:

 Go to your cPanel Cronjob section
 Use This command for Investment: curl -s https://yoursite.com/cron-job/investment
 Use This command for Referral: curl -s https://yoursite.com/cron-job/referral
 Use This command for User Ranking: curl -s https://yoursite.com/cron-job/user-ranking
 Use This command for Paypal Automatic withdraw: /usr/bin/php /path/to/your/laravel/project/artisan queue:work --daemon > /dev/null



