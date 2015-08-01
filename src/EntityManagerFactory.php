<?php

namespace Managlea\Component;


use Doctrine\ORM\Tools\Setup;
use Managlea\Component\EntityManager\DoctrineEntityManager;

class EntityManagerFactory implements EntityManagerFactoryInterface
{
    /**
     * @param string $resourceName
     * @return EntityManagerInterface
     * @throws \Exception
     */
    public static function createForResource($resourceName)
    {
        $entityManagerName = ResourceMapper::getEntityManager($resourceName);

        switch ($entityManagerName) {
            case 'DoctrineEntityManager':
                $isDevMode = true;
                $paths = array(__DIR__ . "/Models");

                $dbParams = array(
                    'driver' => 'pdo_mysql',
                    'user' => 'root',
                    'password' => '',
                    'dbname' => 'test',
                );

                $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
                $entityManager = DoctrineEntityManager::initialize($dbParams, $config);
                break;
            default:
                throw new \Exception(sprintf('EntityManager of type %s not implemented', $entityManagerName));
        }

        return $entityManager;
    }
}
