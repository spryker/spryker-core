<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication\Resolver;

use Spryker\Zed\Kernel\Container;
use Symfony\Component\Console\Command\Command;

class OptionalCommandResolver implements OptionalCommandResolverInterface
{
    /**
     * @var string
     */
    protected $commandClassName;

    /**
     * @var callable|null
     */
    protected $callback;

    /**
     * @param string $commandClassName
     * @param callable|null $callback
     */
    public function __construct(string $commandClassName, ?callable $callback = null)
    {
        $this->commandClassName = $commandClassName;
        $this->callback = $callback;
    }

    /**
     * @return bool
     */
    public function isResolvable(): bool
    {
        return class_exists($this->commandClassName);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function resolve(Container $container): Command
    {
        if (is_callable($this->callback)) {
            return ($this->callback)($container);
        }

        $className = $this->commandClassName;

        return new $className();
    }
}
