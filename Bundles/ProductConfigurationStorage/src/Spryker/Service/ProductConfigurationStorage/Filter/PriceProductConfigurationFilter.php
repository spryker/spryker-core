<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfigurationStorage\Filter;

use Generated\Shared\Transfer\PriceProductFilterTransfer;

class PriceProductConfigurationFilter implements PriceProductConfigurationFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filterProductConfigurationPrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        if ($priceProductFilterTransfer->getProductConfigurationInstance()) {
            return $priceProductTransfers;
        }

        $priceProductTransfers = array_filter(
            $priceProductTransfers,
            function ($priceProductTransfer) {
                /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
                return $priceProductTransfer->getPriceDimension()->getProductConfigurationConfiguratorKey() !== null;
            }
        );

        return $priceProductTransfers;
    }
}
