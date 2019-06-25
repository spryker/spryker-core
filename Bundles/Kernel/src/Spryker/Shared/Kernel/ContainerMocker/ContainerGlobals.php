<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ContainerMocker;

class ContainerGlobals
{
    /**
     * @var array
     */
    protected static $containerGlobals = [];

    /**
     * @var array
     */
    protected static $onlyFor = [];

    /**
     * @param string $dependencyProviderClassName
     *
     * @return array|mixed
     */
    public function getContainerGlobals($dependencyProviderClassName)
    {
        if (isset(static::$onlyFor[$dependencyProviderClassName])) {
            return static::$onlyFor[$dependencyProviderClassName];
        }

        return self::$containerGlobals;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string|null $onlyFor
     *
     * @return void
     */
    public function set($key, $value, $onlyFor = null)
    {
        if ($onlyFor) {
            static::$onlyFor[$onlyFor] = [
                $key => $value,
            ];

            return;
        }

        static::$containerGlobals[$key] = $value;
    }

    /**
     * @return void
     */
    public function reset()
    {
        static::$containerGlobals = [];
        static::$onlyFor = [];
    }
}
