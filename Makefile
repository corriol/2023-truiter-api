#PHP_CMD = docker exec -it 2023-truiter-symfony_web-server_1 php
PHP_CMD = php
.DEFAULT_GOAL:=help
rebuild:
	-composer install

	@ echo "Esborrant la base de dades..."
	-$(PHP_CMD) bin/console doctrine:database:drop -n --force

	@ echo "Creant-la de nous..."
	$(PHP_CMD) bin/console doctrine:database:create -n 

	@ echo "Creant l'estructura..."
	$(PHP_CMD) bin/console doctrine:schema:create -n 

	@ echo "Carregant les dades..."
	$(PHP_CMD) bin/console doctrine:fixtures:load -n 


rebuild-test:
	-composer install

	@ echo "Esborrant la base de dades..."
	-$(PHP_CMD) bin/console doctrine:database:drop -n --force --env=test

	@ echo "Creant-la de nous..."
	$(PHP_CMD) bin/console doctrine:database:create -n --env=test

	@ echo "Creant l'estructura..."
	$(PHP_CMD) bin/console doctrine:schema:create -n --env=test

	@ echo "Esborrant miniatures..."
	-$(PHP_CMD) bin/console liip:imagine:cache:remove -n --env=test


	@ echo "Carregant les dades..."
	$(PHP_CMD) bin/console doctrine:fixtures:load -n --env=test


help:
	@ echo "Utilitza 'make rebuild' or 'make rebuild-test' per a regenerar les dades"
