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

    public function updateEntity($resourceId, array $data)
    {
        throw new \Exception('Method not implemented');
    }

    public function removeEntity($resourceId)
    {
        throw new \Exception('Method not implemented');
    }

    /**
     * @param string $entity
     * @param array $data
     * @return mixed
     */
    public function createDetachedEntity($entity, array $data)
    {
        $detachedEntity = new $entity;
        foreach ($data as $field => $value) {
            $method = 'set' . $this->formatInputToMethodName($field);
            if (method_exists($detachedEntity, $method)) {
                $detachedEntity->$method($value);
            }
        }

        return $detachedEntity;
    }

    private function formatInputToMethodName($input)
    {
        $methodName = implode('', array_map('ucfirst', explode('_', strtolower($input))));

        return $methodName;
    }
}
