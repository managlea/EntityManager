<?php

declare(strict_types=1);

namespace Managlea\Component;


interface EntityManagerFactoryInterface
{
    /**
     * @param string $entityManagerName
     * @return EntityManagerInterface
     */
    public function create(string $entityManagerName) : EntityManagerInterface;
}
