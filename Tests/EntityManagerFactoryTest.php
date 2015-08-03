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
        EntityManagerFactory::create('foo');
    }

    /**
     * @test
     */
    public function createFromResourceName()
    {
        $entityManager = EntityManagerFactory::create('DoctrineEntityManager');
        $this->assertTrue($entityManager instanceof EntityManagerInterface);
    }
}
