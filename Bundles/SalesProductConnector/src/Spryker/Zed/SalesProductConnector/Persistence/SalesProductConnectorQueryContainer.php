<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorPersistenceFactory getFactory()
 */
class SalesProductConnectorQueryContainer extends AbstractQueryContainer implements SalesProductConnectorQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $fkSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery
     */
    public function queryProductMetadata($fkSalesOrderItem)
    {
        return $this->getFactory()->createProductMetadataQuery()->filterByFkSalesOrderItem($fkSalesOrderItem);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteBySku($sku)
    {
        return $this->getFactory()->getProductQueryContainer()
            ->queryProductConcreteBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $attributeKeys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryMatchingSuperAttributes(array $attributeKeys)
    {
        return $this->getFactory()->getProductQueryContainer()
            ->queryProductAttributeKey()
            ->filterByIsSuper(true)
            ->filterByKey($attributeKeys, Criteria::IN);
    }
}
