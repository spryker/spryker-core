<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Cache;

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
        return $this->isEnabled && file_exists($this->cacheFile);
    }

    /**
     * @param array $navigation
     */
    public function setNavigation(array $navigation)
    {
        file_put_contents($this->cacheFile, serialize($navigation));
    }

    /**
     * @return array
     */
    public function getNavigation()
    {
        return unserialize(file_get_contents($this->cacheFile));
    }

}
