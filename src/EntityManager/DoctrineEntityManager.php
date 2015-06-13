<?php

namespace Managlea\Component\EntityManager;


use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\Configuration;
use Doctrine\Common\EventManager;
use Managlea\Component\EntityManagerInterface;

class DoctrineEntityManager extends EntityManagerDecorator implements EntityManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        return new self(\Doctrine\ORM\EntityManager::create($conn, $config, $eventManager));
    }

    /**
     * @param $entity
     * @param int $resourceId
     * @return bool|null|object
     * @throws \Exception
     */
    public function findEntity($entity, $resourceId)
    {
        $repository = $this->getRepository($entity);
        $entity = $repository->find($resourceId);
        if (!$entity) {
            return false;
        }

        return $entity;
    }

    /**
     * @param $entity
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @return array
     * @throws \Exception
     */
    public function findEntityCollection($entity, array $filters = array(), $limit = 20, $offset = 0, $order = null)
    {
        $repository = $this->getRepository($entity);
        $collection = $repository->findBy($filters, $order, $limit, $offset);

        return $collection;
    }

    public function createEntity($entity, array $data)
    {
        $detachedEntity = $this->createDetachedEntity($entity, $data);

        $entity = $this->merge($detachedEntity);
        $this->persist($entity);
        $this->flush();

        return $entity;
    }

    /**
     * @param string $entity
     * @param int $resourceId
     * @param array $data
     * @return bool
     */
    public function updateEntity($entity, $resourceId, array $data)
    {
        $entityObject = $this->findEntity($entity, $resourceId);

        if (!$entityObject) {
            return false;
        }

        self::updateEntityFromData($entityObject, $data);

        $this->flush();

        return $entityObject;
    }

    /**
     * @param string $entity
     * @param int $resourceId
     * @return bool
     */
    public function removeEntity($entity, $resourceId)
    {
        $entityObject = $this->findEntity($entity, $resourceId);

        if (!$entityObject) {
            return false;
        }

        $this->remove($entityObject);
        $this->flush();

        return true;
    }

    /**
     * @param string $entity
     * @param array $data
     * @return mixed
     */
    public function createDetachedEntity($entity, array $data)
    {
        $detachedEntity = new $entity;

        self::updateEntityFromData($detachedEntity, $data);

        return $detachedEntity;
    }

    /**
     * @param object $entity
     * @param array $data
     * @return mixed
     */
    public static function updateEntityFromData($entity, array $data)
    {
        foreach ($data as $field => $value) {
            $method = 'set' . self::formatInputToMethodName($field);
            if (method_exists($entity, $method)) {
                $entity->$method($value);
            }
        }

        return $entity;
    }

    /**
     * @param string $input
     * @return string
     */
    public static function formatInputToMethodName($input)
    {
        $methodName = implode('', array_map('ucfirst', explode('_', strtolower($input))));

        return $methodName;
    }
}
