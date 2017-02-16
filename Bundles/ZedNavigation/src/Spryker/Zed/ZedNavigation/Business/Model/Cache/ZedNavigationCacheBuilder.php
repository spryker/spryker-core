<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Cache;

use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;

class ZedNavigationCacheBuilder
{

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface
     */
    private $navigationCollector;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface
     */
    private $navigationCache;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface $navigationCache
     */
    public function __construct(NavigationCollectorInterface $navigationCollector, ZedNavigationCacheInterface $navigationCache)
    {
        $this->navigationCollector = $navigationCollector;
        $this->navigationCache = $navigationCache;
    }

    /**
     * @return void
     */
    public function writeNavigationCache()
    {
        $navigation = $this->navigationCollector->getNavigation();
        $this->navigationCache->setNavigation($navigation);
    }

}
