<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery;
use Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelQuery;
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
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelQuery
     */
    public function getPropelProductLabelQuery(): SpyProductLabelQuery
    {
        return $this->getProvidedDependency(ProductLabelSearchDependencyProvider::PROPEL_QUERY_PRODUCT_LABEL);
    }
}
