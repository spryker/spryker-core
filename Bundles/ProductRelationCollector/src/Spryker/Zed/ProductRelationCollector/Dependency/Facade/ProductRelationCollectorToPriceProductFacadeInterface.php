<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationCollector\Dependency\Facade;

interface ProductRelationCollectorToPriceProductFacadeInterface
{
    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int|null
     */
    public function findPriceBySku($sku, $priceTypeName = null);

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku);

    /**
     * @param string $sku
     *
     * @return array
     */
    public function findPricesBySkuGrouped($sku);
}
