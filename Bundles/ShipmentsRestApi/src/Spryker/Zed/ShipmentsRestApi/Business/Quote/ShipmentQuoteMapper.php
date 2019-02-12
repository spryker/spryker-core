<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApi\Business\Quote;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface;

class ShipmentQuoteMapper implements ShipmentQuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentsRestApiToShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapShipmentToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        if (!$restCheckoutRequestAttributesTransfer->getShipment()
            || !$restCheckoutRequestAttributesTransfer->getShipment()->getIdShipmentMethod()
        ) {
            return $quoteTransfer;
        }
        $idShipmentMethod = $restCheckoutRequestAttributesTransfer->getShipment()->getIdShipmentMethod();

        $shipmentMethodTransfer = $this->shipmentFacade->findAvailableMethodById($idShipmentMethod, $quoteTransfer);
        if ($shipmentMethodTransfer === null) {
            return $quoteTransfer;
        }

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setMethod($shipmentMethodTransfer)
            ->setShipmentSelection((string)$idShipmentMethod);

        $quoteTransfer->setShipment($shipmentTransfer);

        $expenseTransfer = $this->createShippingExpenseTransfer($shipmentMethodTransfer);
        $quoteTransfer->addExpense($expenseTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer): ExpenseTransfer
    {
        $shipmentExpenseTransfer = new ExpenseTransfer();
        $shipmentExpenseTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $shipmentExpenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $shipmentExpenseTransfer->setUnitNetPrice(0);
        $shipmentExpenseTransfer->setUnitGrossPrice($shipmentMethodTransfer->getStoreCurrencyPrice());
        $shipmentExpenseTransfer->setQuantity(1);

        return $shipmentExpenseTransfer;
    }
}
