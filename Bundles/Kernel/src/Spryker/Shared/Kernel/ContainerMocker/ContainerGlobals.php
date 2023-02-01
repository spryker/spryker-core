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
     * @param string $factoryClassName
     *
     * @return mixed|array
     */
    public function getContainerGlobals($factoryClassName)
    {
        if (isset(static::$onlyFor[$factoryClassName])) {
            return static::$onlyFor[$factoryClassName];
        }

        return static::$containerGlobals;
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
            $this->setOnlyFor($key, $value, $onlyFor);

            return;
        }

        static::$containerGlobals[$key] = $value;
    }

    /**
     * @param string $key
     * @param object|array<string|object>|string $value
     * @param string|null $onlyFor
     *
     * @return void
     */
    protected function setOnlyFor(string $key, $value, ?string $onlyFor = null): void
    {
        if (!isset(static::$onlyFor[$onlyFor])) {
            static::$onlyFor[$onlyFor] = [];
        }

        static::$onlyFor[$onlyFor][$key] = $value;
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
