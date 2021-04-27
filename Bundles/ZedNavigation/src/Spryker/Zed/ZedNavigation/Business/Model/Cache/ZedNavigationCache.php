<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Cache;

use Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheEmptyException;
use Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheFileDoesNotExistException;
use Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingInterface;

class ZedNavigationCache implements ZedNavigationCacheInterface
{
    /**
     * @deprecated Use settings in ZedNavigationCollectorCacheDecorator. Or any class, that use this one.
     *
     * @var bool
     */
    protected $isEnabled;

    /**
     * @var \Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param bool $isEnabled
     * @param \Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingInterface $utilEncodingService
     */
    public function __construct($isEnabled, ZedNavigationToUtilEncodingInterface $utilEncodingService)
    {
        $this->isEnabled = $isEnabled;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @deprecated Use settings in ZedNavigationCollectorCacheDecorator. Or any class, that use this one.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param array $navigation
     * @param string $cacheFilePath
     *
     * @return void
     */
    public function setNavigation(array $navigation, string $cacheFilePath): void
    {
        if (!is_dir(dirname($cacheFilePath))) {
            mkdir(dirname($cacheFilePath), 0777, true);
        }

        file_put_contents($cacheFilePath, $this->utilEncodingService->encodeJson($navigation));
    }

    /**
     * @param string $cacheFilePath
     *
     * @throws \Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheFileDoesNotExistException
     * @throws \Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheEmptyException
     *
     * @return array
     */
    public function getNavigation(string $cacheFilePath): array
    {
        if (!file_exists($cacheFilePath)) {
            throw new ZedNavigationCacheFileDoesNotExistException('Navigation cache is enabled, but there is no cache file.');
        }

        $content = file_get_contents($cacheFilePath);

        if (empty($content)) {
            throw new ZedNavigationCacheEmptyException('Navigation cache is enabled, but cache is empty.');
        }

        return $this->utilEncodingService->decodeJson($content, true);
    }

    /**
     * @param string $cacheFilePath
     *
     * @return bool
     */
    public function hasContent(string $cacheFilePath): bool
    {
        clearstatcache(false, $cacheFilePath);
        if (!file_exists($cacheFilePath)) {
            return false;
        }
        $cacheFileSized = filesize($cacheFilePath);

        return !empty($cacheFileSized);
    }

    /**
     * @param string $cacheFilePath
     *
     * @return void
     */
    public function removeCache(string $cacheFilePath): void
    {
        if (file_exists($cacheFilePath)) {
            unlink($cacheFilePath);
        }
    }
}
