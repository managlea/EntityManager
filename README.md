# EntityManager
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master)  
[![Code Climate](https://codeclimate.com/github/managlea/EntityManager/badges/gpa.svg)](https://codeclimate.com/github/managlea/EntityManager) [![Test Coverage](https://codeclimate.com/github/managlea/EntityManager/badges/coverage.svg)](https://codeclimate.com/github/managlea/EntityManager/coverage)  
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fccb20e0-d90c-4801-a534-88845faea1ec/mini.png)](https://insight.sensiolabs.com/projects/fccb20e0-d90c-4801-a534-88845faea1ec)  
[![Codacy Badge](https://api.codacy.com/project/badge/grade/1caaead12a1b4cdd8c66ebc4afee9ad9)](https://www.codacy.com/app/Managlea/EntityManager)  
[![Build Status](https://travis-ci.org/managlea/EntityManager.svg?branch=master)](https://travis-ci.org/managlea/EntityManager) [ ![Codeship Status for managlea/EntityManager](https://codeship.com/projects/7acdf9d0-cf5f-0133-71d1-52aba3b897dd/status?branch=master)](https://codeship.com/projects/141212) [![Circle CI](https://circleci.com/gh/managlea/EntityManager/tree/master.svg?style=svg)](https://circleci.com/gh/managlea/EntityManager/tree/master)

Wrapped on top of different database connectors to provide general interface. Currently supports only Doctrine ORM.

##Basic usage (DoctrineEntityManager):
```php
// Create directly
$entityManager = DoctrineEntityManager::initialize($dbParams, $config);

// Create using factory (recommended)
$entityManager = EntityManagerFactory::create('DoctrineEntityManager');

// Get single entity
$entity = $entityManager->get('Entities\Product', 1);

// Get single entity (with additional criterias)
$entity = $entityManager->get('Entities\Product', 1, array('user_id' => 2));

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
