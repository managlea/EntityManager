<?php

namespace Managlea\Component;


use Symfony\Component\Yaml\Yaml;

/**
 * Class ResourceMapper
 * @package Managlea\Component
 */
final class ResourceMapper
{
    /**
     * @var ResourceMapper The reference to *ResourceMapper* instance of this class
     */
    private static $instance;

    /**
     * @var array
     */
    private $mapping;

    /**
     * @var
     */
    private $defaultEntityManager;

    /**
     * Returns the *ResourceMapper* instance of this class.
     *
     * @return ResourceMapper The *Singleton* instance.
     */
    private static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     *
     */
    private function __construct()
    {
        $conf = self::setConfig();
        $this->defaultEntityManager = $conf['default_entity_manager'];
        $this->mapping = $conf['mapping'];
    }

    /**
     * @param $resourceName
     * @return mixed
     * @throws \Exception
     */
    private function getResourceMappingConf($resourceName)
    {
        $instance = self::getInstance();

        if (!array_key_exists($resourceName, $instance->mapping)) {
            throw new \Exception(sprintf('Mapping configuration missing for resource: "%s"', $resourceName));
        }

        return $instance->mapping[$resourceName];
    }

    /**
     * @return array
     */
    private static function setConfig()
    {
        $configValues = Yaml::parse(file_get_contents(__DIR__ . '/../config/resource_mapping.yml'));

        return $configValues;
    }

    /**
     * @param $resourceName
     * @return mixed
     * @throws \Exception
     */
    public static function getEntityManager($resourceName)
    {
        $instance = self::getInstance();
        $resourceConf = $instance->getResourceMappingConf($resourceName);

        if (is_array($resourceConf)) {
            $entityManagerName = $resourceConf['entity_manager'];
        } else {
            $entityManagerName = $instance->defaultEntityManager;
        }

        return $entityManagerName;
    }

    /**
     * @param $resourceName
     * @return mixed
     * @throws \Exception
     */
    public static function getObjectName($resourceName)
    {
        $instance = self::getInstance();
        $resourceConf = $instance->getResourceMappingConf($resourceName);

        if (is_array($resourceConf)) {
            if (!array_key_exists('object_name', $resourceConf)) {
                $objectName = $resourceName;
            } else {
                $objectName = $resourceConf['object_name'];
            }
        } else {
            $objectName = $resourceConf;
        }

        return $objectName;
    }
}
