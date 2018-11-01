<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Collector;

use ErrorException;
use Exception;
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
    public function __construct(ZedNavigationSchemaFinderInterface $navigationSchemaFinder, $rootNavigationFile)
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
            /** @var \Zend\Config\Config $navigationDefinition */
            $navigationDefinition = Factory::fromFile($this->rootNavigationFile, true);
            $rootDefinition = clone $navigationDefinition;
        } catch (Exception $e) {
            $navigationDefinition = new Config([]);
            $rootDefinition = new Config([]);
        }

        foreach ($this->navigationSchemaFinder->getSchemaFiles() as $moduleNavigationFile) {
            if (!file_exists($moduleNavigationFile->getPathname())) {
                throw new ErrorException('Navigation-File does not exist: ' . $moduleNavigationFile);
            }
            /** @var \Zend\Config\Config $configFromFile */
            $configFromFile = Factory::fromFile($moduleNavigationFile->getPathname(), true);
            $navigationDefinition->merge($configFromFile);
        }

        $navigationDefinition->merge($rootDefinition);

        return $navigationDefinition->toArray();
    }
}
