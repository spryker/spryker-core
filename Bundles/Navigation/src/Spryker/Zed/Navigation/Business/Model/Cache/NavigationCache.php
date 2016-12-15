<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Model\Cache;

use Spryker\Zed\Navigation\Business\Exception\NavigationCacheEmptyException;
use Spryker\Zed\Navigation\Business\Exception\NavigationCacheFileDoesNotExistException;
use Spryker\Zed\Navigation\Dependency\Util\NavigationToUtilEncodingInterface;

class NavigationCache implements NavigationCacheInterface
{

    /**
     * @var string
     */
    protected $cacheFile;

    /**
     * @var bool
     */
    protected $isEnabled;

    /**
     * @var \Spryker\Zed\Navigation\Dependency\Util\NavigationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param string $cacheFile
     * @param bool $isEnabled
     * @param \Spryker\Zed\Navigation\Dependency\Util\NavigationToUtilEncodingInterface $utilEncodingService
     */
    public function __construct($cacheFile, $isEnabled, NavigationToUtilEncodingInterface $utilEncodingService)
    {
        $this->cacheFile = $cacheFile;
        $this->isEnabled = $isEnabled;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
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
     * @throws \Spryker\Zed\Navigation\Business\Exception\NavigationCacheFileDoesNotExistException
     * @throws \Spryker\Zed\Navigation\Business\Exception\NavigationCacheEmptyException
     *
     * @return array
     */
    public function getNavigation()
    {
        if (!file_exists($this->cacheFile)) {
            throw new NavigationCacheFileDoesNotExistException('Navigation cache is enabled, but there is no cache file.');
        }

        $content = file_get_contents($this->cacheFile);

        if (empty($content)) {
            throw new NavigationCacheEmptyException('Navigation cache is enabled, but cache is empty.');
        }

        return $this->utilEncodingService->decodeJson($content, true);
    }

}
