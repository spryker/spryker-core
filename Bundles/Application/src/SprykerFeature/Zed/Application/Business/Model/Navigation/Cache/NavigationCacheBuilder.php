<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Cache;

use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface;

class NavigationCacheBuilder
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

    public function writeNavigationCache()
    {
        $navigation = $this->navigationCollector->getNavigation();
        $this->navigationCache->setNavigation($navigation);
    }

}
