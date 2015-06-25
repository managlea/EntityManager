<?php

namespace Managlea\Component;

/**
 * Interface EntityManagerInterface
 * @package Managlea\Component
 */
interface EntityManagerInterface
{
    /**
     * @param string $objectName
     * @param int $id The identifier.
     * @return mixed
     */
    public function get($objectName, $id);

    /**
     * @param string $objectName
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function getCollection($objectName, array $filters = array(), $limit = 20, $offset = 0);

    /**
     * @param string $objectName
     * @param array $data
     * @return mixed
     */
    public function create($objectName, array $data);

    /**
     * @param string $objectName
     * @param int $id The identifier.
     * @param array $data
     * @return mixed
     */
    public function update($objectName, $id, array $data);

    /**
     * @param string $objectName
     * @param int $id The identifier.
     * @return bool
     */
    public function delete($objectName, $id);
}
