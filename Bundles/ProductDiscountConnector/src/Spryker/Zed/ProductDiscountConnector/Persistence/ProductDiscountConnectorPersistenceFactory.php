<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class ProductDiscountConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery
     */
    public function createProductAttributesMetadataQuery()
    {
        return SpyProductAttributesMetadataQuery::create();
    }
}
