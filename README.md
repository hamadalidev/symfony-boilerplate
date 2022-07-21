
# Api doc
this is api doc url api/doc

# Postman collection
find postman collection in main folder
desygner.postman_collection.json

# Jwt keys
"openssl genrsa -out config/jwt/private.pem 4096", <br>
"openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem"

# Run migration
set databse detail in .env <br>
php bin/console doctrine:schema:update --dump-sql <br>
php bin/console doctrine:schema:update --force

# Run fixture
php bin/console doctrine:fixtures:load

# Admin account
username: admin <br>
password: password

# Docker Development
docker-compose build <br>
docker-compose up
