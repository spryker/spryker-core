<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Dependency\Resolver;

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

    /**s
     * @return bool
     */
    public function isResolvable(): bool
    {
        return class_exists($this->commandClassName);
    }

    /**
     * @return \Spryker\Install\Stage\Section\Command\Command
     */
    public function resolve(): Command
    {
        if (is_callable($this->callback)) {
            return ($this->callback)();
        }

        $className = $this->commandClassName;

        return new $className();
    }
}
