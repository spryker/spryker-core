<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\Cache;

use Spryker\Zed\Application\Business\Exception\NavigationCacheFileDoesNotExistException;
use Spryker\Zed\Application\Business\Exception\NavigationCacheFileEmptyException;

class NavigationCache implements NavigationCacheInterface
{

    /**
     * @var string
     */
    private $cacheFile;

    /**
     * @var bool
     */
    private $isEnabled;

    /**
     * @param string $cacheFile
     * @param bool $isEnabled
     */
    public function __construct($cacheFile, $isEnabled)
    {
        $this->cacheFile = $cacheFile;
        $this->isEnabled = $isEnabled;
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
        file_put_contents($this->cacheFile, serialize($navigation));
    }

    /**
     * @throws \Spryker\Zed\Application\Business\Exception\NavigationCacheFileDoesNotExistException
     * @throws \Spryker\Zed\Application\Business\Exception\NavigationCacheFileEmptyException
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
            throw new NavigationCacheFileEmptyException('Navigation cache is enabled, but cache is empty.');
        }

        $cachedNavigation = unserialize($content);

        return $cachedNavigation;
    }

}
