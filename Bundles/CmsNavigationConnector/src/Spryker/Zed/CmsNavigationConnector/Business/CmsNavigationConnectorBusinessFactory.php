<?php

namespace Spryker\Zed\CmsNavigationConnector\Business;

use Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodeReader;
use Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodesIsActiveUpdater;
use Spryker\Zed\CmsNavigationConnector\CmsNavigationConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CmsNavigationConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer\CmsNavigationConnectorToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(CmsNavigationConnectorDependencyProvider::QUERY_CONTAINER_CMS);
    }

    /**
     * @return \Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer\CmsNavigationConnectorToNavigationQueryContainerInterface
     */
    public function getNavigationQueryContainer()
    {
        return $this->getProvidedDependency(CmsNavigationConnectorDependencyProvider::QUERY_CONTAINER_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\CmsNavigationConnector\Dependency\Facade\CmsNavigationConnectorToNavigationFacadeInterface
     */
    public function getNavigationFacade()
    {
        return $this->getProvidedDependency(CmsNavigationConnectorDependencyProvider::FACADE_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodeReaderInterface
     */
    public function createNavigationNodeReader()
    {
        return new NavigationNodeReader($this->getCmsQueryContainer(), $this->getNavigationQueryContainer());
    }

    /**
     * @return \Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodesIsActiveUpdaterInterface
     */
    public function createNavigationNodesIsActiveUpdater()
    {
        return new NavigationNodesIsActiveUpdater($this->getNavigationFacade(), $this->createNavigationNodeReader());
    }
}
