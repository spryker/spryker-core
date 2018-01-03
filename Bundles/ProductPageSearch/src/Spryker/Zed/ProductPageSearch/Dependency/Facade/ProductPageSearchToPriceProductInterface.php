<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

interface ProductPageSearchToPriceProductInterface
{

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function findPriceBySku($sku, $priceTypeName = null);

    /**
     * @param string $sku
     *
     * @return array
     */
    public function findPricesBySkuGroupedForCurrentStore($sku);

}
