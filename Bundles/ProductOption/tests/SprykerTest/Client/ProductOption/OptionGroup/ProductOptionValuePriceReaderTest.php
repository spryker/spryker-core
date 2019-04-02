<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StorageProductOptionGroupTransfer;
use Generated\Shared\Transfer\StorageProductOptionValueTransfer;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToCurrencyClientInterface;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToPriceClientInterface;
use Spryker\Client\ProductOption\OptionGroup\ProductOptionValuePriceReader;
use Spryker\Shared\ProductOption\ProductOptionConstants;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group OptionGroup
 * @group ProductOptionValuePriceReaderTest
 * Add your own group annotations below this line
 */
class ProductOptionValuePriceReaderTest extends Unit
{
    public const CURRENT_CURRENCY_CODE = 'EUR';
    public const OTHER_CURRENCY_CODE = 'USD';
    public const CURRENT_PRICE_MODE = 'price_mode_net';
    public const OTHER_PRICE_MODE = 'price_mode_gross';

    /**
     * @var \Spryker\Client\ProductOption\OptionGroup\ProductOptionValuePriceReaderInterface
     */
    protected $productOptionValuePriceReader;

    /**
     * @var \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToPriceClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $priceClientMock;

    /**
     * @var \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToCurrencyClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $currencyClientMock;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->priceClientMock = $this->getMockBuilder(ProductOptionToPriceClientInterface::class)->getMock();
        $this->currencyClientMock = $this->getMockBuilder(ProductOptionToCurrencyClientInterface::class)->getMock();

        $this->mockCurrentCurrency();
        $this->mockCurrentPriceMode();

        $this->productOptionValuePriceReader = new ProductOptionValuePriceReader(
            $this->priceClientMock,
            $this->currencyClientMock
        );
    }

    /**
     * @return void
     */
    public function testLocalizeGroupPricesRemovesOptionsWhenCurrentCurrencyIsNotDefined()
    {
        // Assign
        $productOptionGroupTransfer = new StorageProductOptionGroupTransfer();
        $productOptionGroupTransfer->addValue(
            (new StorageProductOptionValueTransfer())
                ->setPrices(
                    [
                        static::OTHER_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 300],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 400],
                            ],
                    ]
                )
        );
        $productOptionGroupTransfer->addValue(
            (new StorageProductOptionValueTransfer())
                ->setPrices(
                    [
                        static::CURRENT_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 500],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 600],
                            ],
                        static::OTHER_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 700],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 800],
                            ],
                    ]
                )
        );
        $expectedPrices = [500];

        // Act
        $this->productOptionValuePriceReader->localizeGroupPrices($productOptionGroupTransfer);

        // Assert
        $actualPrices = $this->filterPrices($productOptionGroupTransfer);
        $this->assertEquals($expectedPrices, $actualPrices);
    }

    /**
     * @return void
     */
    public function testLocalizeGroupPricesRemovesOptionsWhenPriceIsNull()
    {
        // Assign
        $productOptionGroupTransfer = new StorageProductOptionGroupTransfer();
        $productOptionGroupTransfer->addValue(
            (new StorageProductOptionValueTransfer())
                ->setPrices(
                    [
                        static::CURRENT_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => null],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 200],
                            ],
                        static::OTHER_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 300],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 400],
                            ],
                    ]
                )
        );
        $productOptionGroupTransfer->addValue(
            (new StorageProductOptionValueTransfer())
                ->setPrices(
                    [
                        static::CURRENT_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 500],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 600],
                            ],
                        static::OTHER_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 700],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 800],
                            ],
                    ]
                )
        );
        $expectedPrices = [500];

        // Act
        $this->productOptionValuePriceReader->localizeGroupPrices($productOptionGroupTransfer);

        // Assert
        $actualPrices = $this->filterPrices($productOptionGroupTransfer);
        $this->assertEquals($expectedPrices, $actualPrices);
    }

    /**
     * @return void
     */
    public function testLocalizeGroupPricesLocalizesAllProductOptionValuePrices()
    {
        // Assign
        $productOptionGroupTransfer = new StorageProductOptionGroupTransfer();
        $productOptionGroupTransfer->addValue(
            (new StorageProductOptionValueTransfer())
                ->setPrices(
                    [
                        static::CURRENT_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 100],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 200],
                            ],
                        static::OTHER_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 300],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 400],
                            ],
                    ]
                )
        );
        $productOptionGroupTransfer->addValue(
            (new StorageProductOptionValueTransfer())
                ->setPrices(
                    [
                        static::CURRENT_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 500],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 600],
                            ],
                        static::OTHER_CURRENCY_CODE =>
                            [
                                static::CURRENT_PRICE_MODE => [ProductOptionConstants::AMOUNT => 700],
                                static::OTHER_PRICE_MODE => [ProductOptionConstants::AMOUNT => 800],
                            ],
                    ]
                )
        );
        $expectedPrices = [100, 500];

        // Act
        $this->productOptionValuePriceReader->localizeGroupPrices($productOptionGroupTransfer);

        // Assert
        $actualPrices = $this->filterPrices($productOptionGroupTransfer);
        $this->assertEquals($expectedPrices, $actualPrices);
    }

    /**
     * @uses ProductOptionToCurrencyClientInterface::getCurrent()
     * @uses CurrencyTransfer::getCode()
     *
     * @return void
     */
    protected function mockCurrentCurrency()
    {
        $currentCurrencyMock = $this->getMockBuilder(CurrencyTransfer::class)->getMock();
        $currentCurrencyMock
            ->expects($this->any())
            ->method('getCode')
            ->willReturn(static::CURRENT_CURRENCY_CODE);

        $this->currencyClientMock
            ->expects($this->any())
            ->method('getCurrent')
            ->willReturn($currentCurrencyMock);
    }

    /**
     * @uses ProductOptionToPriceClientInterface::getCurrentPriceMode()
     *
     * @return void
     */
    protected function mockCurrentPriceMode()
    {
        $this->priceClientMock
            ->expects($this->any())
            ->method('getCurrentPriceMode')
            ->willReturn(static::CURRENT_PRICE_MODE);
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return array
     */
    protected function filterPrices(StorageProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        $filtered = array_map(
            function (StorageProductOptionValueTransfer $productOptionValueTransfer) {
                return $productOptionValueTransfer->getPrice();
            },
            (array)$productOptionGroupTransfer->getValues()
        );

        // reset keys
        return array_values($filtered);
    }
}
