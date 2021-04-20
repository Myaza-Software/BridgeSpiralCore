<?php
/**
 * Bridge Spiral-Core
 *
 * @author Vlad Shashkov <root@myaza.info>
 * @copyright Copyright (c) 2021, The Myaza Software
 */
declare(strict_types=1);

namespace Spiral\Bridge\Core\Test\Stub;

use Psr\Container\ContainerInterface;

final class Service
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ServiceTwo
     */
    private $service;

    public function __construct(ContainerInterface $container, ServiceTwo $service)
    {
        $this->service   = $service;
        $this->container = $container;
    }
}
