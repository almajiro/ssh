.PHONY: test
public_key := $(shell cat tests/keys/id_rsa.pub)

coverage:
	@echo Executing PHPUnit and generating coverage report
	docker-compose exec php vendor/bin/phpunit --coverage-html build/coverage-report tests/

test:
	@echo Executing phpunit on docker container
	docker-compose exec server rm -rf /root/.ssh
	docker-compose exec server mkdir /root/.ssh
	docker-compose exec server chmod 700 /root/.ssh
	docker-compose exec server sh -c "echo '$(public_key)' > /root/.ssh/authorized_keys"
	docker-compose exec server chmod 600 /root/.ssh/authorized_keys
	docker-compose exec php vendor/bin/phpunit tests/

test_sftp:
	@echo Executing phpunit on docker container
	docker-compose exec php vendor/bin/phpunit tests/FileSystemTest.php

clean:
	rm -f .phpunit.result.cache
	rm -rf build/
