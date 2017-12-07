<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Persistence;

use Orm\Zed\ProductManagementStoreConnector\Persistence\SpyProductAbstractStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductManagementStoreConnector\Persistence\ProductManagementStoreConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagementStoreConnector\ProductManagementStoreConnectorConfig getConfig()
 */
class ProductManagementStoreConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductManagementStoreConnector\Persistence\SpyProductAbstractStoreQuery
     */
    public function createProductAbstractStoreQuery()
    {
        return SpyProductAbstractStoreQuery::create();
    }
}
