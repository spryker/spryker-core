<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

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
            $this->getBundlesDirectory() . '/*/src/*/Zed/*/Communication',
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
        return $this->get(ApplicationConstants::APPLICATION_SPRYKER_ROOT);
    }

    /**
     * @return bool
     */
    public function isNavigationEnabled()
    {
        return $this->get(ApplicationConstants::NAVIGATION_ENABLED, true);
    }

}
