<?php

namespace Managlea\Tests;


use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Managlea\Component\EntityManager\DoctrineEntityManager;
use Managlea\Tests\Models\Product;
use Symfony\Component\Yaml\Yaml;

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

    /**
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp()
    {
        $paths = array(__DIR__ . "/Models");
        $config = Setup::createAnnotationMetadataConfiguration($paths, true);

        $connectionConfig = $this->getConfig();
        $this->entityManager = DoctrineEntityManager::initialize($connectionConfig['parameters'], $config);

        $this->schemaTool = new SchemaTool($this->entityManager);

        $this->schemaTool->dropSchema($this->getSchemaClasses());
        $this->schemaTool->createSchema($this->getSchemaClasses());
    }

    public function tearDown()
    {
        $this->schemaTool->dropSchema($this->getSchemaClasses());
    }

    public function getConfig()
    {
        return Yaml::parse(file_get_contents(__DIR__ . '/../config/database.yml'));
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
        $product->setDateOfBirth('1970-01-01');

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