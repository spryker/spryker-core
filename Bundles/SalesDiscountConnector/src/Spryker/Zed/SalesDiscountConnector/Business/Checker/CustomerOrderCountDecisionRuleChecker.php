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
use Spryker\Zed\SalesDiscountConnector\SalesDiscountConnectorConfig;

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
     * @var \Spryker\Zed\SalesDiscountConnector\SalesDiscountConnectorConfig
     */
    protected SalesDiscountConnectorConfig $salesDiscountConnectorConfig;

    /**
     * @param \Spryker\Zed\SalesDiscountConnector\Dependency\Facade\SalesDiscountConnectorToDiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\SalesDiscountConnector\Dependency\Facade\SalesDiscountConnectorToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesDiscountConnector\SalesDiscountConnectorConfig $salesDiscountConnectorConfig
     */
    public function __construct(
        SalesDiscountConnectorToDiscountFacadeInterface $discountFacade,
        SalesDiscountConnectorToSalesFacadeInterface $salesFacade,
        SalesDiscountConnectorConfig $salesDiscountConnectorConfig
    ) {
        $this->discountFacade = $discountFacade;
        $this->salesFacade = $salesFacade;
        $this->salesDiscountConnectorConfig = $salesDiscountConnectorConfig;
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

        $orderTransfers = $this->salesFacade
            ->getCustomerOrders($orderListTransfer, $idCustomer)
            ->getOrders();

        $customerOrderCount = $this->excludeCurrentOrderFromCount(
            $quoteTransfer,
            $orderListTransfer,
            $orderTransfers->count(),
        );

        return $this->discountFacade->queryStringCompare($clauseTransfer, $customerOrderCount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $customerOrderCount
     *
     * @return int
     */
    protected function excludeCurrentOrderFromCount(
        QuoteTransfer $quoteTransfer,
        OrderListTransfer $orderListTransfer,
        int $customerOrderCount
    ): int {
        if (!$this->salesDiscountConnectorConfig->isCurrentOrderExcludedFromCount() || !$quoteTransfer->getOrderReference()) {
            return $customerOrderCount;
        }

        foreach ($orderListTransfer->getOrders() as $orderTransfer) {
            if ($orderTransfer->getOrderReferenceOrFail() === $quoteTransfer->getOrderReferenceOrFail()) {
                return $customerOrderCount - 1;
            }
        }

        return $customerOrderCount;
    }
}
