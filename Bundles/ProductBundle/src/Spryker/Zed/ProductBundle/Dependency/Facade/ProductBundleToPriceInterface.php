<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Dependency\Facade;

use Generated\Shared\Transfer\PriceFilterTransfer;

interface ProductBundleToPriceInterface
{
    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null);

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return int
     */
    public function getPriceFor(PriceFilterTransfer $priceFilterTransfer);

}
