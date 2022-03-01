<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Router;

use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\Router as SymfonyRouter;

class Router extends SymfonyRouter implements RouterInterface, WarmableInterface
{
    /**
     * @var \Symfony\Component\Config\ConfigCacheFactoryInterface|null
     */
    protected $configCacheFactory;

    /**
     * Provides the ConfigCache factory implementation, falling back to a
     * default implementation if necessary.
     *
     * @return \Symfony\Component\Config\ConfigCacheFactoryInterface
     */
    protected function getConfigCacheFactory()
    {
        if ($this->configCacheFactory === null) {
            $this->configCacheFactory = new ConfigCacheFactory($this->options['debug']);
        }

        return $this->configCacheFactory;
    }

    /**
     * @param string $cacheDir
     *
     * @return array<string>
     */
    public function warmUp($cacheDir): array
    {
        $this->getGenerator();
        $this->getMatcher();

        return [];
    }
}
