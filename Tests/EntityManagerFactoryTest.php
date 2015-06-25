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
        EntityManagerFactory::createFromResourceName('foo');
    }
    /**
     * @test
     * @expectedException \Exception
     */
    public function createFromResourceNameNotImplemented()
    {
        EntityManagerFactory::createFromResourceName('baz');
    }
    /**
     * @test
     */
    public function createFromResourceName()
    {
        $entityManager = EntityManagerFactory::createFromResourceName('product');
        $this->assertTrue($entityManager instanceof EntityManagerInterface);
    }
}
