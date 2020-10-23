<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Expander;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class PriceProductFilterExpander implements PriceProductFilterExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function expandPriceProductFilterWithProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductFilterTransfer {
        $productConfigurationInstance = $productViewTransfer->getProductConfigurationInstance();
        if (!$productConfigurationInstance || !$productConfigurationInstance->getPrices()->count()) {
            return $priceProductFilterTransfer;
        }

        $priceProductFilterTransfer->setSku($productViewTransfer->getSku());

        return $priceProductFilterTransfer->setProductConfigurationInstance($productConfigurationInstance);
    }
}
