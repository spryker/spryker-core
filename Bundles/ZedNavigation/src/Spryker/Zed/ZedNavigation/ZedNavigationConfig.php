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

    protected const NAVIGATION_TYPE_MAIN = 'main';
    protected const NAVIGATION_TYPE_SECONDARY = 'secondary';

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
     * @deprecated Use {@link getNavigationSchemaFileNamePatterns()} instead.
     *
     * @return string
     */
    public function getNavigationSchemaFileNamePattern()
    {
        return 'navigation.xml';
    }

    /**
     * Specification:
     *  - Returns navigation schema file name patters indexed by navigation types.
     *
     * @api
     *
     * @return string[]
     */
    public function getNavigationSchemaFileNamePatterns(): array
    {
        return [
            static::NAVIGATION_TYPE_MAIN => $this->getNavigationSchemaFileNamePattern(),
            static::NAVIGATION_TYPE_SECONDARY => 'navigation-secondary.xml',
        ];
    }

    /**
     * @api
     *
     * @deprecated Use {@link getRootNavigationSchemaPaths()} instead.
     *
     * @return string
     */
    public function getRootNavigationSchema()
    {
        return $this->getRootNavigationSchemasDirName() . $this->getNavigationSchemaFileNamePattern();
    }

    /**
     * Specification:
     *  - Returns absolute paths to navigation schemas indexed by navigation types.
     *
     * @api
     *
     * @return string[]
     */
    public function getRootNavigationSchemaPaths(): array
    {
        return [
            static::NAVIGATION_TYPE_MAIN => $this->getRootNavigationSchema(),
            static::NAVIGATION_TYPE_SECONDARY => $this->getRootNavigationSchemasDirName() . $this->getNavigationSchemaFileNamePatterns()[static::NAVIGATION_TYPE_SECONDARY],
        ];
    }

    /**
     * @api
     *
     * @deprecated Use {@link getCacheFilePaths()} instead.
     *
     * @return string
     */
    public function getCacheFile()
    {
        return $this->getCacheDirName() . 'navigation.cache';
    }

    /**
     * Specification:
     *  - Returns absolute paths to cache files indexed by navigation types.
     *
     * @api
     *
     * @return string[]
     */
    public function getCacheFilePaths(): array
    {
        return [
            static::NAVIGATION_TYPE_MAIN => $this->getCacheFile(),
            static::NAVIGATION_TYPE_SECONDARY => $this->getCacheDirName() . 'navigation-secondary.cache',
        ];
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

    /**
     * Specification:
     *  - Defines the default navigation type.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultNavigationType(): string
    {
        return static::NAVIGATION_TYPE_MAIN;
    }

    /**
     * Specification:
     *  - Defines the cache directory name.
     *
     * @api
     *
     * @return string
     */
    public function getCacheDirName(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Generated/' . ucfirst(strtolower(APPLICATION)) . '/Navigation/codeBucket/';
    }

    /**
     * Specification:
     *  - Defines the navigation schemas directory name.
     *
     * @api
     *
     * @return string
     */
    public function getRootNavigationSchemasDirName(): string
    {
        return APPLICATION_ROOT_DIR . '/config/Zed/';
    }
}
