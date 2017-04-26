<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business;

use Spryker\Zed\CmsUserConnector\Business\Version\UserManager;
use Spryker\Zed\CmsUserConnector\CmsUserConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsUserConnector\CmsUserConnectorConfig getConfig()
 */
class CmsUserConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CmsUserConnector\Business\Version\UserManagerInterface
     */
    public function createUserManager()
    {
        return new UserManager(
            $this->getUserFacade(),
            $this->getCmsQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface
     */
    public function getUserFacade()
    {
        return $this->getProvidedDependency(CmsUserConnectorDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\CmsUserConnector\Dependency\QueryContainer\CmsUserConnectorToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(CmsUserConnectorDependencyProvider::QUERY_CONTAINER_CMS);
    }

}
