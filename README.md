## SETUP

1) build the docker containers: `docker-compose build` 
2) start the docker containers: `docker-compose up -d`
3) curl to `http://localhost:8080/api/currency/CAD CHF` to see the result
4) run tests `docker exec php php bin/phpunit`
