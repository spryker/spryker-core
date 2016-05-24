<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface;

class ProductAttributeCollector
{
    /**
     * @var ProductDiscountConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @var ProductDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param ProductDiscountConnectorToProductInterface $productFacade
     * @param ProductDiscountConnectorToDiscountInterface $discountFacade
     */
    public function __construct(
        ProductDiscountConnectorToProductInterface $productFacade,
        ProductDiscountConnectorToDiscountInterface $discountFacade
    )
    {
        $this->productFacade = $productFacade;
        $this->discountFacade = $discountFacade;

    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param ClauseTransfer $clauseTransfer
     *
     * @return DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {

            $haveAttribute = $this->haveItemGivenAttributeInVariants($clauseTransfer, $itemTransfer);
            if ($haveAttribute) {
                $discountableItems[] = $this->createDiscountableItemTransfer($itemTransfer);
            }
        }

        return $discountableItems;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer(ItemTransfer $itemTransfer)
    {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->fromArray($itemTransfer->toArray(), true);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts());

        return $discountableItemTransfer;
    }

    /**
     * @param ClauseTransfer $clauseTransfer
     * @param ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function haveItemGivenAttributeInVariants(ClauseTransfer $clauseTransfer, ItemTransfer $itemTransfer)
    {
        $productVariants = $this->productFacade
            ->getProductVariantsByAbstractSku($itemTransfer->getAbstractSku());

        foreach ($productVariants as $productVariantTransfer) {
            $attributes = $productVariantTransfer->getAttributes();
            foreach ($attributes as $attribute => $value) {
                if ($clauseTransfer->getAttribute() !== $attribute) {
                    continue;
                }

                if ($this->discountFacade->queryStringCompare($clauseTransfer, $value)) {
                   return true;
                }
            }
        }
        return false;
    }
}
