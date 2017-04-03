<?php

namespace Spryker\Zed\CmsUserConnector\Business;

use Spryker\Zed\CmsUserConnector\Business\Version\UserManager;
use Spryker\Zed\CmsUserConnector\Business\Version\UserManagerInterface;
use Spryker\Zed\CmsUserConnector\CmsUserConnectorDependencyProvider;
use Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface;
use Spryker\Zed\CmsUserConnector\Dependency\QueryContainer\CmsUserConnectorToCmsQueryContainerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsUserConnector\CmsUserConnectorConfig getConfig()
 */
class CmsUserConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return UserManagerInterface
     */
    public function createUserManager()
    {
        return new UserManager(
            $this->getUserFacade(),
            $this->getCmsQueryContainer()
        );
    }

    /**
     * @return CmsUserConnectorToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(CmsUserConnectorDependencyProvider::FACADE_USER);
    }

    /**
     * @return CmsUserConnectorToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(CmsUserConnectorDependencyProvider::QUERY_CONTAINER_CMS);
    }
}
