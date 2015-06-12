<?php

namespace Managlea\Tests;


use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Managlea\Component\EntityManager\DoctrineEntityManager;
use Managlea\Tests\Models\Product;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineEntityManager
     */
    protected $entityManager;

    /**
     * @var SchemaTool
     */
    protected $schemaTool;

    const SCHEMA_PRODUCT = 'Managlea\Tests\Models\Product';

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

        $this->schemaTool = new SchemaTool($this->entityManager);

        $this->schemaTool->dropSchema($this->getSchemaClasses());
        $this->schemaTool->createSchema($this->getSchemaClasses());
    }

    public function tearDown()
    {
        $this->schemaTool->dropSchema($this->getSchemaClasses());
    }

    private function getSchemaClasses()
    {
        $classes = array(
            $this->entityManager->getClassMetadata(self::SCHEMA_PRODUCT)
        );

        return $classes;
    }

    /**
     * @return Product
     */
    protected function createProduct()
    {
        $product = new Product();
        $product->setName(rand(1, 9) . uniqid());

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    protected function createProductsCollection($min = 25, $max = 35)
    {
        $noOfProducts = rand($min, $max);

        // Generate random objects
        $products = array();
        for ($i = 1; $i <= $noOfProducts; $i++) {
            $products[] = $this->createProduct();
        }

        return $products;
    }
}