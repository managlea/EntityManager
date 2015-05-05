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
}
