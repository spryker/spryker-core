<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroupDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface;
use Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToDiscountFacadeInterface;

class CustomerGroupDecisionRule implements CustomerGroupDecisionRuleInterface
{
    /**
     * @var \Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToDiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface
     */
    protected $customerGroupFacade;

    /**
     * @param \Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToDiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface $customerGroupFacade
     */
    public function __construct(
        CustomerGroupDiscountConnectorToDiscountFacadeInterface $discountFacade,
        CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface $customerGroupFacade
    ) {
        $this->discountFacade = $discountFacade;
        $this->customerGroupFacade = $customerGroupFacade;
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
        if (!$this->rulePreCheck($quoteTransfer)) {
            return false;
        }

        $customerTransfer = $quoteTransfer->getCustomer();

        $customerGroupCollectionTransfer = $this->customerGroupFacade
            ->getCustomerGroupCollectionByIdCustomer($customerTransfer->getIdCustomer());

        $customerGroups = $customerGroupCollectionTransfer->getGroups();
        if (!$customerGroups || count($customerGroups) === 0) {
            return false;
        }

        foreach ($customerGroups as $customerGroup) {
            $lowerCustomerGroupName = mb_strtolower($customerGroup->getName());

            if ($this->discountFacade->queryStringCompare($clauseTransfer, $lowerCustomerGroupName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function rulePreCheck(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getCustomer()) {
            return false;
        }

        if (!$quoteTransfer->getCustomer()->getIdCustomer()) {
            return false;
        }

        return true;
    }
}
