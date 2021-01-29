<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Container;

use Spryker\Service\Container\ContainerInterface;

class GlobalContainer implements GlobalContainerInterface
{
    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected static $container;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    public static function setContainer(ContainerInterface $container): void
    {
        static::$container = $container;
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return static::$container;
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function has(string $id)
    {
        return static::$container->has($id);
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function get(string $id)
    {
        return static::$container->get($id);
    }
}
