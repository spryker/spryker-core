<?php

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Collector;

use Zend\Config\Factory;

class NavigationCollector implements NavigationCollectorInterface
{
    /**
     * @param array $navigationFiles
     * @return array
     * @throws \ErrorException
     */
    public function mergeNavigationFiles(array $navigationFiles)
    {
        $navigationDefinition = Factory::fromFile(APPLICATION_ROOT_DIR . '/config/Zed/navigation.xml', true);
        foreach ($navigationFiles as $moduleNavigationFile) {
            if (!file_exists($moduleNavigationFile)) {
                throw new \ErrorException('Navigation-File does not exist: ' . $moduleNavigationFile);
            }
            $configFromFile = Factory::fromFile($moduleNavigationFile, true);
            $navigationDefinition->merge($configFromFile);
        }

        return $navigationDefinition->toArray();
    }
}
