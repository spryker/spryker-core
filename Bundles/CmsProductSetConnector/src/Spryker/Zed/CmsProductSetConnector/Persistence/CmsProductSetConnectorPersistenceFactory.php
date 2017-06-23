<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductSetConnector\Persistence;

use Spryker\Zed\CmsProductSetConnector\CmsProductSetConnectorDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsProductSetConnector\CmsProductSetConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsProductSetConnector\Persistence\CmsProductSetSetConnectorQueryContainer getQueryContainer()
 */
class CmsProductSetConnectorPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\CmsProductSetConnector\Dependency\QueryContainer\CmsProductSetConnectorProductSetQueryContainerInterface
     */
    public function getProductSetQueryContainer()
    {
        return $this->getProvidedDependency(CmsProductSetConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT_SET);
    }

}
