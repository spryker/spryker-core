<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig getConfig()
 */
class ProductAlternativeProductLabelConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function getProductAlternativePropelQuery(): SpyProductAlternativeQuery
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::PROPEL_QUERY_PRODUCT_ALTERNATIVE);
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function getProductLabelPropelQuery(): SpyProductLabelQuery
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::PROPEL_QUERY_PRODUCT_LABEL);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductPropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductAlternativeProductLabelConnectorDependencyProvider::PROPEL_QUERY_PRODUCT);
    }
}
