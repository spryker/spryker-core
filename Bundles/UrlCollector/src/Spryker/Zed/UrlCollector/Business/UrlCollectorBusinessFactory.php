<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlCollector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\UrlCollector\Business\Collector\Storage\UrlCollector;
use Spryker\Zed\UrlCollector\Business\Collector\UrlCollectorRunner;
use Spryker\Zed\UrlCollector\Business\Collector\UrlCollectorRunnerInterface;
use Spryker\Zed\UrlCollector\Persistence\Collector\Propel\UrlCollectorQuery;
use Spryker\Zed\UrlCollector\UrlCollectorDependencyProvider;

/**
 * @method \Spryker\Zed\UrlCollector\UrlCollectorConfig getConfig()
 */
class UrlCollectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\UrlCollector\Dependency\Facade\UrlCollectorToCollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(UrlCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\UrlCollector\Business\Collector\Storage\UrlCollector
     */
    public function createStorageUrlCollector()
    {
        $collector = new UrlCollector(
            $this->getDataReaderService(),
            $this->getUrlQueryContainer()
        );

        $collector->setTouchQueryContainer($this->getTouchQueryContainer());
        $collector->setQueryBuilder($this->createUrlCollectorQuery());

        return $collector;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    public function getDataReaderService()
    {
        return $this->getProvidedDependency(UrlCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    public function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(UrlCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\UrlCollector\Dependency\QueryContainer\UrlCollectorToUrlQueryContainerInterface
     */
    public function getUrlQueryContainer()
    {
        return $this->getProvidedDependency(UrlCollectorDependencyProvider::QUERY_CONTAINER_URL);
    }

    /**
     * @return \Spryker\Zed\UrlCollector\Persistence\Collector\Propel\UrlCollectorQuery
     */
    public function createUrlCollectorQuery()
    {
        return new UrlCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\UrlCollector\Business\Collector\UrlCollectorRunnerInterface
     */
    public function createStorageUrlCollectorRunner(): UrlCollectorRunnerInterface
    {
        return new UrlCollectorRunner(
            $this->createStorageUrlCollector(),
            $this->getCollectorFacade()
        );
    }
}
