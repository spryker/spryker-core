<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentExpense;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;

class ShipmentExpenseCollectionRemover implements ShipmentExpenseCollectionRemoverInterface
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var string
     */
    protected $shipmentExpenseType;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(ShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;

        $this->setShipmentExpenseType();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     * @param string $shipmentHash
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[]
     */
    public function removeByShipmentHash(ArrayObject $expenseTransfers, string $shipmentHash): ArrayObject
    {
        foreach ($expenseTransfers as $expenseIndex => $expenseTransfer) {
            if ($this->isExpenseShipmentMatchesWithHash($expenseTransfer, $shipmentHash)) {
                $expenseTransfers->offsetUnset($expenseIndex);

                break;
            }
        }

        return $expenseTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param string $shipmentHash
     *
     * @return bool
     */
    protected function isExpenseShipmentMatchesWithHash(ExpenseTransfer $expenseTransfer, string $shipmentHash): bool
    {
        if ($expenseTransfer->getType() !== $this->shipmentExpenseType) {
            return false;
        }

        $expenseShipmentTransfer = $expenseTransfer->requireShipment()->getShipment();
        $expenseShipmentHash = $this->shipmentService->getShipmentHashKey($expenseShipmentTransfer);

        return $expenseShipmentHash === $shipmentHash;
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentExpense\ShipmentExpenseCollectionRemoverInterface
     */
    protected function setShipmentExpenseType(): ShipmentExpenseCollectionRemoverInterface
    {
        $this->shipmentExpenseType = $this->shipmentService->getShipmentExpenseType();

        return $this;
    }
}
