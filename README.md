# FundingPlattform
For crowd funded projects with single time or regular payments  this tool can help to manage fundings. With realtime graph and sepa xml output for direct usage on banking account.

The tools contains a campaign manager, each campaign allows to invite supporters by mail.

## Installation

    git clone https://github.com/ljonka/FundingPlattform.git
    cd FundingPlattform
    touch database/database.sqlite
    cp .env.example .env
    #change database path to absolute path of database.sqlite file in .env file
    #...
    #install composer if not available
    composer update #update required php libraries
    php artisan key:generate #update application key
    php artisan migrate #create db tables
    #this should pass without errors, otherwise consult laravel installation doc
    php artisan serve #start php server for testing
    #point your browser to local ip and used port. eg: http://127.0.0.1:8080
    # on live setup, point your webserver to public/ as DirectoryRoot and
    # enable htaccess functions


Based on Laravel 5.4 PHP Framework
