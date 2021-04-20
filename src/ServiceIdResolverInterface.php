<?php

/**
 * Bridge Spiral-Core
 *
 * @author Vlad Shashkov <root@myaza.info>
 * @copyright Copyright (c) 2021, The Myaza Software
 */

declare(strict_types=1);

namespace Spiral\Bridge\Core;

interface ServiceIdResolverInterface
{
    /**
     * @param class-string|string $class
     * @param array<string,mixed> $parameters
     */
    public function support(string $class, array $parameters): bool;

    /**
     * @param class-string|string $class
     * @param array<string,mixed> $parameters
     */
    public function resolve(string $class, array $parameters): string;
}
