<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Cache\Cache;

use Spryker\Shared\Twig\Cache\CacheInterface;
use Spryker\Shared\Twig\Cache\CacheLoaderInterface;
use Spryker\Shared\Twig\Cache\CacheWriterInterface;

class FilesystemCache implements CacheInterface
{
    /**
     * @var string
     */
    protected $cacheFilePath;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var bool
     */
    protected $refresh = false;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @var \Spryker\Shared\Twig\Cache\CacheWriterInterface
     */
    protected $cacheWriter;

    /**
     * @param \Spryker\Shared\Twig\Cache\CacheLoaderInterface $cacheLoader
     * @param \Spryker\Shared\Twig\Cache\CacheWriterInterface $cacheWriter
     * @param bool $enabled
     */
    public function __construct(CacheLoaderInterface $cacheLoader, CacheWriterInterface $cacheWriter, $enabled)
    {
        $this->cache = $cacheLoader->load();
        $this->cacheWriter = $cacheWriter;
        $this->enabled = $enabled;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        if (!$this->enabled) {
            return false;
        }

        return isset($this->cache[$key]);
    }

    /**
     * @param string $key
     *
     * @return bool|string
     */
    public function get($key)
    {
        if (!$this->enabled || !$this->has($key)) {
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
        $this->refresh = true;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key)
    {
        if (!$this->enabled || !$this->has($key)) {
            return false;
        }

        return ($this->cache[$key] !== false);
    }

    public function __destruct()
    {
        if (count($this->cache) === 0 || !$this->refresh) {
            return;
        }

        $this->cacheWriter->write($this->cache);
    }
}
