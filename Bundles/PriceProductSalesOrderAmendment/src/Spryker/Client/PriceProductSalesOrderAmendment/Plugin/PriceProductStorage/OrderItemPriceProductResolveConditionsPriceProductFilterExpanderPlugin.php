<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductSalesOrderAmendment\Plugin\PriceProductStorage;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductResolveConditionsTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductFilterExpanderPluginInterface;

/**
 * @method \Spryker\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentClientInterface getClient()
 */
class OrderItemPriceProductResolveConditionsPriceProductFilterExpanderPlugin extends AbstractPlugin implements PriceProductFilterExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `PriceProductFilterTransfer` with `PriceProductResolveConditionsTransfer` from `ProductViewTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function expand(
        ProductViewTransfer $productViewTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductFilterTransfer {
        return $priceProductFilterTransfer->setPriceProductResolveConditions(
            (new PriceProductResolveConditionsTransfer())->fromArray($productViewTransfer->toArray(), true),
        );
    }
}
