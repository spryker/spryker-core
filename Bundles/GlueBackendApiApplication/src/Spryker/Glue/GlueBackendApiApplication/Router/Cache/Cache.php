<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Router\Cache;

use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig;
use Spryker\Glue\GlueBackendApiApplication\Router\ChainRouterInterface;
use Symfony\Component\Filesystem\Filesystem;

class Cache implements CacheInterface
{
    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\Router\ChainRouterInterface
     */
    protected ChainRouterInterface $router;

    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig
     */
    protected GlueBackendApiApplicationConfig $config;

    /**
     * @param \Spryker\Glue\GlueBackendApiApplication\Router\ChainRouterInterface $router
     * @param \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig $config
     */
    public function __construct(ChainRouterInterface $router, GlueBackendApiApplicationConfig $config)
    {
        $this->router = $router;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function warmUp(): void
    {
        $this->clear();
        $this->router->warmUp('');
    }

    /**
     * @return void
     */
    protected function clear(): void
    {
        $filesystem = new Filesystem();
        $routerConfiguration = $this->config->getRouterConfiguration();

        if (isset($routerConfiguration['cache_dir']) && is_dir($routerConfiguration['cache_dir'])) {
            $filesystem->remove($routerConfiguration['cache_dir']);
        }
    }
}
