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
use ReflectionFunctionAbstract as ContextFunction;
use Spiral\Core\Exception\Container\ContainerException;
use Spiral\Core\ResolverInterface;

final class ArgumentResolver implements ResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ArgumentResolver constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array<string,mixed> $parameters
     *
     * @throws ContainerExceptionInterface
     *
     * @return array<string,mixed>
     */
    public function resolveArguments(ContextFunction $reflection, array $parameters = []): array
    {
        $arguments = [];

        foreach ($reflection->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (null === $type) {
                throw new ContainerException(sprintf('Please set type param: %s', $parameter->getName()));
            }

            if (!$type instanceof \ReflectionNamedType) {
                throw new ContainerException(sprintf('Not supported union type, param:%s', $parameter->getName()));
            }

            $arguments[$parameter->getName()] = $this->container->get($type->getName());
        }

        return $arguments;
    }
}
