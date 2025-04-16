<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface;

class CartChangeReplacer implements CartChangeReplacerInterface
{
    /**
     * @param \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface $priceProductSalesOrderAmendmentService
     * @param \Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer\PriceReplacerInterface $priceReplacer
     */
    public function __construct(
        protected PriceProductSalesOrderAmendmentServiceInterface $priceProductSalesOrderAmendmentService,
        protected PriceReplacerInterface $priceReplacer
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function replaceOriginalSalesOrderItemPrices(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $quoteTransfer = $cartChangeTransfer->getQuoteOrFail();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $originalSalesOrderItemUnitPrice = $this->findOriginalSalesOrderItemUnitPrice($quoteTransfer, $itemTransfer);

            if (!$originalSalesOrderItemUnitPrice) {
                continue;
            }

            $this->priceReplacer->replaceOriginalSalesOrderItemUnitPrice(
                $itemTransfer,
                $quoteTransfer,
                $originalSalesOrderItemUnitPrice,
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int|null
     */
    protected function findOriginalSalesOrderItemUnitPrice(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer
    ): ?int {
        $originalSalesOrderItemPriceGroupKey = $this->priceProductSalesOrderAmendmentService
            ->buildOriginalSalesOrderItemPriceGroupKey($itemTransfer);

        return $quoteTransfer->getOriginalSalesOrderItemUnitPrices()[$originalSalesOrderItemPriceGroupKey] ?? null;
    }
}
