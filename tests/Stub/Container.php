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

final class Container implements ContainerInterface
{
    /**
     * @var array<string,mixed>
     */
    private $dependencies;

    /**
     * Container constructor.
     *
     * @param array<string,mixed> $dependencies
     */
    public function __construct(array $dependencies = [])
    {
        $this->dependencies                            = $dependencies;
        $this->dependencies[ContainerInterface::class] = $this;
    }

    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new \RuntimeException('Not found service:' . $id);
        }

        return $this->dependencies[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->dependencies);
    }
}
