<?php

declare(strict_types=1);

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
     * @param array $criteria
     * @return mixed
     */
    public function get(string $objectName, int $id, array $criteria = array());

    /**
     * @param string $objectName
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @param array|null $order
     * @return array
     */
    public function getCollection(string $objectName, array $filters = array(), int $limit = 20, int $offset = 0, array $order = null) : array;

    /**
     * @param string $objectName
     * @param array $data
     * @return mixed
     */
    public function create(string $objectName, array $data);

    /**
     * @param string $objectName
     * @param int $id The identifier.
     * @param array $data
     * @return mixed
     */
    public function update(string $objectName, int $id, array $data);

    /**
     * @param string $objectName
     * @param int $id The identifier.
     * @return bool
     */
    public function delete(string $objectName, int $id);
}
