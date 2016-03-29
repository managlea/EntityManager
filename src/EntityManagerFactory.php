<?php

declare(strict_types = 1);

namespace Managlea\Component;


use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityManagerFactory implements EntityManagerFactoryInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $entityManagerName service name for entityManager
     * @return EntityManagerInterface
     * @throws \Exception
     */
    public function create(string $entityManagerName) : EntityManagerInterface
    {
        $entityManager = $this->container->get($entityManagerName);
        
        return $entityManager;
    }
}
