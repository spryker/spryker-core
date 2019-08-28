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
use Spryker\Shared\ShipmentCartConnector\ShipmentCartConnectorConstants;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface;

/**
 * @deprecated Use \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpander instead.
 */
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
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade
     * @param \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface $priceFacade
     */
    public function __construct(
        ShipmentCartConnectorToShipmentFacadeInterface $shipmentFacade,
        ShipmentCartConnectorToPriceFacadeInterface $priceFacade
    ) {
        $this->shipmentFacade = $shipmentFacade;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function updateShipmentPrice(CartChangeTransfer $cartChangeTransfer)
    {
        $quoteTransfer = $cartChangeTransfer->getQuote();
        if (!$quoteTransfer->getShipment() || !$this->isCurrencyChanged($quoteTransfer)) {
            return $cartChangeTransfer;
        }

        $idShipmentMethod = $quoteTransfer->getShipment()->getMethod()->getIdShipmentMethod();

        $shipmentMethodTransfer = $this->shipmentFacade->findAvailableMethodById($idShipmentMethod, $quoteTransfer);

        if (!$shipmentMethodTransfer) {
            return $cartChangeTransfer;
        }

        $this->updateShipmentExpenses($quoteTransfer, $shipmentMethodTransfer);

        $shipmentMethodTransfer->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());

        $quoteTransfer->getShipment()->setMethod($shipmentMethodTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isCurrencyChanged(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getShipment()->getMethod()) {
            return false;
        }

        $shipmentCurrencyIsoCode = $quoteTransfer->getShipment()->getMethod()->getCurrencyIsoCode();
        if ($shipmentCurrencyIsoCode !== $quoteTransfer->getCurrency()->getCode()) {
            return true;
        }

        return false;
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
    ) {

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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return void
     */
    protected function updateShipmentExpenses(QuoteTransfer $quoteTransfer, ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        $priceMode = $quoteTransfer->getPriceMode();
        $currencyTransfer = $quoteTransfer->getCurrency();
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentCartConnectorConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $this->setExpensePrice($expenseTransfer, $shipmentMethodTransfer, $currencyTransfer, $priceMode);
        }
    }
}
