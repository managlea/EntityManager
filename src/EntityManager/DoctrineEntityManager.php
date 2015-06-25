<?php

namespace Managlea\Component\EntityManager;


use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\Configuration;
use Doctrine\Common\EventManager;
use Managlea\Component\EntityManagerInterface;

class DoctrineEntityManager extends EntityManagerDecorator implements EntityManagerInterface
{
    /**
     * @var
     */
    private $objectName;

    /**
     * {@inheritDoc}
     */
    public function setObjectName($objectName)
    {
        $this->objectName = $objectName;
    }

    /**
     * {@inheritDoc}
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * {@inheritDoc}
     */
    public static function initialize($conn, Configuration $config, EventManager $eventManager = null)
    {
        return new self(\Doctrine\ORM\EntityManager::create($conn, $config, $eventManager));
    }

    /**
     * {@inheritDoc}
     */
    public function get($objectName, $id)
    {
        $repository = $this->getRepository($objectName);
        $objectName = $repository->find($id);
        if (!$objectName) {
            return false;
        }

        return $objectName;
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection($objectName, array $filters = array(), $limit = 20, $offset = 0, $order = null)
    {
        $repository = $this->getRepository($objectName);
        $collection = $repository->findBy($filters, $order, $limit, $offset);

        return $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function create($objectName, array $data)
    {
        $detachedEntity = self::createDetachedEntity($objectName, $data);

        $entity = $this->merge($detachedEntity);
        $this->persist($entity);
        $this->flush();

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function update($objectName, $id, array $data)
    {
        return $this->executeActionOnEntity('update', $objectName, $id, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($objectName, $id)
    {
        return (bool)$this->executeActionOnEntity('remove', $objectName, $id);
    }

    /**
     * @param string $method
     * @param string $objectName
     * @param int $id
     * @param array $data
     * @return mixed
     */
    private function executeActionOnEntity($method, $objectName, $id, array $data = null)
    {
        $entityObject = $this->get($objectName, $id);

        if (!$entityObject) {
            return false;
        }

        if ($method == 'update' && is_array($data)) {
            self::updateEntityFromArray($entityObject, $data);
        } elseif ($method == 'remove') {
            $this->remove($entityObject);
        }

        $this->flush();

        if ($method == 'update') {
            return $entityObject;
        }

        return true;
    }

    /**
     * Creates new entity based on object name sets object
     * parameter from data
     * @param string $objectName
     * @param array $data
     * @return mixed
     */
    public static function createDetachedEntity($objectName, array $data)
    {
        $detachedEntity = new $objectName;

        self::updateEntityFromArray($detachedEntity, $data);

        return $detachedEntity;
    }

    /**
     * Updates Entity from data calling setters based
     * on data keys
     * @param object $entity
     * @param array $data
     * @return mixed
     */
    public static function updateEntityFromArray($entity, array $data)
    {
        foreach ($data as $field => $value) {
            $method = 'set' . self::formatStringToMethodName($field);
            if (method_exists($entity, $method)) {
                $entity->$method($value);
            }
        }

        return $entity;
    }

    /**
     * Generates camel-case method names from string
     * @param string $input
     * @return string
     */
    public static function formatStringToMethodName($input)
    {
        $methodName = implode('', array_map('ucfirst', explode('_', strtolower($input))));

        return $methodName;
    }
}
