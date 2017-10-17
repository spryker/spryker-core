<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationCollector\Business;

use Spryker\Shared\Navigation\KeyBuilder\NavigationKeyBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\NavigationCollector\Business\Collector\Storage\NavigationMenuCollector;
use Spryker\Zed\NavigationCollector\NavigationCollectorDependencyProvider;
use Spryker\Zed\NavigationCollector\Persistence\Collector\Propel\NavigationMenuCollectorQuery;

/**
 * @method \Spryker\Zed\NavigationCollector\NavigationCollectorConfig getConfig()
 */
class NavigationCollectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\NavigationCollector\Business\Collector\Storage\NavigationMenuCollector
     */
    public function createStorageNavigationMenuCollector()
    {
        $storageNavigationMenuCollector = new NavigationMenuCollector(
            $this->getUtilDataReaderService(),
            $this->getNavigationFacade(),
            $this->createNavigationKeyBuilder()
        );

        $storageNavigationMenuCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageNavigationMenuCollector->setQueryBuilder($this->createNavigationMenuCollectorQuery());

        return $storageNavigationMenuCollector;
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createNavigationKeyBuilder()
    {
        return new NavigationKeyBuilder();
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(NavigationCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(NavigationCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\NavigationCollector\Persistence\Collector\Propel\NavigationMenuCollectorQuery
     */
    protected function createNavigationMenuCollectorQuery()
    {
        return new NavigationMenuCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(NavigationCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\NavigationCollector\Dependency\Facade\NavigationCollectorToNavigationInterface
     */
    protected function getNavigationFacade()
    {
        return $this->getProvidedDependency(NavigationCollectorDependencyProvider::FACADE_NAVIGATION);
    }
}
