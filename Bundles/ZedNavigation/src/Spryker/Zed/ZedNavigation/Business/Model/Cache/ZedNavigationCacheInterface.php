<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Cache;

interface ZedNavigationCacheInterface
{
    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param array $navigation
     * @param string $cacheFilePath
     *
     * @return void
     */
    public function setNavigation(array $navigation, string $cacheFilePath): void;

    /**
     * @param string $cacheFilePath
     *
     * @return array
     */
    public function getNavigation(string $cacheFilePath): array;

    /**
     * @param string $cacheFilePath
     *
     * @return bool
     */
    public function hasContent(string $cacheFilePath): bool;

    /**
     * @param string $cacheFilePath
     *
     * @return void
     */
    public function removeCache(string $cacheFilePath): void;
}
