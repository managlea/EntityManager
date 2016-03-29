# EntityManager
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/managlea/EntityManager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/managlea/EntityManager/?branch=master)  
[![Code Climate](https://codeclimate.com/github/managlea/EntityManager/badges/gpa.svg)](https://codeclimate.com/github/managlea/EntityManager) [![Test Coverage](https://codeclimate.com/github/managlea/EntityManager/badges/coverage.svg)](https://codeclimate.com/github/managlea/EntityManager/coverage)  
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fccb20e0-d90c-4801-a534-88845faea1ec/mini.png)](https://insight.sensiolabs.com/projects/fccb20e0-d90c-4801-a534-88845faea1ec)  
[![Codacy Badge](https://api.codacy.com/project/badge/grade/1caaead12a1b4cdd8c66ebc4afee9ad9)](https://www.codacy.com/app/Managlea/EntityManager)  
[![Build Status](https://travis-ci.org/managlea/EntityManager.svg?branch=master)](https://travis-ci.org/managlea/EntityManager) [![Circle CI](https://circleci.com/gh/managlea/EntityManager/tree/master.svg?style=svg)](https://circleci.com/gh/managlea/EntityManager/tree/master)  
[![PHP-Eye](http://php-eye.com/badge/managlea/entity-manager/tested.svg)](http://php-eye.com/package/managlea/entity-manager)

Wrapped on top of different database connectors to provide general interface. Currently supports only Doctrine ORM.

##Basic usage (DoctrineEntityManager):
```php
// Create directly
$em = new DoctrineEntityManager($entityManager);

// Create using factory (recommended)
$em = new EntityManagerFactory($containerBuilder)->create('doctrine_entity_manager');

// Get single entity
$entity = $em->get('Entities\Product', 1);

// Get single entity (with additional criterias)
$entity = $em->get('Entities\Product', 1, array('user_id' => 2));

// Get collection
$entityCollection = $em->getCollection('Entities\Product');

// Create new entity
$newEntity = $em->create('Entities\Product', array('name' => 'foo'));

// Update newly created entity
$updatedEntity = $em->update('Entities\Product', $newEntity->getId(), array('name' => 'bar'));

// Delete update entity
$em->delete('Entities\Product', $updatedEntity->getId());
```

As all existing Doctrine functionality is left intact you are also able to use all Doctrine ORM build in methods:
```php
$em = new DoctrineEntityManager($entityManager);

$user = new User;
$user->setName('Mr.Right');

$em->persist($user);
$em->flush();
```
