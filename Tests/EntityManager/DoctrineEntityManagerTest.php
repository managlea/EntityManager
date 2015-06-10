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
        $entity = $this->entityManager->findEntity('Managlea\Tests\Models\Product', -1);

        $this->assertEquals(false, $entity);
    }

    /**
     * @test
     */
    public function findEntitySuccess()
    {
        $product = $this->createProduct();
        $entity = $this->entityManager->findEntity('Managlea\Tests\Models\Product', $product->getId());

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
        $collection = $this->entityManager->findEntityCollection('Managlea\Tests\Models\Product');

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
            $collection = $this->entityManager->findEntityCollection('Managlea\Tests\Models\Product', array(), $limit,
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
        $collection = $this->entityManager->findEntityCollection('Managlea\Tests\Models\Product', $filters, 1, 0);
        $this->assertEquals(0, count($collection));

        $products = $this->createProductsCollection();
        $noOfProducts = count($products);

        // Get random offset
        $productOffset = rand(0, $noOfProducts);
        $product = $products[$productOffset];

        // Set filter based on random object
        $filters = array(
            'name' => $product->getName()
        );

        $collection = $this->entityManager->findEntityCollection('Managlea\Tests\Models\Product', $filters, 10, 0);
        $this->assertEquals(1, count($collection));

        $result = current($collection);
        $this->assertEquals($result->getName(), $product->getName());

    }

    /**
     * @test
     */
    public function findEntityCollectionOrderASC()
    {
        $products = $this->createProductsCollection();
        $ordered = array();

        foreach ($products as $product) {
            $ordered[] = $product->getName();
        }

        sort($ordered);
        $order = array(
            'name' => 'ASC'
        );

        $collection = $this->entityManager->findEntityCollection('Managlea\Tests\Models\Product', array(), 1, null, $order);
        $result = current($collection);
        $this->assertEquals($result->getName(), $ordered[0]);
    }

    /**
     * @test
     */
    public function findEntityCollectionOrderDESC()
    {
        $products = $this->createProductsCollection();
        $ordered = array();

        foreach ($products as $product) {
            $ordered[] = $product->getName();
        }

        rsort($ordered);
        $order = array(
            'name' => 'DESC'
        );

        $collection = $this->entityManager->findEntityCollection('Managlea\Tests\Models\Product', array(), 1, null, $order);
        $result = current($collection);
        $this->assertEquals($result->getName(), $ordered[0]);
    }

}
