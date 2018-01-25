<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Business;

use Spryker\Zed\CategoryNavigationConnector\Business\Model\NavigationNodeIsActiveUpdater;
use Spryker\Zed\CategoryNavigationConnector\Business\Model\NavigationNodeReader;
use Spryker\Zed\CategoryNavigationConnector\CategoryNavigationConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryNavigationConnector\CategoryNavigationConnectorConfig getConfig()
 */
class CategoryNavigationConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToCategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CategoryNavigationConnectorDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToNavigationQueryContainerInterface
     */
    public function getNavigationQueryContainer()
    {
        return $this->getProvidedDependency(CategoryNavigationConnectorDependencyProvider::QUERY_CONTAINER_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\CategoryNavigationConnector\Dependency\Facade\CategoryNavigationConnectorToNavigationFacadeInterface
     */
    public function getNavigationFacade()
    {
        return $this->getProvidedDependency(CategoryNavigationConnectorDependencyProvider::FACADE_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\CategoryNavigationConnector\Business\Model\NavigationNodeReaderInterface
     */
    public function createNavigationNodeReader()
    {
        return new NavigationNodeReader($this->getCategoryQueryContainer(), $this->getNavigationQueryContainer());
    }

    /**
     * @return \Spryker\Zed\CategoryNavigationConnector\Business\Model\NavigationNodeIsActiveUpdaterInterface
     */
    public function createNavigationNodesIsActiveUpdater()
    {
        return new NavigationNodeIsActiveUpdater($this->getNavigationFacade(), $this->createNavigationNodeReader());
    }
}
