<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferDiscountConnector\Business\Checker;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOfferDiscountConnector\Dependency\Facade\ProductOfferDiscountConnectorToDiscountFacadeInterface;

class ProductOfferDecisionRuleChecker implements ProductOfferDecisionRuleCheckerInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferDiscountConnector\Dependency\Facade\ProductOfferDiscountConnectorToDiscountFacadeInterface
     */
    protected ProductOfferDiscountConnectorToDiscountFacadeInterface $discountFacade;

    /**
     * @param \Spryker\Zed\ProductOfferDiscountConnector\Dependency\Facade\ProductOfferDiscountConnectorToDiscountFacadeInterface $discountFacade
     */
    public function __construct(ProductOfferDiscountConnectorToDiscountFacadeInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isProductOfferReferenceSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ): bool {
        if (!$itemTransfer->getProductOfferReference()) {
            return false;
        }

        return $this->discountFacade->queryStringCompare($clauseTransfer, $itemTransfer->getProductOfferReference());
    }
}
