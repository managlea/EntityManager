# EntityManager
[![Build Status](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/fccb20e0-d90c-4801-a534-88845faea1ec/mini.png)](https://insight.sensiolabs.com/projects/fccb20e0-d90c-4801-a534-88845faea1ec)

Wrapped on top of different database connectors to provide general interface. Currently supports only Doctrine ORM.

##Basic usage (DoctrineEntityManager):
```php

// Create directly
$entityManager = DoctrineEntityManager::initialize($dbParams, $config);

// Create using factory (recommended)
$entityManager = EntityManagerFactory::create('DoctrineEntityManager');

// Get single entity
$entity = $entityManager->get('Entities\Product', 1);

// Get collection
$entityCollection = $entityManager->getCollection('Entities\Product');

// Create new entity
$newEntity = $entityManager->create('Entities\Product', array('name' => 'foo'));

// Update newly created entity
$updatedEntity = $entityManager->update('Entities\Product', $newEntity->getId(), array('name' => 'bar'));

// Delete update entity
$entityManager->delete('Entities\Product', $updatedEntity->getId());
```

As all existing Doctrine functionality is left intact you are also able to use all Doctrine ORM build in methods:
```php
$entityManager = DoctrineEntityManager::initialize($dbParams, $config);

$user = new User;
$user->setName('Mr.Right');

$entityManager->persist($user);
$entityManager->flush();
```
