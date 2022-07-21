
# Api doc
this is api doc url api/doc

# Postman collection
find postman collection in main folder
 desygner.postman_collection.json

# Run migration
set databse detail in .env <br>
php bin/console doctrine:schema:update --dump-sql <br>
php bin/console doctrine:schema:update --force

# Run fixture
php bin/console doctrine:fixtures:load

# Admin account
username: admin <br>
password: password
