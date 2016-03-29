<?php

declare(strict_types=1);

namespace Managlea\Component;


use Symfony\Component\DependencyInjection\ContainerInterface;

interface EntityManagerFactoryInterface
{
    /**
     * EntityManagerFactoryInterface constructor.
     * @param ContainerInterface $containerInterface
     */
    public function __construct(ContainerInterface $containerInterface);

    /**
     * @param string $entityManagerName
     * @return EntityManagerInterface
     */
    public function create(string $entityManagerName) : EntityManagerInterface;
}
