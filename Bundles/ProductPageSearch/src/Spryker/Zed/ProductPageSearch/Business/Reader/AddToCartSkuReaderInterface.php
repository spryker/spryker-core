<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Reader;

interface AddToCartSkuReaderInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<string>
     */
    public function getProductAbstractAddToCartSkus(array $productAbstractIds): array;
}
