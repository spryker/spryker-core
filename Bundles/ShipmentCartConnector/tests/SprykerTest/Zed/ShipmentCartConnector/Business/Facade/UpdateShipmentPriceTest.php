<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCartConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\ShipmentCartConnector\ShipmentCartConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentCartConnector
 * @group Business
 * @group Facade
 * @group UpdateShipmentPriceTest
 * Add your own group annotations below this line
 */
class UpdateShipmentPriceTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShipmentCartConnector\ShipmentCartConnectorBusinessTester
     */
    protected ShipmentCartConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldUpdatePriceBasedOnCurrency(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(ShipmentCartConnectorBusinessTester::CURRENCY_ISO_CODE_USD);
        $shipmentMethodTransfer->setStoreCurrencyPrice(-1);

        $cartChangeTransfer = $this->tester->createCartCartChangeTransfer($shipmentMethodTransfer, $storeTransfer);

        // Act
        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $quoteTransfer = $updatedCartChangeTransfer->getQuote();

        $this->assertSame($quoteTransfer->getShipment()->getMethod()->getCurrencyIsoCode(), $quoteTransfer->getCurrency()->getCode());

        $price = $quoteTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
        $this->assertNotEmpty($price);
        $this->assertNotSame(-1, $price);
    }

    /**
     * @return void
     */
    public function testShouldUpdatePriceBasedOnCurrencyWithItemLevelShipments(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(ShipmentCartConnectorBusinessTester::CURRENCY_ISO_CODE_USD);
        $shipmentMethodTransfer->setStoreCurrencyPrice(-1);

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        // Act
        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $quoteTransfer = $updatedCartChangeTransfer->getQuote();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertSame(
                $itemTransfer->getShipment()->getMethod()->getCurrencyIsoCode(),
                $quoteTransfer->getCurrency()->getCode(),
            );

            $price = $itemTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
            $this->assertNotEmpty($price);
            $this->assertNotSame(-1, $price);
        }
    }

    /**
     * @return void
     */
    public function testShouldUpdatePriceWithShipmentMethodSourcePrices(): void
    {
        // Arrange
        $sourcePrice = 322;
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(ShipmentCartConnectorBusinessTester::CURRENCY_ISO_CODE_USD);
        $shipmentMethodTransfer->setSourcePrice((new MoneyValueBuilder([MoneyValueTransfer::GROSS_AMOUNT => $sourcePrice]))->build());

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        // Act
        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $sourcePrice,
            $updatedCartChangeTransfer->getQuote()->getExpenses()->getIterator()->current()->getUnitGrossPrice(),
        );
    }

    /**
     * @return void
     */
    public function testShouldUpdatePriceWithQuoteShipmentMethodSourcePrice(): void
    {
        // Arrange
        $sourcePrice = 322;
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(ShipmentCartConnectorBusinessTester::CURRENCY_ISO_CODE_USD)
            ->setSourcePrice((new MoneyValueBuilder([MoneyValueTransfer::GROSS_AMOUNT => $sourcePrice]))->build());

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithQuoteLevelShipment($shipmentMethodTransfer, $storeTransfer);

        // Act
        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $sourcePrice,
            $updatedCartChangeTransfer->getQuote()->getExpenses()->getIterator()->current()->getUnitGrossPrice(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateShouldNotFailWithDifferentExpenseTypes(): void
    {
        // Arrange
        $sourcePrice = 322;
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => ShipmentCartConnectorBusinessTester::STORE_NAME_DE,
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], ShipmentCartConnectorBusinessTester::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(ShipmentCartConnectorBusinessTester::CURRENCY_ISO_CODE_USD)
            ->setSourcePrice((new MoneyValueBuilder([MoneyValueTransfer::GROSS_AMOUNT => $sourcePrice]))->build());

        $cartChangeTransfer = $this->tester->createCartChangeTransferWithDifferentExpenseTypes($shipmentMethodTransfer, $storeTransfer);

        // Act
        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $sourcePrice,
            $updatedCartChangeTransfer->getQuote()->getExpenses()->getIterator()->current()->getUnitGrossPrice(),
        );
    }
}
