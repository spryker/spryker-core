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
     * @var string
     */
    protected $cacheFile;

    /**
     * @deprecated
     *
     * @var bool
     */
    protected $isEnabled;

    /**
     * @var \Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param string $cacheFile
     * @param bool $isEnabled
     * @param \Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingInterface $utilEncodingService
     */
    public function __construct($cacheFile, $isEnabled, ZedNavigationToUtilEncodingInterface $utilEncodingService)
    {
        $this->cacheFile = $cacheFile;
        $this->isEnabled = $isEnabled;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @deprecated
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param array $navigation
     *
     * @return void
     */
    public function setNavigation(array $navigation)
    {
        file_put_contents($this->cacheFile, $this->utilEncodingService->encodeJson($navigation));
    }

    /**
     * @throws \Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheFileDoesNotExistException
     * @throws \Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheEmptyException
     *
     * @return array
     */
    public function getNavigation()
    {
        if (!file_exists($this->cacheFile)) {
            throw new ZedNavigationCacheFileDoesNotExistException('Navigation cache is enabled, but there is no cache file.');
        }

        $content = file_get_contents($this->cacheFile);

        if (empty($content)) {
            throw new ZedNavigationCacheEmptyException('Navigation cache is enabled, but cache is empty.');
        }

        return $this->utilEncodingService->decodeJson($content, true);
    }

    /**
     * @return bool
     */
    public function hasContent(): bool
    {
        clearstatcache(false, $this->cacheFile);
        if (!file_exists($this->cacheFile)) {
            return false;
        }
        $content = file_get_contents($this->cacheFile);

        return !empty($content);
    }
}
