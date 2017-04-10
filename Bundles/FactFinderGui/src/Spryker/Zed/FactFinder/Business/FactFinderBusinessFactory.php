<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Spryker\Shared\Library\Reader\Csv\CsvReader;
use Spryker\Zed\FactFinder\FactFinderDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class FactFinderBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Shared\Library\Reader\Csv\CsvReader
     */
    public function createFileReader()
    {
        return new CsvReader();
    }

    /**
     * @return \Pyz\Zed\Collector\Business\CollectorFacade
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(FactFinderDependencyProvider::COLLECTOR_FACADE);
    }

    /**
     * @return \Spryker\Zed\FactFinder\FactFinderConfig
     */
    public function getFactFinderConfig()
    {
        return $this->getConfig();
    }

    /**
     * @return string
     */
    public function getFactFinderCategoryCollectorClassName()
    {
        return '\Spryker\Zed\FactFinder\Business\Collector\File\FactFinderCategoryCollector';
    }

    /**
     * @return string
     */
    public function getFactFinderProductCollectorClassName()
    {
        return '\Spryker\Zed\FactFinder\Business\Collector\File\FactFinderProductCollector';
    }

}
