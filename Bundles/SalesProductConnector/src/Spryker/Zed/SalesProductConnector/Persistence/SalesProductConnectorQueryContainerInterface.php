<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface SalesProductConnectorQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $fkSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery
     */
    public function queryProductMetadata($fkSalesOrderItem);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteBySku($sku);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $attributeKeys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryMatchingSuperAttributes(array $attributeKeys);
}
