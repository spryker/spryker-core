<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Collector;

use ErrorException;
use Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinderInterface;
use Zend\Config\Config;
use Zend\Config\Factory;

class ZedNavigationCollector implements ZedNavigationCollectorInterface
{

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinderInterface
     */
    private $navigationSchemaFinder;

    /**
     * @var string
     */
    private $rootNavigationFile;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder\ZedNavigationSchemaFinderInterface $navigationSchemaFinder
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
                throw new ErrorException('Navigation-File does not exist: ' . $moduleNavigationFile);
            }
            $configFromFile = Factory::fromFile($moduleNavigationFile->getPathname(), true);
            $navigationDefinition->merge($configFromFile);
        }

        return $navigationDefinition->toArray();
    }

}
