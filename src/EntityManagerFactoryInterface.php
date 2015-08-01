<?php

namespace Managlea\Component;


interface EntityManagerFactoryInterface
{
    /**
     * @param string $resourceName
     * @return EntityManagerInterface
     */
    public static function createForResource($resourceName);
}
