<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Collector\Decorator;

use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;

class ZedNavigationCollectorCacheDecorator implements ZedNavigationCollectorInterface
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
    public function __construct(ZedNavigationCollectorInterface $navigationCollector, ZedNavigationCacheInterface $navigationCache)
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
