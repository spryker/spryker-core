<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Business\Checker;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CustomerDiscountConnector\Dependency\Facade\CustomerDiscountConnectorToDiscountFacadeInterface;

class CustomerDecisionRuleChecker implements CustomerDecisionRuleCheckerInterface
{
    /**
     * @param \Spryker\Zed\CustomerDiscountConnector\Dependency\Facade\CustomerDiscountConnectorToDiscountFacadeInterface $discountFacade
     */
    public function __construct(protected CustomerDiscountConnectorToDiscountFacadeInterface $discountFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCustomerReferenceSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ): bool {
        $customerReference = $quoteTransfer->getCustomer()?->getCustomerReference();

        if (!$customerReference) {
            return false;
        }

        return $this->discountFacade->queryStringCompare($clauseTransfer, $customerReference);
    }
}
