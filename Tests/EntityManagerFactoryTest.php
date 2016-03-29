<?php

namespace Managlea\Tests;


use Managlea\Component\EntityManagerFactoryInterface;
use Managlea\Component\EntityManagerInterface;

class EntityManagerFactoryTest extends BaseTestCase
{
    /**
     * @test
     * @expectedException \Exception
     */
    public function createFromResourceNameNoMapping()
    {
        $entityManagerFactory = $this->getEntityManagerFactory();
        $entityManagerFactory->create('foo');
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function createFromResourceNameNoService()
    {
        $entityManagerFactory = $this->getEntityManagerFactory();
        $this->assertTrue($entityManagerFactory instanceof EntityManagerFactoryInterface);

        $entityManagerFactory->create('foo');
    }

    /**
     * @test
     */
    public function createFromResourceName()
    {
        $entityManagerFactory = $this->getEntityManagerFactory();
        $this->assertTrue($entityManagerFactory instanceof EntityManagerFactoryInterface);

        $entityManager = $entityManagerFactory->create('doctrine_entity_manager');
        $this->assertTrue($entityManager instanceof EntityManagerInterface);
    }
}
