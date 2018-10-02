<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Persistence;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductLabelSearch\ProductLabelSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig getConfig()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainerInterface getQueryContainer()
 */
class ProductLabelSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function createSpyProductLabelProductAbstractQuery()
    {
        return SpyProductLabelProductAbstractQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function getPropelProductLabelQuery(): SpyProductLabelQuery
    {
        return $this->getProvidedDependency(ProductLabelSearchDependencyProvider::PROPEL_QUERY_PRODUCT_LABEL);
    }
}
