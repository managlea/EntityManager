# EntityManager
[![Build Status](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master)

Example usage:
```php
$entityManager = DoctrineEntityManager::create();
$entity = $entityManager->findEntity('\Product', 1);
```
