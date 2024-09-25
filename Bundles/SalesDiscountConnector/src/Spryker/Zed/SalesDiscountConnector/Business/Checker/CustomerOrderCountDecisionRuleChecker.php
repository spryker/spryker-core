<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDiscountConnector\Business\Checker;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesDiscountConnector\Dependency\Facade\SalesDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\SalesDiscountConnector\Dependency\Facade\SalesDiscountConnectorToSalesFacadeInterface;

class CustomerOrderCountDecisionRuleChecker implements CustomerOrderCountDecisionRuleCheckerInterface
{
    /**
     * @var \Spryker\Zed\SalesDiscountConnector\Dependency\Facade\SalesDiscountConnectorToDiscountFacadeInterface
     */
    protected SalesDiscountConnectorToDiscountFacadeInterface $discountFacade;

    /**
     * @var \Spryker\Zed\SalesDiscountConnector\Dependency\Facade\SalesDiscountConnectorToSalesFacadeInterface
     */
    protected SalesDiscountConnectorToSalesFacadeInterface $salesFacade;

    /**
     * @param \Spryker\Zed\SalesDiscountConnector\Dependency\Facade\SalesDiscountConnectorToDiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\SalesDiscountConnector\Dependency\Facade\SalesDiscountConnectorToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        SalesDiscountConnectorToDiscountFacadeInterface $discountFacade,
        SalesDiscountConnectorToSalesFacadeInterface $salesFacade
    ) {
        $this->discountFacade = $discountFacade;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCustomerOrderCountSatisfiedBy(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): bool
    {
        if (!$quoteTransfer->getCustomer() || !$quoteTransfer->getCustomerOrFail()->getIdCustomer()) {
            return false;
        }

        $idCustomer = $quoteTransfer->getCustomerOrFail()->getIdCustomerOrFail();
        $orderListTransfer = (new OrderListTransfer())
            ->setWithoutSearchOrderExpanders(true);

        $customerOrderCount = $this->salesFacade
            ->getCustomerOrders($orderListTransfer, $idCustomer)
            ->getOrders()
            ->count();

        return $this->discountFacade->queryStringCompare($clauseTransfer, $customerOrderCount);
    }
}
