<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Cache;

use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

class ZedNavigationCacheBuilder
{
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
    protected $zedNavigationConfig;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface $navigationCache
     * @param \Spryker\Zed\ZedNavigation\ZedNavigationConfig $zedNavigationConfig
     */
    public function __construct(
        ZedNavigationCollectorInterface $navigationCollector,
        ZedNavigationCacheInterface $navigationCache,
        ZedNavigationConfig $zedNavigationConfig
    ) {
        $this->navigationCollector = $navigationCollector;
        $this->navigationCache = $navigationCache;
        $this->zedNavigationConfig = $zedNavigationConfig;
    }

    /**
     * @return void
     */
    public function writeNavigationCache()
    {
        foreach ($this->zedNavigationConfig->getCacheFilePaths() as $navigationType => $cacheFilePath) {
            $navigation = $this->navigationCollector->getNavigation($navigationType);
            $this->navigationCache->setNavigation($navigation, $cacheFilePath);
        }
    }
}
