<?php

namespace Managlea\Component\EntityManager;


use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Configuration;
use Doctrine\Common\EventManager;
use Managlea\Component\EntityManagerInterface;

class DoctrineEntityManager extends EntityManager implements EntityManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        switch (true) {
            case (is_array($conn)):
                $conn = \Doctrine\DBAL\DriverManager::getConnection(
                    $conn, $config, ($eventManager ?: new EventManager())
                );
                break;

            case ($conn instanceof Connection):
                if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                    throw ORMException::mismatchedEventManager();
                }
                break;

            default:
                throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new DoctrineEntityManager($conn, $config, $conn->getEventManager());
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
        if (!($repository instanceof EntityRepository)) {
            throw new \Exception('Repository not found');
        }
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
        if (!($repository instanceof EntityRepository)) {
            throw new \Exception('Repository not found');
        }
        $collection = $repository->findBy($filters, $order, $limit, $offset);

        return $collection;
    }

    public function createEntity(array $data)
    {
        throw new \Exception('Method not implemented');
    }

    public function updateEntity($resourceId, array $data)
    {
        throw new \Exception('Method not implemented');
    }

    public function removeEntity($resourceId)
    {
        throw new \Exception('Method not implemented');
    }
}