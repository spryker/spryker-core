<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductSalesOrderAmendment;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceFactory getFactory()
 */
class PriceProductSalesOrderAmendmentService extends AbstractService implements PriceProductSalesOrderAmendmentServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemPriceGroupKey(ItemTransfer $itemTransfer): string
    {
        return $this->getFactory()
            ->createOriginalSalesOrderItemPriceGroupKeyBuilder()
            ->buildOriginalSalesOrderItemPriceGroupKey($itemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $salesOrderItemUnitPrice
     * @param int $originalSalesOrderItemUnitPrice
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return int
     */
    public function resolveOriginalSalesOrderItemPrice(
        int $salesOrderItemUnitPrice,
        int $originalSalesOrderItemUnitPrice,
        ?QuoteTransfer $quoteTransfer = null
    ): int {
        return $this->getFactory()
            ->createOriginalSalesOrderItemPriceResolver()
            ->resolveOriginalSalesOrderItemPrice($salesOrderItemUnitPrice, $originalSalesOrderItemUnitPrice, $quoteTransfer);
    }
}
