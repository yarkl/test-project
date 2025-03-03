<h1>Project setup</h1>

<h2>1. Build container by running: docker build -t test-project . </h2>

<h2>2. Enter a container: docker run -it --rm --mount type=bind,source=./,target=/var/www/service --name test-project test-project bash<h2>

<h2>3. Install dependencies: composer install</h2>

<h2>4. Run tests: php vendor/bin/phpunit</h2>

<h2>5. To run real code create an account at https://apilayer.com/marketplace/exchangerates_data-api?live_demo=show exchange rate api and get at token. Fill in the field "exchange_api_key" di_config.php file with a token you have created.</h2>

<h2>6. Place a file with transaction data a "files" directory</h2>

<h2>7. Run app: php index.php </h2>