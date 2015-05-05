<?php

namespace Managlea\Tests;


use Managlea\Component\EntityManager\DoctrineEntityManager;
use Managlea\Component\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Managlea\Tests\Models\Product;

class DoctrineEntityManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineEntityManager
     */
    protected $entityManager;

    public function setUp()
    {
        $isDevMode = true;
        $paths = array(__DIR__ . "/Models");

        $dbParams = array(
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => '',
            'dbname' => 'test',
        );

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $this->entityManager = DoctrineEntityManager::create($dbParams, $config);

        $this->truncateDB();
    }

    public function tearDown()
    {
        $this->truncateDB();
    }

    private function truncateDB()
    {
        $this->entityManager->getConnection()->executeQuery("TRUNCATE product");
    }

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
        $product = new Product();
        $product->setName(uniqid());

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $productId = $product->getId();

        $this->entityManager->findEntity('Managlea\Tests\Models\Products', $productId);
    }

    /**
     * @test
     */
    public function findEntityNoEntity()
    {
        $product = new Product();
        $product->setName(uniqid());

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $entity = $this->entityManager->findEntity('Managlea\Tests\Models\Product', -1);

        $this->assertEquals(false, $entity);
    }

    /**
     * @test
     */
    public function findEntitySuccess()
    {
        $product = new Product();
        $product->setName(uniqid());

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $entity = $this->entityManager->findEntity('Managlea\Tests\Models\Product', $product->getId());

        $this->assertEquals($product->getName(), $entity->getName());
    }
}
