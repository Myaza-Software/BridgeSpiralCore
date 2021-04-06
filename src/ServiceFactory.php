<?php

/**
 * Bridge Spiral-Core
 *
 * @author Vlad Shashkov <root@myaza.info>
 * @copyright Copyright (c) 2021, The Myaza Software
 */

declare(strict_types=1);

namespace Spiral\Bridge\Core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Spiral\Core\FactoryInterface;

final class ServiceFactory implements FactoryInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var iterable<ServiceIdResolverInterface>
     */
    private $serviceIdResolvers;

    /**
     * ServiceFactory constructor.
     *
     * @param ServiceIdResolverInterface[] $serviceIdResolvers
     */
    public function __construct(ContainerInterface $container, iterable $serviceIdResolvers)
    {
        $this->container          = $container;
        $this->serviceIdResolvers = $serviceIdResolvers;
    }

    /**
     * @param array<string,mixed> $parameters
     *
     * @throws ContainerExceptionInterface
     *
     * @return mixed|object|null
     */
    public function make(string $alias, array $parameters = [])
    {
        foreach ($this->serviceIdResolvers as $resolver) {
            if ($resolver->support($alias, $parameters)) {
                return $this->container->get($resolver->resolve($alias, $parameters));
            }
        }

        return $this->container->get($alias);
    }
}
