<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\Collector;

use Spryker\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface;
use Zend\Config\Config;
use Zend\Config\Factory;

class NavigationCollector implements NavigationCollectorInterface
{

    /**
     * @var \Spryker\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface
     */
    private $navigationSchemaFinder;

    /**
     * @var string
     */
    private $rootNavigationFile;

    /**
     * @param \Spryker\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface $navigationSchemaFinder
     * @param string $rootNavigationFile
     */
    public function __construct(NavigationSchemaFinderInterface $navigationSchemaFinder, $rootNavigationFile)
    {
        $this->navigationSchemaFinder = $navigationSchemaFinder;
        $this->rootNavigationFile = $rootNavigationFile;
    }

    /**
     * @throws \ErrorException
     *
     * @return array
     */
    public function getNavigation()
    {
        try {
            $navigationDefinition = Factory::fromFile($this->rootNavigationFile, true);
        } catch (\Exception $e) {
            $navigationDefinition = new Config([]);
        }

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
