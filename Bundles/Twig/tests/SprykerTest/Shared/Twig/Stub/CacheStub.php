<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\Stub;

use Spryker\Shared\Twig\Cache\CacheInterface;

class CacheStub implements CacheInterface
{
    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->cache[$key]);
    }

    /**
     * @param string $key
     *
     * @return bool|string
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            return false;
        }

        return $this->cache[$key];
    }

    /**
     * @param string $key
     * @param string|bool $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->cache[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key)
    {
        if (!$this->has($key)) {
            return false;
        }

        return ($this->cache[$key] !== false);
    }
}
