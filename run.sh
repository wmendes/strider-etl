
if [ ! -f .env.example ]; then
    cp .env.example .env
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/opt \
        -w /opt \
        laravelsail/php80-composer:latest \
        composer install --ignore-platform-reqs
fi

if [ ! -d storage/app/ftp ]; then
    cp -r resources/default-ftp-data storage/app/ftp
fi

./vendor/bin/sail up -d
