<?php

namespace Managlea\Component;


interface EntityManagerFactoryInterface
{
    /**
     * @param string $entityManagerName
     * @return EntityManagerInterface
     */
    public static function create($entityManagerName);
}
