<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCartConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\ShipmentCartConnector\Business\ShipmentCartConnectorFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShipmentCartConnector
 * @group Business
 * @group Facade
 * @group ShipmentCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentCartConnectorFacadeTest extends Unit
{
    const CURRENCY_ISO_CODE = 'USD';

    /**
     * @var \SprykerTest\Zed\ShipmentCartConnector\ShipmentCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateShipmentPriceShouldUpdatePriceBasedOnCurrency()
    {
        $shipmentCartConnectorFacade = $this->createShipmentCartConnectorFacade();

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        $shipmentMethodTransfer->setCurrencyIsoCode(static::CURRENCY_ISO_CODE);
        $shipmentMethodTransfer->setStoreCurrencyPrice(-1);

        $cartChangeTransfer = $this->createCartCartChangeTransfer($shipmentMethodTransfer);

        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        $quoteTransfer = $updatedCartChangeTransfer->getQuote();

        $this->assertSame($quoteTransfer->getShipment()->getMethod()->getCurrencyIsoCode(), $quoteTransfer->getCurrency()->getCode());

        $price = $quoteTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
        $this->assertNotEmpty($price);
        $this->assertNotEquals(-1, $price);
    }

    /**
     * @return void
     */
    public function testValidateShipmentShouldReturnFalseWhenSelectedShipmentHaveNoPrice()
    {
        $shipmentCartConnectorFacade = $this->createShipmentCartConnectorFacade();

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        $cartChangeTransfer = $this->createCartCartChangeTransfer($shipmentMethodTransfer);

        $cartChangeTransfer->getQuote()->getCurrency()->setCode('LTL');

        $cartPreCheckResponseTransfer = $shipmentCartConnectorFacade->validateShipment($cartChangeTransfer);

        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testValidateShipmentShouldReturnTrueWhenSelectedShipmentHavePrice()
    {
        $shipmentCartConnectorFacade = $this->createShipmentCartConnectorFacade();

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        $cartChangeTransfer = $this->createCartCartChangeTransfer($shipmentMethodTransfer);

        $cartPreCheckResponseTransfer = $shipmentCartConnectorFacade->validateShipment($cartChangeTransfer);

        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Business\ShipmentCartConnectorFacadeInterface
     */
    protected function createShipmentCartConnectorFacade()
    {
        return new ShipmentCartConnectorFacade();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartCartChangeTransfer(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        $cartChangeTransfer = (new CartChangeBuilder())->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withCurrency()
            ->withExpense()
            ->build();

        $shipmentTransfer = (new ShipmentBuilder())->build();

        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $quoteTransfer->setShipment($shipmentTransfer);

        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }
}
