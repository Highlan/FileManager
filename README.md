## Run the project
### Setup
- `docker-compose up -d`
- `docker-compose exec php composer install `
- `docker-compose exec php bin/console doctrine:migrations:migrate`
- `docker-compose exec php php bin/console doctrine:fixtures:load`

```php 
Fisrt Page: http://localhost/
Username: (Based on generated data in DB)
Password: password

