<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Cache;

use Spryker\Shared\Router\Cache\CacheInterface;
use Spryker\Zed\Router\Business\Router\ChainRouter;
use Spryker\Zed\Router\RouterConfig;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractCacheWarmer implements CacheInterface
{
    /**
     * @var \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    protected $router;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @var \Spryker\Zed\Router\RouterConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Router\Business\Router\ChainRouter $router
     * @param \Symfony\Component\Filesystem\Filesystem $fileSystem
     * @param \Spryker\Zed\Router\RouterConfig $config
     */
    public function __construct(ChainRouter $router, Filesystem $fileSystem, RouterConfig $config)
    {
        $this->router = $router;
        $this->fileSystem = $fileSystem;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function warmUp(): void
    {
        $this->removeDir($this->getCacheDir());
        $this->router->warmUp('');
    }

    /**
     * @param string|null $cacheDir
     *
     * @return void
     */
    protected function removeDir(?string $cacheDir): void
    {
        if ($cacheDir === null || !is_dir($cacheDir)) {
            return;
        }

        $this->fileSystem->remove($cacheDir);
    }

    /**
     * @return string|null
     */
    abstract protected function getCacheDir(): ?string;
}
