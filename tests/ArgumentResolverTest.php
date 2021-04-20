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
use Spiral\Bridge\Core\ArgumentResolver;
use Spiral\Bridge\Core\Test\Stub\Container;
use Spiral\Bridge\Core\Test\Stub\Service;
use Spiral\Bridge\Core\Test\Stub\ServiceTwo;
use Spiral\Core\Exception\Container\ContainerException;

final class ArgumentResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $container        = new Container([ServiceTwo::class => new ServiceTwo()]);
        $reflectionMethod = new \ReflectionMethod(Service::class, '__construct');
        $argumentResolver = new ArgumentResolver($container);

        $args = $argumentResolver->resolveArguments($reflectionMethod, []);

        $this->assertArrayHasKey('container', $args);
        $this->assertArrayHasKey('service', $args);
        $this->assertInstanceOf(ContainerInterface::class, $args['container']);
        $this->assertInstanceOf(ServiceTwo::class, $args['service']);
    }

    public function testNullService(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Please set type param: nullService');

        $nullService      = $this->getNullService();
        $container        = new Container();
        $argumentResolver = new ArgumentResolver($container);
        $reflectionMethod = new \ReflectionMethod($nullService, '__construct');

        $argumentResolver->resolveArguments($reflectionMethod, []);
    }

    public function testUnionType(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Not supported union type');

        $reflectionParam  = $this->createMock(\ReflectionParameter::class);
        $reflectionMethod = $this->createMock(\ReflectionMethod::class);

        $reflectionParam
            ->method('getType')
            ->willReturn(new class() extends \ReflectionType{})
        ;

        $reflectionMethod
            ->method('getParameters')
            ->willReturn([$reflectionParam])
        ;

        $container        = new Container();
        $argumentResolver = new ArgumentResolver($container);

        $argumentResolver->resolveArguments($reflectionMethod, []);
    }

    private function getNullService(): object
    {
        return new class(1) {
            /**
             * @var int
             */
            private $nullService;

            /**
             * @param int $nullService
             */
            public function __construct($nullService)
            {
                $this->nullService = $nullService;
            }
        };
    }
}
