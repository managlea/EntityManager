<?php

namespace Managlea\Tests;


use Managlea\Component\EntityManagerFactory;
use Managlea\Component\EntityManagerInterface;

class EntityManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Exception
     */
    public function createFromResourceNameNoMapping()
    {
        EntityManagerFactory::createForResource('foo');
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function createFromResourceNameNoObjectName()
    {
        EntityManagerFactory::createForResource('baz');
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function createFromResourceNameNotImplemented()
    {
        EntityManagerFactory::createForResource('zoo');
    }

    /**
     * @test
     */
    public function createFromResourceName()
    {
        $entityManager = EntityManagerFactory::createForResource('product');
        $this->assertTrue($entityManager instanceof EntityManagerInterface);
        $this->assertEquals($entityManager->getObjectName(), 'Entities\Product');

        $entityManager = EntityManagerFactory::createForResource('bar');
        $this->assertTrue($entityManager instanceof EntityManagerInterface);
        $this->assertEquals($entityManager->getObjectName(), 'Entities\Product');
    }
}
