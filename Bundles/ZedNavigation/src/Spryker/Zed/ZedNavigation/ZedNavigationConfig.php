<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation;

use Spryker\Shared\ZedNavigation\ZedNavigationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ZedNavigationConfig extends AbstractBundleConfig
{
    public const MAX_LEVEL_COUNT = 5;

    /**
     * Specification:
     *  - Strategy by which root navigation elements are being merged with core navigation elements.
     */
    public const FULL_MERGE_STRATEGY = 'fullMergeStrategy';

    /**
     * Specification:
     *  - Strategy by which root navigation elements are being merged with core navigation elements excluding first and second level.
     */
    public const BREADCRUMB_MERGE_STRATEGY = 'breadcrumbMergeStrategy';

    /**
     * @api
     *
     * @return int
     */
    public function getMaxMenuLevelCount()
    {
        return static::MAX_LEVEL_COUNT;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getNavigationSchemaPathPattern()
    {
        $navigationSchemaPathPatterns = [
            $this->getBundlesDirectory() . '/*/src/*/Zed/*/Communication',
            APPLICATION_SOURCE_DIR . '/*/Zed/*/Communication',
        ];

        return $navigationSchemaPathPatterns;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNavigationSchemaFileNamePattern()
    {
        return 'navigation.xml';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getRootNavigationSchema()
    {
        return APPLICATION_ROOT_DIR . '/config/Zed/' . $this->getNavigationSchemaFileNamePattern();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCacheFile()
    {
        return APPLICATION_ROOT_DIR . '/src/Generated/navigation.cache';
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isNavigationCacheEnabled()
    {
        return $this->get(ZedNavigationConstants::ZED_NAVIGATION_CACHE_ENABLED, true);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isNavigationEnabled()
    {
        return $this->get(ZedNavigationConstants::ZED_NAVIGATION_ENABLED, true);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBundlesDirectory()
    {
        return APPLICATION_VENDOR_DIR . '/*';
    }

    /**
     * Specification:
     *  - Defines by which strategy merging of navigation elements should be.
     *
     * @api
     *
     * @return string
     */
    public function getMergeStrategy(): string
    {
        return static::FULL_MERGE_STRATEGY;
    }
}
