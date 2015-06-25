<?php

namespace Managlea\Component;


use Doctrine\ORM\Tools\Setup;
use Managlea\Component\EntityManager\DoctrineEntityManager;
use Symfony\Component\Yaml\Yaml;

class EntityManagerFactory implements EntityManagerFactoryInterface
{
    /**
     * @param string $resourceName
     * @return EntityManagerInterface
     * @throws \Exception
     */
    public static function createFromResourceName($resourceName)
    {
        $conf = self::getConfig();
        $defaultEntityManager = $conf['default_entity_manager'];

        if (!array_key_exists($resourceName, $conf['mapping'])) {
            throw new \Exception(sprintf('Mapping configuration missing for resource: "%s"', $resourceName));
        }

        $resourceConf = $conf['mapping'][$resourceName];

        if (is_array($resourceConf)) {
            $entityManagerName = $resourceConf['entity_manager'];
        } else {
            $entityManagerName = $defaultEntityManager;
        }

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
                break;
        }

        if (!$entityManager instanceof EntityManagerInterface) {
            throw new \Exception(sprintf('$entityManager not instance of EntityManagerInterface'));
        }

        return $entityManager;
    }

    private static function getConfig()
    {
        $configValues = Yaml::parse(file_get_contents(__DIR__ . '/../config/resource_mapping.yml'));

        return $configValues;
    }
}
