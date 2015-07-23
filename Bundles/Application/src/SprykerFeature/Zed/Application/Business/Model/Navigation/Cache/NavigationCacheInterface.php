<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Cache;

interface NavigationCacheInterface
{

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param array $navigation
     */
    public function setNavigation(array $navigation);

    /**
     * @return array
     */
    public function getNavigation();

}
