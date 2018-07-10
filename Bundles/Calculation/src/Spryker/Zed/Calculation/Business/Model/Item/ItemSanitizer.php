<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Item;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;

class ItemSanitizer implements ItemSanitizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $items
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function removeSumPrices(ItemCollectionTransfer $items)
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $item */
        foreach ($items->getItems() as $item) {
            $item
                ->setSumDiscountAmountAggregation(null)
                ->setSumDiscountAmountFullAggregation(null)
                ->setSumNetPrice(null)
                ->setSumGrossPrice(null)
                ->setSumPrice(null)
                ->setSumPriceToPayAggregation(null)
                ->setSumExpensePriceAggregation(null)
                ->setSumProductOptionPriceAggregation(null)
                ->setSumSubtotalAggregation(null)
                ->setSumTaxAmountFullAggregation(null);

            // feature check
            if (defined($item::SUM_TAX_AMOUNT)) {
                $item->setSumTaxAmount(null);
            }

            $productOptions = $this->removeProductOptionSumPrices($item->getProductOptions()->getArrayCopy());

            $item->setProductOptions(new ArrayObject($productOptions));
        }

        return $items;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $productOptions
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function removeProductOptionSumPrices(array $productOptions): array
    {
        foreach ($productOptions as $productOption) {
            $productOption
                ->setSumPrice(null)
                ->setSumGrossPrice(null)
                ->setSumNetPrice(null)
                ->setSumDiscountAmountAggregation(null)
                ->setSumTaxAmount(null);
        }

        return $productOptions;
    }
}
