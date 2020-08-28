<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorRepositoryInterface getRepository()
 */
class ProductDiscontinuedProductLabelConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductPropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductDiscontinuedProductLabelConnectorDependencyProvider::PROPEL_QUERY_PRODUCT);
    }
}
