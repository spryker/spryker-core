<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributesMetadataTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductDiscountConnector\Persistence\ProductDiscountConnectorPersistenceFactory getFactory()
 */
class ProductDiscountConnectorQueryContainer extends AbstractQueryContainer implements ProductDiscountConnectorQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery
     */
    public function queryProductAttributeKeys()
    {
        return $this->getFactory()
            ->createProductAttributesMetadataQuery()
            ->select(SpyProductAttributesMetadataTableMap::COL_KEY);
    }

}
