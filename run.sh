docker run --rm --interactive --tty --volume "$PWD:/app" composer install --ignore-platform-reqs --no-scripts
./vendor/bin/sail artisan sail:install
./vendor/bin/sail up -d
