<?php

namespace Managlea\Component;


interface EntityManagerInterface
{
    public function findEntity($entity, $resourceId);
    
    public function findEntityCollection($entity, array $filters = array());
    
    public function createEntity(array $data);

    public function updateEntity($resourceId, array $data);
    
    public function removeEntity($resourceId);
}
