<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Collector;

use SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface;
use SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface;
use Zend\Config\Factory;

class NavigationCollector implements NavigationCollectorInterface
{

    /**
     * @var NavigationSchemaFinderInterface
     */
    private $navigationSchemaFinder;

    /**
     * @var NavigationCacheInterface
     */
    private $navigationCache;

    /**
     * @var string
     */
    private $rootNavigationFile;

    /**
     * @param NavigationSchemaFinderInterface $navigationSchemaFinder
     * @param NavigationCacheInterface $navigationCache
     * @param string $rootNavigationFile
     */
    public function __construct(NavigationSchemaFinderInterface $navigationSchemaFinder, NavigationCacheInterface $navigationCache, $rootNavigationFile)
    {
        $this->navigationSchemaFinder = $navigationSchemaFinder;
        $this->navigationCache = $navigationCache;
        $this->rootNavigationFile = $rootNavigationFile;
    }

    /**
     * @throws \ErrorException
     *
     * @return array
     */
    public function getNavigation()
    {
        $navigationDefinition = Factory::fromFile($this->rootNavigationFile, true);
        foreach ($this->navigationSchemaFinder->getSchemaFiles() as $moduleNavigationFile) {
            if (!file_exists($moduleNavigationFile->getPathname())) {
                throw new \ErrorException('Navigation-File does not exist: ' . $moduleNavigationFile);
            }
            $configFromFile = Factory::fromFile($moduleNavigationFile->getPathname(), true);
            $navigationDefinition->merge($configFromFile);
        }

        return $navigationDefinition->toArray();
    }

}
