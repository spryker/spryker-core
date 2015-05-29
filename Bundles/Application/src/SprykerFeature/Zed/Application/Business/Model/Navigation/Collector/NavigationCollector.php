<?php

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Collector;

use SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface;
use Zend\Config\Factory;

class NavigationCollector implements NavigationCollectorInterface
{

    /**
     * @param NavigationSchemaFinderInterface $navigationSchemaFinder
     *
     * @throws \ErrorException
     * @return array
     */
    public function mergeNavigationFiles(NavigationSchemaFinderInterface $navigationSchemaFinder)
    {
        $navigationDefinition = Factory::fromFile(APPLICATION_ROOT_DIR . '/config/Zed/navigation.xml', true);
        foreach ($navigationSchemaFinder->getSchemaFiles() as $moduleNavigationFile) {
            if (!file_exists($moduleNavigationFile->getPathname())) {
                throw new \ErrorException('Navigation-File does not exist: ' . $moduleNavigationFile);
            }
            $configFromFile = Factory::fromFile($moduleNavigationFile->getPathname(), true);
            $navigationDefinition->merge($configFromFile);
        }

        return $navigationDefinition->toArray();
    }

}
