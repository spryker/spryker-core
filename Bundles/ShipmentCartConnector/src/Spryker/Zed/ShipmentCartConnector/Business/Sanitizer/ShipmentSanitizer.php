<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\Sanitizer;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;

class ShipmentSanitizer implements ShipmentSanitizerInterface
{
    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_ADD
     */
    protected const OPERATION_ADD = 'add';

    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_REMOVE
     */
    protected const OPERATION_REMOVE = 'remove';

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function clearShipmentMethod(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        if (
            $cartChangeTransfer->getOperation() !== static::OPERATION_ADD
            && $cartChangeTransfer->getOperation() !== static::OPERATION_REMOVE
        ) {
            return $cartChangeTransfer;
        }

        $cartChangeTransfer = $this->clearQuoteLevelShipmentForEmptyQuote($cartChangeTransfer);
        $cartChangeTransfer = $this->clearQuoteLevelShipmentMethod($cartChangeTransfer);
        $cartChangeTransfer = $this->clearItemsShipmentMethod($cartChangeTransfer);
        $cartChangeTransfer = $this->clearShipmentExpenses($cartChangeTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function clearItemsShipmentMethod(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment()) {
                $itemTransfer->getShipment()->setMethod(null);
            }
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function clearShipmentExpenses(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $expenseTransfers = new ArrayObject();
        $quoteTransfer = $cartChangeTransfer->getQuote();

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                $expenseTransfers->append($expenseTransfer);
            }
        }

        $quoteTransfer->setExpenses($expenseTransfers);

        return $cartChangeTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function clearQuoteLevelShipmentForEmptyQuote(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $quoteTransfer = $cartChangeTransfer->getQuote();

        if (!$quoteTransfer->getItems()->count()) {
            $quoteTransfer->setShipment(null);
        }

        return $cartChangeTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function clearQuoteLevelShipmentMethod(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $quoteTransfer = $cartChangeTransfer->getQuote();

        if ($quoteTransfer->getShipment()) {
            $quoteTransfer->getShipment()->setMethod(null);
        }

        return $cartChangeTransfer;
    }
}
