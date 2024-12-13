<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDiscountConnector\Business\Checker;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToDiscountFacadeInterface;

class MerchantReferenceDecisionRuleChecker implements MerchantReferenceDecisionRuleCheckerInterface
{
    /**
     * @var \Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToDiscountFacadeInterface
     */
    protected MerchantDiscountConnectorToDiscountFacadeInterface $discountFacade;

    /**
     * @param \Spryker\Zed\MerchantDiscountConnector\Dependency\Facade\MerchantDiscountConnectorToDiscountFacadeInterface $discountFacade
     */
    public function __construct(MerchantDiscountConnectorToDiscountFacadeInterface $discountFacade)
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
    public function isMerchantReferenceSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        if ($itemTransfer->getMerchantReference() === null) {
            return false;
        }

        return $this->discountFacade->queryStringCompare($clauseTransfer, $itemTransfer->getMerchantReference());
    }
}
