<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business;

use Spryker\Zed\CmsUserConnector\Business\Version\CmsVersionUserExpander;
use Spryker\Zed\CmsUserConnector\Business\Version\CmsVersionUserUpdater;
use Spryker\Zed\CmsUserConnector\CmsUserConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsUserConnector\CmsUserConnectorConfig getConfig()
 */
class CmsUserConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsUserConnector\Business\Version\CmsVersionUserUpdaterInterface
     */
    public function createCmsVersionUserUpdater()
    {
        return new CmsVersionUserUpdater(
            $this->getUserFacade(),
            $this->getCmsQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\CmsUserConnector\Business\Version\CmsVersionUserExpanderInterface
     */
    public function createCmsVersionUserExpander()
    {
        return new CmsVersionUserExpander(
            $this->getUserFacade()
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
