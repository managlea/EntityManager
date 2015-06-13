<?php

namespace Managlea\Tests\EntityManager;


use Managlea\Component\EntityManagerInterface;
use Managlea\Tests\BaseTestCase;

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
    public function findEntityException()
    {
        $product = $this->createProduct();
        $this->entityManager->findEntity('foo', $product->getId());
    }

    /**
     * @test
     */
    public function findEntityNoEntity()
    {
        $entity = $this->entityManager->findEntity(self::SCHEMA_PRODUCT, -1);

        $this->assertEquals(false, $entity);
    }

    /**
     * @test
     */
    public function findEntitySuccess()
    {
        $product = $this->createProduct();
        $entity = $this->entityManager->findEntity(self::SCHEMA_PRODUCT, $product->getId());

        $this->assertEquals($product->getName(), $entity->getName());
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function findEntityCollectionException()
    {
        $this->entityManager->findEntityCollection('foo');
    }

    /**
     * @test
     */
    public function findEntityCollectionNoEntities()
    {
        $collection = $this->entityManager->findEntityCollection(self::SCHEMA_PRODUCT);

        $this->assertEquals(0, count($collection));
    }

    /**
     * @test
     *
     * Will generate new objects, save them into DB and later
     * try to retrieve them by using offset and limit
     */
    public function findEntityCollectionSuccess()
    {
        $products = $this->createProductsCollection();
        $noOfProducts = count($products);

        $limit = rand(4, 8);
        $iterations = ceil($noOfProducts / $limit);

        for ($i = 1; $i <= $iterations; $i++) {
            $offset = ($i > 1) ? ($i - 1) * $limit : 0;
            $collection = $this->entityManager->findEntityCollection(self::SCHEMA_PRODUCT, array(), $limit,
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
    public function findEntityCollectionFilters()
    {
        $filters = array(
            'name' => 'random'
        );
        $collection = $this->entityManager->findEntityCollection(self::SCHEMA_PRODUCT, $filters, 1, 0);
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

        $collection = $this->entityManager->findEntityCollection(self::SCHEMA_PRODUCT, $filters, 10, 0);
        $this->assertEquals(1, count($collection));

        $result = current($collection);
        $this->assertEquals($result->getName(), $product->getName());

    }

    /**
     * @test
     */
    public function findEntityCollectionOrder()
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

            $collection = $this->entityManager->findEntityCollection(self::SCHEMA_PRODUCT, array(), 1, null,
                $order);
            $result = current($collection);

            $this->assertEquals($result->getName(), $ordered[0]);
        }
    }

    /**
     * @test
     */
    public function createEntity()
    {
        $data = array('name' => uniqid());
        $entity = $this->entityManager->createEntity(self::SCHEMA_PRODUCT, $data);
        $this->assertEquals($entity->getName(), $data['name']);

        $product = $this->entityManager->findEntity(self::SCHEMA_PRODUCT, $entity->getId());
        $this->assertEquals($product->getName(), $entity->getName());
    }

    /**
     * @test
     */
    public function updateEntity()
    {
        /**
         * Update entity which does not exist
         */
        $updateResult = $this->entityManager->updateEntity(self::SCHEMA_PRODUCT, 1, array());
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
        $collection = $this->entityManager->findEntityCollection(self::SCHEMA_PRODUCT, $filters);
        $this->assertTrue(count($collection) == 1);

        /**
         * Update the name
         */
        $newName = uniqid();
        $this->entityManager->updateEntity(self::SCHEMA_PRODUCT, $product->getId(), array('name' => $newName));

        /**
         * Search for original name
         */
        $collection = $this->entityManager->findEntityCollection(self::SCHEMA_PRODUCT, $filters);
        $this->assertTrue(count($collection) == 0);

        /**
         * Search for id and check for updated name
         */
        $entity = $this->entityManager->findEntity(self::SCHEMA_PRODUCT, $product->getId());
        $this->assertEquals($entity->getName(), $newName);
    }

    /**
     * @test
     */
    public function removeEntity()
    {
        $removeResult = $this->entityManager->removeEntity(self::SCHEMA_PRODUCT, rand(1, 9999));
        $this->assertFalse($removeResult);

        $data = array('name' => uniqid());
        $newEntity = $this->entityManager->createEntity(self::SCHEMA_PRODUCT, $data);

        $entityId = $newEntity->getId();

        $entity = $this->entityManager->findEntity(self::SCHEMA_PRODUCT, $entityId);
        $this->assertEquals($data['name'], $entity->getName());

        $removeResult = $this->entityManager->removeEntity(self::SCHEMA_PRODUCT, $entityId);
        $this->assertTrue($removeResult);

        $entity = $this->entityManager->findEntity(self::SCHEMA_PRODUCT, $entityId);
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

        $entity = $this->entityManager->createDetachedEntity(self::SCHEMA_PRODUCT, $data);
        $this->assertEquals($data['namE'], $entity->getName());
        $this->assertEquals($data['dAte_of_bIrth'], $entity->getDateOfBirth());
    }
}
