<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\Collector\Decorator;

use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface;

class NavigationCollectorCacheDecorator implements NavigationCollectorInterface
{

    /**
     * @var \Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface
     */
    private $navigationCollector;

    /**
     * @var \Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface
     */
    private $navigationCache;

    /**
     * @param \Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface $navigationCollector
     * @param \Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface $navigationCache
     */
    public function __construct(NavigationCollectorInterface $navigationCollector, NavigationCacheInterface $navigationCache)
    {
        $this->navigationCollector = $navigationCollector;
        $this->navigationCache = $navigationCache;
    }

    /**
     * @return array
     */
    public function getNavigation()
    {
        if ($this->navigationCache->isEnabled()) {
            return $this->navigationCache->getNavigation();
        }

        return $this->navigationCollector->getNavigation();
    }

}
