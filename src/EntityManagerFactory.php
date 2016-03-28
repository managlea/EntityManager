<?php

declare(strict_types=1);

namespace Managlea\Component;


use Doctrine\ORM\Tools\Setup;
use Managlea\Component\EntityManager\DoctrineEntityManager;
use Symfony\Component\Yaml\Yaml;

class EntityManagerFactory implements EntityManagerFactoryInterface
{
    /**
     * @param string $entityManagerName
     * @return EntityManagerInterface
     * @throws \Exception
     */
    public function create(string $entityManagerName) : EntityManagerInterface
    {
        switch ($entityManagerName) {
            case 'DoctrineEntityManager':
                $isDevMode = true;
                $paths = array(__DIR__ . "/Models");

                $dbParams = Yaml::parse(file_get_contents(__DIR__ . '/../config/database.yml'));
                $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);

                $entityManager = DoctrineEntityManager::initialize($dbParams['parameters'], $config);
                break;
            default:
                throw new \Exception(sprintf('EntityManager of type %s not implemented', $entityManagerName));
        }

        return $entityManager;
    }
}
