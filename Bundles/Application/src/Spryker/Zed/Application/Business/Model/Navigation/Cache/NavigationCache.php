<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\Cache;

use Spryker\Zed\Application\Business\Exception\NavigationCacheException;

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
     * @throws \Spryker\Zed\Application\Business\Exception\NavigationCacheException
     *
     * @return array
     */
    public function getNavigation()
    {
        if (!file_exists($this->cacheFile)) {
            throw new NavigationCacheException('Navigation cache is enabled, but there is no cache file.');
        }
        return unserialize(file_get_contents($this->cacheFile));
    }

}
