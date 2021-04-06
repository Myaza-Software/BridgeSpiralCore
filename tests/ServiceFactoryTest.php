<?php

/**
 * Bridge Spiral-Core
 *
 * @author Vlad Shashkov <root@myaza.info>
 * @copyright Copyright (c) 2021, The Myaza Software
 */

declare(strict_types=1);

namespace Spiral\Bridge\Core\Test;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Spiral\Bridge\Core\ServiceFactory;
use Spiral\Bridge\Core\ServiceIdResolverInterface;

final class ServiceFactoryTest extends TestCase
{
    public function testServiceIdResolver(): void
    {
        $serviceIdMask = 'service.%s';
        $serviceId     = sprintf($serviceIdMask, 'user');
        $dependency    = new \stdClass();

        $container      = $this->getContainer([$serviceId => $dependency]);
        $serviceFactory = new ServiceFactory($container, [$this->getServiceIdResolver($serviceIdMask)]);

        $service = $serviceFactory->make($serviceId, ['user' => 'user']);

        $this->assertInstanceOf(\stdClass::class, $service);
    }

    private function getServiceIdResolver(string $serviceIdMask): object
    {
        return new class($serviceIdMask) implements ServiceIdResolverInterface {
            /**
             * @var string
             */
            private $serviceIdMask;

            public function __construct(string $serviceIdMask)
            {
                $this->serviceIdMask = $serviceIdMask;
            }

            public function support(string $class, array $parameters): bool
            {
                return \stdClass::class === $class && $parameters['user'];
            }

            public function resolve(string $class, array $parameters): string
            {
                return sprintf($this->serviceIdMask, $parameters['user']);
            }
        };
    }

    /**
     * @param array<string,mixed> $dependencies
     */
    private function getContainer(array $dependencies): ContainerInterface
    {
        return new class($dependencies) implements ContainerInterface {
            /**
             * @var array<string,mixed>
             */
            private $dependencies;

            /**
             * @param array<string,mixed> $dependencies
             */
            public function __construct(array $dependencies)
            {
                $this->dependencies = $dependencies;
            }

            public function get(string $id)
            {
                $dependency = $this->dependencies[$id] ?? null;

                if (null === $dependency) {
                    throw new \RuntimeException('Not found service:' . $id);
                }

                return $dependency;
            }

            public function has(string $id): bool
            {
                return null === ($this->dependencies[$id] ?? null);
            }
        };
    }
}
