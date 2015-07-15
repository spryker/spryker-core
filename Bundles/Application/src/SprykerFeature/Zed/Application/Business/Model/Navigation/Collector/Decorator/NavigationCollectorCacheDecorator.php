<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\Decorator;

use SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface;

class NavigationCollectorCacheDecorator implements NavigationCollectorInterface
{

    /**
     * @var NavigationCollectorInterface
     */
    private $navigationCollector;

    /**
     * @var NavigationCacheInterface
     */
    private $navigationCache;

    /**
     * @param NavigationCollectorInterface $navigationCollector
     * @param NavigationCacheInterface $navigationCache
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
