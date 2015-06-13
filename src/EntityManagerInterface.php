<?php

namespace Managlea\Component;


interface EntityManagerInterface
{
    /**
     * @param string $entity
     * @param int $resourceId
     * @return mixed
     */
    public function findEntity($entity, $resourceId);

    /**
     * @param string $entity
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function findEntityCollection($entity, array $filters = array(), $limit = 20, $offset = 0);

    /**
     * @param string $entity
     * @param array $data
     * @return mixed
     */
    public function createEntity($entity, array $data);

    public function updateEntity($resourceId, array $data);

    /**
     * @param string $entity
     * @param int $resourceId
     * @return bool
     */
    public function removeEntity($entity, $resourceId);
}
