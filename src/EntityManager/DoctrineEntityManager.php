<?php

declare(strict_types=1);

namespace Managlea\Component\EntityManager;


use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface as DoctrineEntityManagerInterface;
use Managlea\Component\EntityManagerInterface;

class DoctrineEntityManager extends EntityManagerDecorator implements EntityManagerInterface
{
    /**
     * DoctrineEntityManager constructor.
     * @param DoctrineEntityManagerInterface $wrapped
     */
    public function __construct(DoctrineEntityManagerInterface $wrapped)
    {
        parent::__construct($wrapped);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $objectName, int $id, array $criteria = array())
    {
        $repository = $this->getRepository($objectName);

        $criteria['id'] = $id;
        $entityObject = $repository->findOneBy($criteria);

        if (!$entityObject) {
            return false;
        }

        return $entityObject;
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection(string $objectName, array $filters = array(), int $limit = 20, int $offset = 0, array $order = null) : array
    {
        $repository = $this->getRepository($objectName);
        $collection = $repository->findBy($filters, $order, $limit, $offset);

        return $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $objectName, array $data)
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
    public function update(string $objectName, int $id, array $data)
    {
        return $this->executeActionOnEntity('update', $objectName, $id, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $objectName, int $id) : bool
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
    private function executeActionOnEntity(string $method, string $objectName, int $id, array $data = null)
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
    public static function createDetachedEntity(string $objectName, array $data)
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
    public static function formatStringToMethodName(string $input) : string
    {
        $methodName = implode('', array_map('ucfirst', explode('_', strtolower($input))));

        return $methodName;
    }
}
