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
use Spiral\Bridge\Core\ServiceFactory;
use Spiral\Bridge\Core\ServiceIdResolverInterface;
use Spiral\Bridge\Core\Test\Stub\Container;

final class ServiceFactoryTest extends TestCase
{
    public function testServiceIdResolver(): void
    {
        $serviceIdMask = 'service.%s';
        $serviceId     = sprintf($serviceIdMask, 'user');
        $dependency    = new \stdClass();

        $container      = new Container([$serviceId => $dependency]);
        $serviceFactory = new ServiceFactory($container, [$this->getServiceIdResolver($serviceIdMask)]);
        $service        = $serviceFactory->make(\stdClass::class, ['user' => 'user']);

        $this->assertInstanceOf(\stdClass::class, $service);
        $this->assertEquals(spl_object_id($dependency), spl_object_id($service));
    }

    public function testNoUseServiceIdResolver(): void
    {
        $service        = new \stdClass();
        $container      = new Container([\stdClass::class => $service]);
        $serviceFactory = new ServiceFactory($container, []);
        $result         = $serviceFactory->make(\stdClass::class);

        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertEquals(spl_object_id($result), spl_object_id($service));
    }

    private function getServiceIdResolver(string $serviceIdMask): ServiceIdResolverInterface
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
                return \stdClass::class === $class && array_key_exists('user', $parameters);
            }

            public function resolve(string $class, array $parameters): string
            {
                return sprintf($this->serviceIdMask, $parameters['user']);
            }
        };
    }
}
