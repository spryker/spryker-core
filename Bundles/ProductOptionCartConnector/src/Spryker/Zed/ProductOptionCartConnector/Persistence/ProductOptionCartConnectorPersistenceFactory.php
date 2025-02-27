<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Persistence;

use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOptionCartConnector\Persistence\ProductOptionCartConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorConfig getConfig()
 */
class ProductOptionCartConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function getProductOptionValuePropelQuery(): SpyProductOptionValueQuery
    {
        return $this->getProvidedDependency(ProductOptionCartConnectorDependencyProvider::PROPEL_QUERY_PRODUCT_OPTION_VALUE);
    }
}
