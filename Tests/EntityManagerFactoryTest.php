<?php

namespace Managlea\Tests;


use Managlea\Component\EntityManagerFactory;
use Managlea\Component\EntityManagerFactoryInterface;
use Managlea\Component\EntityManagerInterface;

class EntityManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Exception
     */
    public function createFromResourceNameNoMapping()
    {
        $entityManagerFactory = new EntityManagerFactory;
        $entityManagerFactory->create('foo');
    }

    /**
     * @test
     */
    public function createFromResourceName()
    {
        $entityManagerFactory = new EntityManagerFactory;
        $this->assertTrue($entityManagerFactory instanceof EntityManagerFactoryInterface);

        $entityManager = $entityManagerFactory->create('DoctrineEntityManager');
        $this->assertTrue($entityManager instanceof EntityManagerInterface);
    }
}
