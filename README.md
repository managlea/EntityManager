# EntityManager
[![Build Status](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/fccb20e0-d90c-4801-a534-88845faea1ec/mini.png)](https://insight.sensiolabs.com/projects/fccb20e0-d90c-4801-a534-88845faea1ec)

Example usage:
```php
$entityManager = DoctrineEntityManager::create();
$entity = $entityManager->findEntity('\Product', 1);
```
