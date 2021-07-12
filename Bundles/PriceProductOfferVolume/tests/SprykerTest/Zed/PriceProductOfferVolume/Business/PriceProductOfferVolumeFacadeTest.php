<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOfferVolume\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductOfferVolume
 * @group Business
 * @group Facade
 * @group PriceProductOfferVolumeFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductOfferVolumeFacadeTest extends Unit
{
    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';
    protected const MONEY_VALUE = 10000;
    protected const PRICE_DIMENSION_TYPE = 'PRODUCT_OFFER';
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    protected const VALID_GROSS_NET_PRICE_CONSTRAINT_MESSAGE = 'Gross Default and/or Net Default price is required for volume price.';
    protected const UNIQUE_STORE_CURRENCY_VOLUME_QUANTITY_CONSTRAINT_MESSAGE = 'The set of Store, Currency, and Quantity needs to be unique.';
    protected const VOLUME_QUANTITY_CONSTRAINT_MESSAGE = 'Invalid volume quantity.';

    /**
     * @var \SprykerTest\Business\PriceProductOfferVolume\PriceProductOfferVolumeBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractVolumePrices(): void
    {
        // Arrange
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_TYPE);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension($priceDimensionTransfer);

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setNetAmount(static::MONEY_VALUE)
            ->setGrossAmount(static::MONEY_VALUE)
            ->setPriceData(static::PRICE_DATA_VOLUME);

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        // Act
        $volumePrices = $this->tester
            ->getFacade()
            ->extractVolumePrices([$priceProductTransfer]);

        // Assert
        $this->assertCount(2, $volumePrices);
    }

    /**
     * @return void
     */
    public function testExpandPriceProductTransferExpandsWithOneIfPriceDataIsNotSet(): void
    {
        // Arrange
        $priceProductTransfer = new PriceProductTransfer();

        // Act
        $priceProductTransferExpanded = $this->tester
            ->getFacade()
            ->expandPriceProductTransfer($priceProductTransfer);

        // Assert
        $this->assertSame(1, $priceProductTransferExpanded->getVolumeQuantity());
    }

    /**
     * @return void
     */
    public function testExpandPriceProductTransferExpandsWithCorrectDataIfPriceDataIsSet(): void
    {
        // Arrange
        $moneyValueTransfer = (new MoneyValueTransfer())->setPriceData('{"quantity":5}');
        $priceProductTransfer = (new PriceProductTransfer())->setMoneyValue($moneyValueTransfer);

        // Act
        $priceProductTransferExpanded = $this->tester
            ->getFacade()
            ->expandPriceProductTransfer($priceProductTransfer);

        // Assert
        $this->assertSame(5, $priceProductTransferExpanded->getVolumeQuantity());
    }

    /**
     * @return void
     */
    public function testValidatePriceProductOfferCollectionPassesValidation(): void
    {
        // Arrange
        $priceProductOfferCollectionTransfer = $this->tester->createValidPriceProductOfferCollection();

        // Act
        /** @var \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer */
        $validationResponseTransfer = $priceProductTransferExpanded = $this->tester
            ->getFacade()
            ->validatePriceProductOfferCollection($priceProductOfferCollectionTransfer);

        // Assert
        $this->assertTrue($validationResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidatePriceProductOfferCollectionViolatesValidGrossNetPriceConstraint(): void
    {
        // Arrange
        $priceProductOfferCollectionTransfer = $this->tester->createValidPriceProductOfferCollection();
        $priceProductOfferCollectionTransfer
            ->getPriceProductOffers()[0]
            ->getProductOfferOrFail()
            ->getPrices()[0]
            ->getMoneyValueOrFail()
            ->setPriceData('{"volume_prices":[{"quantity":200,"net_price":null,"gross_price":null}]}');

        // Act
        /** @var \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer */
        $validationResponseTransfer = $priceProductTransferExpanded = $this->tester
            ->getFacade()
            ->validatePriceProductOfferCollection($priceProductOfferCollectionTransfer);

        // Assert
        $this->assertSame(
            static::VALID_GROSS_NET_PRICE_CONSTRAINT_MESSAGE,
            $validationResponseTransfer->getValidationErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidatePriceProductOfferCollectionViolatesUniqueStoreCurrencyVolumeQuantityConstraint(): void
    {
        // Arrange
        $priceProductOfferCollectionTransfer = $this->tester->createValidPriceProductOfferCollection();
        $priceProductOfferCollectionTransfer
            ->getPriceProductOffers()[0]
            ->getProductOfferOrFail()
            ->getPrices()[0]
            ->getMoneyValueOrFail()
            ->setFkStore(2)
            ->setFkCurrency(2)
            ->setPriceData('{"volume_prices":[{"quantity":10,"net_price":100,"gross_price":120}]}');

        // Act
        /** @var \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer */
        $validationResponseTransfer = $priceProductTransferExpanded = $this->tester
            ->getFacade()
            ->validatePriceProductOfferCollection($priceProductOfferCollectionTransfer);

        // Assert
        $this->assertSame(
            static::UNIQUE_STORE_CURRENCY_VOLUME_QUANTITY_CONSTRAINT_MESSAGE,
            $validationResponseTransfer->getValidationErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidatePriceProductOfferCollectionViolatesVolumeQuantityConstraint(): void
    {
        // Arrange
        $priceProductOfferCollectionTransfer = $this->tester->createValidPriceProductOfferCollection();
        $priceProductOfferCollectionTransfer
            ->getPriceProductOffers()[0]
            ->getProductOfferOrFail()
            ->getPrices()[0]
            ->getMoneyValueOrFail()
            ->setPriceData('{"volume_prices":[{"quantity":0,"net_price":100,"gross_price":120}]}');

        // Act
        /** @var \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer */
        $validationResponseTransfer = $priceProductTransferExpanded = $this->tester
            ->getFacade()
            ->validatePriceProductOfferCollection($priceProductOfferCollectionTransfer);

        // Assert
        $this->assertSame(
            static::VOLUME_QUANTITY_CONSTRAINT_MESSAGE,
            $validationResponseTransfer->getValidationErrors()[0]->getMessage()
        );
    }
}
