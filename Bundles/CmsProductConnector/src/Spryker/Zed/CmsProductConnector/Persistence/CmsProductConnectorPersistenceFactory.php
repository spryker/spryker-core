<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductConnector\Persistence;

use Spryker\Zed\CmsProductConnector\CmsProductConnectorDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsProductConnector\CmsProductConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsProductConnector\Persistence\CmsProductConnectorQueryContainer getQueryContainer()
 */
class CmsProductConnectorPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\CmsProductConnector\Dependency\QueryContainer\CmsProductConnectorProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(CmsProductConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

}
