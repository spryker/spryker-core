<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface;
use Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface;

class ProductAttributeDecisionRule implements ProductAttributeDecisionRuleInterface
{

    /**
     * @var \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductDiscountConnector\Dependency\Facade\ProductDiscountConnectorToDiscountInterface $discountFacade
     */
    public function __construct(
        ProductDiscountConnectorToProductInterface $productFacade,
        ProductDiscountConnectorToDiscountInterface $discountFacade
    ) {
        $this->productFacade = $productFacade;
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        $productVariants = $this->productFacade
            ->getProductVariantsByAbstractSku($currentItemTransfer->getAbstractSku());

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
