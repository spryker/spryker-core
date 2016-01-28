<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\Application\ApplicationConstants;

class ApplicationConfig extends AbstractBundleConfig
{

    const MAX_LEVEL_COUNT = 3;

    /**
     * @return int
     */
    public function getMaxMenuLevelCount()
    {
        return self::MAX_LEVEL_COUNT;
    }

    /**
     * @return array
     */
    public function getNavigationSchemaPathPattern()
    {
        return [
            $this->get(ApplicationConstants::SPRYKER_BUNDLES_ROOT) . '/*/src/*/Zed/*/Communication',
        ];
    }

    /**
     * @return string
     */
    public function getNavigationSchemaFileNamePattern()
    {
        return 'navigation.xml';
    }

    /**
     * @return string
     */
    public function getRootNavigationSchema()
    {
        return APPLICATION_ROOT_DIR . '/config/Zed/' . $this->getNavigationSchemaFileNamePattern();
    }

    /**
     * @return string
     */
    public function getCacheFile()
    {
        return APPLICATION_ROOT_DIR . '/src/Generated/navigation.cache';
    }

    /**
     * @return string
     */
    public function isNavigationCacheEnabled()
    {
        return $this->get(ApplicationConstants::NAVIGATION_CACHE_ENABLED);
    }

    /**
     * @return string
     */
    public function getBundlesDirectory()
    {
        return $this->get(ApplicationConstants::SPRYKER_BUNDLES_ROOT);
    }

}
