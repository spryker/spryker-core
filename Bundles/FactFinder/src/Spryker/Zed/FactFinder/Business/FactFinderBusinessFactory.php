<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Spryker\Shared\Library\Reader\Csv\CsvReader;
use Spryker\Zed\Collector\CollectorDependencyProvider;
use Spryker\Zed\FactFinder\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\FactFinder\Business\Api\FactFinderConnector;
use Spryker\Zed\FactFinder\Business\Api\Handler\Request\SearchRequest;
use Spryker\Zed\FactFinder\Business\Collector\File\FactFinderCategoryCollector;
use Spryker\Zed\FactFinder\Business\Collector\File\FactFinderProductCollector;
use Spryker\Zed\FactFinder\FactFinderDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class FactFinderBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\FactFinder\Business\Api\FactFinderConnector
     */
    public function createFactFinderConnector()
    {
        return new FactFinderConnector($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\FactFinder\Business\Api\Handler\Request\SearchRequest
     */
    public function createSearchRequest()
    {
        return new SearchRequest(
            $this->createFactFinderConnector(),
            $this->createConverterFactory()
        );
    }

    /**
     * @return \Spryker\Zed\FactFinder\Business\Api\Converter\ConverterFactory
     */
    protected function createConverterFactory()
    {
        return new ConverterFactory();
    }

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
    public function createFactFinderCategoryCollectorClassName()
    {
        return '\Spryker\Zed\FactFinder\Business\Collector\File\FactFinderCategoryCollector';
    }

    /**
     * @return string
     */
    public function createFactFinderProductCollectorClassName()
    {
        return '\Spryker\Zed\FactFinder\Business\Collector\File\FactFinderProductCollector';
    }

}
