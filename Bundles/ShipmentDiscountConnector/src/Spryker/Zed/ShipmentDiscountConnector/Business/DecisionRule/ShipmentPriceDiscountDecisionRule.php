<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\ShipmentDiscountConnector\ShipmentDiscountConnectorConfig;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\DecisionRule\ShipmentPriceDiscountDecisionRule as ShipmentPriceDiscountDecisionRuleWithMultiShipment;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToMoneyInterface;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceInterface;

class ShipmentPriceDiscountDecisionRule extends ShipmentPriceDiscountDecisionRuleWithMultiShipment
{
    /**
     * @var \Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToDiscountInterface $discountFacade
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentDiscountConnectorToDiscountInterface $discountFacade,
        ShipmentDiscountConnectorToMoneyInterface $moneyFacade,
        ShipmentDiscountConnectorToShipmentServiceInterface $shipmentService
    ) {
        parent::__construct($discountFacade, $moneyFacade);

        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        if ($itemTransfer->getShipment() === null) {
            return false;
        }

        $expenseTransfer = $this->findQuoteExpenseByShipment($quoteTransfer, $itemTransfer->getShipment());

        if ($expenseTransfer === null) {
            return false;
        }

        return $this->isSatisfiedPrice($expenseTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isExpenseSatisfiedBy(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->isSatisfiedPrice($expenseTransfer, $clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    protected function isSatisfiedPrice(ExpenseTransfer $expenseTransfer, ClauseTransfer $clauseTransfer): bool
    {
        $moneyAmount = $this->moneyFacade->convertIntegerToDecimal($expenseTransfer->getUnitGrossPrice());

        return $this->discountFacade->queryStringCompare($clauseTransfer, $moneyAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findQuoteExpenseByShipment(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): ?ExpenseTransfer
    {
        $itemShipmentKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseShipmentKey = $this->shipmentService->getShipmentHashKey($expenseTransfer->getShipment());
            if ($expenseTransfer->getType() === ShipmentDiscountConnectorConfig::SHIPMENT_EXPENSE_TYPE
                && $expenseTransfer->getShipment() !== null
                && $expenseShipmentKey === $itemShipmentKey
            ) {
                return $expenseTransfer;
            }
        }

        return null;
    }
}
