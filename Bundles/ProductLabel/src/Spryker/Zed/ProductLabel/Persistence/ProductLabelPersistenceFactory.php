<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductLabel\ProductLabelConfig getConfig()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainer getQueryContainer()
 */
class ProductLabelPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function createProductLabelQuery()
    {
        return SpyProductLabelQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function createLocalizedAttributesQuery()
    {
        return SpyProductLabelLocalizedAttributesQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function createProductRelationQuery()
    {
        return SpyProductLabelProductAbstractQuery::create();
    }

}
