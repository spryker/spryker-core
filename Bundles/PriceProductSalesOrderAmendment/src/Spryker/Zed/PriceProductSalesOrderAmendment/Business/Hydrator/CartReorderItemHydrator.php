<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Business\Hydrator;

use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    /**
     * @param \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface $priceProductSalesOrderAmendmentService
     */
    public function __construct(protected PriceProductSalesOrderAmendmentServiceInterface $priceProductSalesOrderAmendmentService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateOriginalSalesOrderItemPrices(
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        foreach ($cartReorderTransfer->getOrderItems() as $itemTransfer) {
            $cartReorderTransfer->getQuoteOrFail()->addOriginalSalesOrderItemUnitPrice(
                $this->priceProductSalesOrderAmendmentService->buildOriginalSalesOrderItemPriceGroupKey($itemTransfer),
                $itemTransfer->getUnitPriceOrFail(),
            );
        }

        return $cartReorderTransfer;
    }
}
