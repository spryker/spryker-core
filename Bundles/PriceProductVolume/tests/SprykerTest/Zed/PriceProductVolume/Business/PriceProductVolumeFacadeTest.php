<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductVolume\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\PriceProductVolume\Business\PriceProductVolumeFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductVolume
 * @group Business
 * @group Facade
 * @group PriceProductVolumeFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductVolumeFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';

    /**
     * @var int
     */
    protected const MONEY_VALUE = 10000;

    /**
     * @var string
     */
    protected const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @var string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var string
     */
    protected const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @var int
     */
    protected const PRICE_TYPE_ID = 1;

    /**
     * @var int
     */
    protected const CURRENCY_ID = 1;

    /**
     * @var int
     */
    protected const STORE_ID = 1;

    /**
     * @var string
     */
    protected const VALID_GROSS_NET_PRICE_CONSTRAINT_MESSAGE = 'Gross Default and/or Net Default price is required for volume price.';

    /**
     * @var string
     */
    protected const VALID_VOLUME_QUANTITY_CONSTRAINT_MESSAGE = 'Invalid volume quantity.';

    /**
     * @var string
     */
    protected const VALID_DEFAULT_PRICE_TYPE_CONSTRAINT_MESSAGE = 'Volume prices can only have DEFAULT prices.';

    /**
     * @var string
     */
    protected const VALID_UNIQUE_VOLUME_PRICE_CONSTRAINT_MESSAGE = 'The set of inputs Store, Currency, and Quantity needs to be unique.';

    /**
     * @var string
     */
    protected const VALID_VOLUME_PRICE_HAS_BASE_PRICE_CONSTRAINT_MESSAGE = 'A Price for Quantity of 2 or above requires a Price for 1 for this set of inputs Store, Currency, and Quantity.';

    /**
     * @var \SprykerTest\Zed\PriceProductVolume\PriceProductVolumeBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractPriceProductVolumesForProductAbstractReturnsNotEmptyArray(): void
    {
        $priceProductVolumeFacade = $this->getPriceProductVolumeFacade();
        $priceProducts = $this->preparePriceProductsWithVolumePrices();

        $volumePrices = $priceProductVolumeFacade->extractPriceProductVolumesForProductAbstract($priceProducts);

        $this->assertGreaterThan(1, count($volumePrices));
    }

    /**
     * @return void
     */
    public function testExtractPriceProductVolumesForProductConcreteReturnsEmptyArrayWithoutPriceData(): void
    {
        $priceProductVolumeFacade = $this->getPriceProductVolumeFacade();
        $priceProducts = $this->preparePriceProductsWithoutVolumePrices();

        $volumePrices = $priceProductVolumeFacade->extractPriceProductVolumesForProductConcrete($priceProducts);

        $this->assertCount(1, $volumePrices);
    }

    /**
     * @return void
     */
    public function testExtractPriceProductVolumeTransfersFromArray(): void
    {
        // Arrange
        $priceProductVolumeFacade = $this->getPriceProductVolumeFacade();
        $priceProducts = $this->preparePriceProductsWithVolumePrices();

        // Act
        $volumePrices = $priceProductVolumeFacade->extractPriceProductVolumeTransfersFromArray($priceProducts);

        // Assert
        $this->assertCount(2, $volumePrices);
    }

    /**
     * @return void
     */
    public function testValidatePriceProductsPassesValidation(): void
    {
        // Arrange
        $priceProducts = new ArrayObject($this->preparePriceProductsWithVolumePrices());

        // Act
        $validationResponseTransfer = $this->getPriceProductVolumeFacade()
            ->validateVolumePrices($priceProducts);

        // Assert
        $this->assertTrue($validationResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidatePriceProductsViolatesValidGrossNetPriceConstraint(): void
    {
        // Arrange
        $priceProducts = new ArrayObject($this->preparePriceProductsWithoutVolumePrices());
        $priceProducts[0]
            ->getMoneyValueOrFail()
            ->setPriceData('{"volume_prices":[{"quantity":200,"net_price":null,"gross_price":null}]}');

        // Act
        $validationResponseTransfer = $this->getPriceProductVolumeFacade()
            ->validateVolumePrices($priceProducts);

        // Assert
        $this->assertSame(
            static::VALID_GROSS_NET_PRICE_CONSTRAINT_MESSAGE,
            $validationResponseTransfer->getValidationErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidatePriceProductsViolatesValidVolumeQuantityConstraint(): void
    {
        // Arrange
        $priceProducts = new ArrayObject($this->preparePriceProductsWithoutVolumePrices());
        $priceProducts[0]
            ->getMoneyValueOrFail()
            ->setPriceData('{"volume_prices":[{"quantity":null,"net_price":123,"gross_price":123}]}');

        // Act
        $validationResponseTransfer = $this->getPriceProductVolumeFacade()
            ->validateVolumePrices($priceProducts);

        // Assert
        $this->assertSame(
            static::VALID_VOLUME_QUANTITY_CONSTRAINT_MESSAGE,
            $validationResponseTransfer->getValidationErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidatePriceProductsViolatesDefaultPriceTypeConstraint(): void
    {
        // Arrange
        $priceProducts = new ArrayObject($this->preparePriceProductsWithoutVolumePrices());
        $priceProducts[0]
            ->getMoneyValueOrFail()
            ->setPriceData('{"volume_prices":[{"quantity":2,"net_price":123,"gross_price":123}]}');
        $priceProducts[0]
            ->getPriceTypeOrFail()
            ->setName(static::PRICE_TYPE_ORIGINAL);

        // Act
        $validationResponseTransfer = $this->getPriceProductVolumeFacade()
            ->validateVolumePrices($priceProducts);

        // Assert
        $this->assertSame(
            static::VALID_DEFAULT_PRICE_TYPE_CONSTRAINT_MESSAGE,
            $validationResponseTransfer->getValidationErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidatePriceProductsViolatesUniqueVolumePriceConstraint(): void
    {
        // Arrange
        $priceProducts = new ArrayObject($this->preparePriceProductsWithoutVolumePrices());
        $priceProducts[0]
            ->getMoneyValueOrFail()
            ->setFkCurrency(static::CURRENCY_ID)
            ->setFkStore(static::STORE_ID)
            ->setPriceData('{"volume_prices":[{"quantity":2,"net_price":123,"gross_price":123},{"quantity":2,"net_price":123,"gross_price":123}]}');

        // Act
        $validationResponseTransfer = $this->getPriceProductVolumeFacade()
            ->validateVolumePrices($priceProducts);

        // Assert
        $this->assertSame(
            static::VALID_UNIQUE_VOLUME_PRICE_CONSTRAINT_MESSAGE,
            $validationResponseTransfer->getValidationErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidatePriceProductsViolatesVolumePriceHasBasePriceConstraint(): void
    {
        // Arrange
        $priceProducts = new ArrayObject($this->preparePriceProductsWithoutVolumePrices());
        $priceProducts[0]
            ->setVolumeQuantity(2)
            ->getMoneyValueOrFail()
            ->setGrossAmount(null)
            ->setNetAmount(null)
            ->setFkCurrency(static::CURRENCY_ID)
            ->setFkStore(static::STORE_ID)
            ->setPriceData('{"volume_prices":[{"quantity":2,"net_price":123,"gross_price":123}]}');

        // Act
        $validationResponseTransfer = $this->getPriceProductVolumeFacade()
            ->validateVolumePrices($priceProducts);

        // Assert
        $this->assertSame(
            static::VALID_VOLUME_PRICE_HAS_BASE_PRICE_CONSTRAINT_MESSAGE,
            $validationResponseTransfer->getValidationErrors()[0]->getMessage()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductVolume\Business\PriceProductVolumeFacade
     */
    protected function getPriceProductVolumeFacade(): PriceProductVolumeFacade
    {
        return new PriceProductVolumeFacade();
    }

    /**
     * @param int $netPrice
     * @param int $grossPrice
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        int $netPrice,
        int $grossPrice
    ): PriceProductTransfer {
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_DEFAULT);

        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setIdPriceType(static::PRICE_TYPE_ID)
            ->setName(static::PRICE_TYPE_DEFAULT);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension($priceDimensionTransfer)
            ->setPriceType($priceTypeTransfer);

        $moneyValueTransfer = $this->createMoneyValueTransfer(
            $grossPrice,
            $netPrice
        );

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param int $grossAmount
     * @param int $netAmount
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function createMoneyValueTransfer(
        int $grossAmount,
        int $netAmount
    ): MoneyValueTransfer {
        return (new MoneyValueTransfer())
            ->setNetAmount($netAmount)
            ->setGrossAmount($grossAmount);
    }

    /**
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function preparePriceProductsWithoutVolumePrices(): array
    {
        $priceProductTransfer = $this->createPriceProductTransfer(static::MONEY_VALUE, static::MONEY_VALUE);

        return [$priceProductTransfer];
    }

    /**
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function preparePriceProductsWithVolumePrices(): array
    {
        $priceProductTransfer = $this->createPriceProductTransfer(static::MONEY_VALUE, static::MONEY_VALUE);
        $priceProductTransfer->getMoneyValue()->setPriceData(static::PRICE_DATA_VOLUME);

        return [$priceProductTransfer];
    }
}
