<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApprovalShipmentConnector\Checker;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Client\QuoteApprovalShipmentConnector\Dependency\Service\QuoteApprovalShipmentConnectorToShipmentServiceInterface;

class QuoteShipmentChecker implements QuoteShipmentCheckerInterface
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE.
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var \Spryker\Client\QuoteApprovalShipmentConnector\Dependency\Service\QuoteApprovalShipmentConnectorToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Client\QuoteApprovalShipmentConnector\Dependency\Service\QuoteApprovalShipmentConnectorToShipmentServiceInterface $shipmentService
     */
    public function __construct(QuoteApprovalShipmentConnectorToShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteShipment(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->hasItemsWithoutShipment($quoteTransfer)) {
            return $this->isQuoteLevelShipment($quoteTransfer);
        }

        if ($quoteTransfer->getItems()->count() === 0) {
            return false;
        }

        $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            if (!$this->checkShipmentExpenseSetInQuote($quoteTransfer, $shipmentGroupTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasItemsWithoutShipment(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return bool
     */
    protected function checkShipmentExpenseSetInQuote(QuoteTransfer $quoteTransfer, ShipmentGroupTransfer $shipmentGroupTransfer): bool
    {
        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return false;
        }

        $shipmentHashKey = $this->shipmentService->getShipmentHashKey($shipmentTransfer);
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($this->matchShipmentExpense($expenseTransfer, $shipmentHashKey)) {
                return $shipmentTransfer->getShipmentSelection() !== null;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param string $itemShipmentKey
     *
     * @return bool
     */
    protected function matchShipmentExpense(ExpenseTransfer $expenseTransfer, string $itemShipmentKey): bool
    {
        return $expenseTransfer->getType() === static::SHIPMENT_EXPENSE_TYPE
            && $expenseTransfer->getShipment() !== null
            && $this->shipmentService->getShipmentHashKey($expenseTransfer->getShipment()) === $itemShipmentKey;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteLevelShipment(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getShipment() || !$quoteTransfer->getShippingAddress()) {
            return false;
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === static::SHIPMENT_EXPENSE_TYPE) {
                return $quoteTransfer->getShipment()->getShipmentSelection() !== null;
            }
        }

        return false;
    }
}
