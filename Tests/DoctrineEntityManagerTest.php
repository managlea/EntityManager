<?php

namespace Managlea\Tests;


use Managlea\Component\EntityManager\DoctrineEntityManager;
use Managlea\Component\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

class DoctrineEntityManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException Doctrine\ORM\ORMException
     */
    public function entityManager()
    {
        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => '',
            'dbname'   => 'foo',
        );

        $conf = Setup::createConfiguration(true);
        $entityManager = DoctrineEntityManager::create($dbParams, $conf);

        $this->assertEquals(true, $entityManager instanceof EntityManagerInterface);
    }
}
