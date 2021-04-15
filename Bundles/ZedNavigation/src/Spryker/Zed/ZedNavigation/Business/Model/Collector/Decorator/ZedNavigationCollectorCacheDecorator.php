<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Collector\Decorator;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

class ZedNavigationCollectorCacheDecorator implements ZedNavigationCollectorInterface
{
    use LoggerTrait;

    protected const MESSAGE_CACHE_LOST = 'Zed navigation cache file lost.';

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface
     */
    protected $navigationCollector;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface
     */
    protected $navigationCache;

    /**
     * @var \Spryker\Zed\ZedNavigation\ZedNavigationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface $navigationCache
     * @param \Spryker\Zed\ZedNavigation\ZedNavigationConfig $config
     */
    public function __construct(
        ZedNavigationCollectorInterface $navigationCollector,
        ZedNavigationCacheInterface $navigationCache,
        ZedNavigationConfig $config
    ) {
        $this->navigationCollector = $navigationCollector;
        $this->navigationCache = $navigationCache;
        $this->config = $config;
    }

    /**
     * @param string $navigationType
     *
     * @return array [string => string][] @see MenuFormatter
     */
    public function getNavigation(string $navigationType): array
    {
        if (!$this->config->isNavigationCacheEnabled()) {
            return $this->navigationCollector->getNavigation($navigationType);
        }

        $cacheFilePath = $this->config->getCacheFilePaths()[$navigationType];

        if (!$this->navigationCache->hasContent($cacheFilePath)) {
            $this->getLogger()->error(static::MESSAGE_CACHE_LOST);

            $this->navigationCache->setNavigation($this->navigationCollector->getNavigation($navigationType), $cacheFilePath);
        }

        return $this->navigationCache->getNavigation($cacheFilePath);
    }
}
