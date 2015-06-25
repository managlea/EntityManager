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
    public function createFromResourceNameNotImplemented()
    {
        EntityManagerFactory::createForResource('baz');
    }
    /**
     * @test
     */
    public function createFromResourceName()
    {
        $entityManager = EntityManagerFactory::createForResource('product');
        $this->assertTrue($entityManager instanceof EntityManagerInterface);

        $entityManager = EntityManagerFactory::createForResource('bar');
        $this->assertTrue($entityManager instanceof EntityManagerInterface);
    }
}
