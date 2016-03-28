<?php

namespace Managlea\Tests\EntityManager;


use Managlea\Component\EntityManager\DoctrineEntityManager;
use Managlea\Component\EntityManagerInterface;
use Managlea\Tests\BaseTestCase;

/**
 * Class DoctrineEntityManagerTest
 * @package Managlea\Tests\EntityManager
 */
class DoctrineEntityManagerTest extends BaseTestCase
{
    /**
     * @test
     */
    public function entityManager()
    {
        $this->assertEquals(true, $this->entityManager instanceof EntityManagerInterface);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function getException()
    {
        $product = $this->createProduct();
        $this->entityManager->get('foo', $product->getId());
    }

    /**
     * @test
     */
    public function getNoEntity()
    {
        $entity = $this->entityManager->get(self::SCHEMA_PRODUCT, -1);
        $this->assertEquals(false, $entity);

        $product = $this->createProduct();
        $criteria = array('dateOfBirth' => '1970-01-02');
        $entity = $this->entityManager->get(self::SCHEMA_PRODUCT, $product->getId(), $criteria);
        $this->assertEquals(false, $entity);
    }

    /**
     * @test
     */
    public function getSuccess()
    {
        $product = $this->createProduct();
        $entity = $this->entityManager->get(self::SCHEMA_PRODUCT, $product->getId());
        $this->assertEquals($product->getName(), $entity->getName());

        $product = $this->createProduct();
        $criteria = array();
        $entity = $this->entityManager->get(self::SCHEMA_PRODUCT, $product->getId(), $criteria);
        $this->assertEquals($product->getName(), $entity->getName());

        $product = $this->createProduct();
        $criteria = array('dateOfBirth' => '1970-01-01');
        $entity = $this->entityManager->get(self::SCHEMA_PRODUCT, $product->getId(), $criteria);
        $this->assertEquals($product->getName(), $entity->getName());
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function getCollectionException()
    {
        $this->entityManager->getCollection('foo');
    }

    /**
     * @test
     */
    public function getCollectionNoEntities()
    {
        $collection = $this->entityManager->getCollection(self::SCHEMA_PRODUCT);
        $this->assertEquals(0, count($collection));
    }

    /**
     * @test
     *
     * Will generate new objects, save them into DB and later
     * try to retrieve them by using offset and limit
     */
    public function getCollectionSuccess()
    {
        $products = $this->createProductsCollection();
        $noOfProducts = count($products);

        $limit = rand(4, 8);
        $iterations = ceil($noOfProducts / $limit);

        for ($i = 1; $i <= $iterations; $i++) {
            $offset = ($i > 1) ? ($i - 1) * $limit : 0;
            $collection = $this->entityManager->getCollection(self::SCHEMA_PRODUCT, array(), $limit,
                $offset);

            $first = current($collection);
            $firstPos = $offset;
            $this->assertEquals($first->getName(), $products[$firstPos]->getName());

            $last = end($collection);
            $lastPos = (count($collection) < $limit) ? $offset + count($collection) - 1 : ($offset + $limit - 1);
            $this->assertEquals($last->getName(), $products[$lastPos]->getName());
        }
    }

    /**
     * @test
     */
    public function getCollectionFilters()
    {
        $filters = array(
            'name' => 'random'
        );
        $collection = $this->entityManager->getCollection(self::SCHEMA_PRODUCT, $filters, 1, 0);
        $this->assertEquals(0, count($collection));

        $products = $this->createProductsCollection();
        $noOfProducts = count($products);

        // Get random offset
        $productOffset = rand(0, $noOfProducts - 1);
        $product = $products[$productOffset];

        // Set filter based on random object
        $filters = array(
            'name' => $product->getName()
        );

        $collection = $this->entityManager->getCollection(self::SCHEMA_PRODUCT, $filters, 10, 0);
        $this->assertEquals(1, count($collection));

        $result = current($collection);
        $this->assertEquals($result->getName(), $product->getName());

    }

    /**
     * @test
     */
    public function getCollectionOrder()
    {
        $orderTypes = array('ASC' => 'sort', 'DESC' => 'rsort');
        foreach ($orderTypes as $orderType => $sortMethod) {
            $products = $this->createProductsCollection();
            $ordered = array();

            foreach ($products as $product) {
                $ordered[] = $product->getName();
            }

            $sortMethod($ordered);
            $order = array(
                'name' => $orderType
            );

            $collection = $this->entityManager->getCollection(self::SCHEMA_PRODUCT, array(), 1, 0,
                $order);
            $result = current($collection);

            $this->assertEquals($result->getName(), $ordered[0]);
        }
    }

    /**
     * @test
     */
    public function create()
    {
        $data = array('name' => uniqid());
        $entity = $this->entityManager->create(self::SCHEMA_PRODUCT, $data);
        $this->assertEquals($entity->getName(), $data['name']);

        $product = $this->entityManager->get(self::SCHEMA_PRODUCT, $entity->getId());
        $this->assertEquals($product->getName(), $entity->getName());
    }

    /**
     * @test
     */
    public function update()
    {
        /**
         * Update entity which does not exist
         */
        $updateResult = $this->entityManager->update(self::SCHEMA_PRODUCT, 1, array());
        $this->assertFalse($updateResult);

        /**
         * Create entity
         */
        $product = $this->createProduct();

        /**
         * Search entity by name
         */
        $filters = array(
            'name' => $product->getName()
        );
        $collection = $this->entityManager->getCollection(self::SCHEMA_PRODUCT, $filters);
        $this->assertTrue(count($collection) == 1);

        /**
         * Update the name
         */
        $newName = uniqid();
        $entity = $this->entityManager->update(self::SCHEMA_PRODUCT, $product->getId(),
            array('name' => $newName));
        $this->assertEquals($newName, $entity->getName());

        /**
         * Search for original name
         */
        $collection = $this->entityManager->getCollection(self::SCHEMA_PRODUCT, $filters);
        $this->assertTrue(count($collection) == 0);

        /**
         * Search for id and check for updated name
         */
        $entity = $this->entityManager->get(self::SCHEMA_PRODUCT, $product->getId());
        $this->assertEquals($entity->getName(), $newName);
    }

    /**
     * @test
     */
    public function remove()
    {
        $removeResult = $this->entityManager->delete(self::SCHEMA_PRODUCT, rand(1, 9999));
        $this->assertFalse($removeResult);

        $data = array('name' => uniqid());
        $newEntity = $this->entityManager->create(self::SCHEMA_PRODUCT, $data);

        $entityId = $newEntity->getId();

        $entity = $this->entityManager->get(self::SCHEMA_PRODUCT, $entityId);
        $this->assertEquals($data['name'], $entity->getName());

        $removeResult = $this->entityManager->delete(self::SCHEMA_PRODUCT, $entityId);
        $this->assertTrue($removeResult);

        $entity = $this->entityManager->get(self::SCHEMA_PRODUCT, $entityId);
        $this->assertFalse($entity);
    }

    /**
     * @test
     */
    public function createDetachedEntity()
    {
        $data = array(
            'namE' => 'foo',
            'dAte_of_bIrth' => "bar"
        );

        $entity = DoctrineEntityManager::createDetachedEntity(self::SCHEMA_PRODUCT, $data);
        $this->assertEquals($data['namE'], $entity->getName());
        $this->assertEquals($data['dAte_of_bIrth'], $entity->getDateOfBirth());
    }
}
