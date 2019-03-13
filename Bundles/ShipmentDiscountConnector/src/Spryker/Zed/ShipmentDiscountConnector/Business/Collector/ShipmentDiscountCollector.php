<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface;
use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountCollector as ShipmentDiscountWithoutMultiShipmentCollector;
use Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceInterface;

class ShipmentDiscountCollector extends ShipmentDiscountWithoutMultiShipmentCollector
{
    /**
     * @var \Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface $carrierDiscountDecisionRule
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Service\ShipmentDiscountConnectorToShipmentServiceInterface $shipmentService
     */
    public function __construct(
        ShipmentDiscountDecisionRuleInterface $carrierDiscountDecisionRule,
        ShipmentDiscountConnectorToShipmentServiceInterface $shipmentService
    ) {
        parent::__construct($carrierDiscountDecisionRule);

        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];

        $shipmentGroups = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        foreach ($shipmentGroups as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            if ($shipmentTransfer === null) {
                continue;
            }

            $expenseTransfer = $this->findShipmentExpense($quoteTransfer, $shipmentTransfer);
            if ($expenseTransfer === null) {
                continue;
            }

            if ($this->isShipmentExpenseSatisfiedBy($shipmentGroupTransfer, $expenseTransfer, $clauseTransfer)) {
                $discountableItems[] = $this->createDiscountableItemTransfer($expenseTransfer, $quoteTransfer->getPriceMode());
            }
        }

        return $discountableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findShipmentExpense(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): ?ExpenseTransfer
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expenseShipmentTransfer = $expenseTransfer->getShipment();
            if ($expenseShipmentTransfer !== $shipmentTransfer) {
                continue;
            }

            return $expenseTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    protected function isShipmentExpenseSatisfiedBy(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        ShipmentTransfer $expenseTransfer,
        ClauseTransfer $clauseTransfer
    ): ?ExpenseTransfer {
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            if (!$this->shipmentDiscountDecisionRule
                    ->isItemShipmentExpenseSatisfiedBy($itemTransfer, $expenseTransfer, $clauseTransfer)
            ) {
                return false;
            }
        }

        return true;
    }
}
