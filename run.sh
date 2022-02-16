filename=".env.example"
if [ ! -f "$filename" ]; then
    cp .env.example .env
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/opt \
        -w /opt \
        laravelsail/php80-composer:latest \
        composer install --ignore-platform-reqs
fi

./vendor/bin/sail up
