<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface;

class ShipmentCartExpander implements ShipmentCartExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpanderHelper
     */
    protected $shipmentCartExpanderHelper;

    /**
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface $priceFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpanderHelper $shipmentCartExpanderHelper
     */
    public function __construct(
        ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade,
        ShipmentCartConnectorToPriceFacadeInterface $priceFacade,
        ShipmentCartExpanderHelper $shipmentCartExpanderHelper
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->priceFacade = $priceFacade;
        $this->shipmentCartExpanderHelper = $shipmentCartExpanderHelper;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function updateShipmentPrice(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $quoteTransfer = $cartChangeTransfer->getQuote();
        $availableShipmentMethods = $this->shipmentFacade->getAvailableMethods($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skipUpdate = (
                $itemTransfer->getShipment() === null
                || !$this->shipmentCartExpanderHelper->isCurrencyChanged($itemTransfer->getShipment(), $quoteTransfer)
            );

            if ($skipUpdate) {
                continue;
            }

            $idShipmentMethod = $itemTransfer->getShipment()->getMethod()->getIdShipmentMethod();
            $shipmentMethodTransfer = $this->shipmentCartExpanderHelper->findAvailableMethodById($idShipmentMethod, $availableShipmentMethods);

            if ($shipmentMethodTransfer === null) {
                return $cartChangeTransfer;
            }

            $this->updateShipmentExpenses($itemTransfer->getShipment(), $shipmentMethodTransfer, $quoteTransfer);

            $shipmentMethodTransfer->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());

            $quoteTransfer->getShipment()->setMethod($shipmentMethodTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shipmentExpenseTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param string $priceMode
     *
     * @return void
     */
    protected function setExpensePrice(
        ExpenseTransfer $shipmentExpenseTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        CurrencyTransfer $currencyTransfer,
        $priceMode
    ): void {
        $netModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            if ($moneyValueTransfer->getCurrency()->getCode() !== $currencyTransfer->getCode()) {
                continue;
            }

            if ($priceMode === $netModeIdentifier) {
                $shipmentExpenseTransfer->setUnitGrossPrice(0);
                $shipmentExpenseTransfer->setUnitNetPrice($moneyValueTransfer->getNetAmount());

                return;
            }

            $shipmentExpenseTransfer->setUnitNetPrice(0);
            $shipmentExpenseTransfer->setUnitGrossPrice($moneyValueTransfer->getGrossAmount());

            return;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function updateShipmentExpenses(
        ShipmentTransfer $shipmentTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $priceMode = $quoteTransfer->getPriceMode();
        $currencyTransfer = $quoteTransfer->getCurrency();
        $shipmentExpenseTypeIdentifier = $this->shipmentFacade->getShipmentExpenseTypeIdentifier();

        $shipmentExpense = $shipmentTransfer->getExpense();

        if ($shipmentExpense === null || $shipmentExpense->getType() !== $shipmentExpenseTypeIdentifier) {
            return;
        }

        $this->setExpensePrice($shipmentExpense, $shipmentMethodTransfer, $currencyTransfer, $priceMode);
    }
}
