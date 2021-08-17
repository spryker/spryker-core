<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfiguration\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfiguration
 * @group Client
 * @group MapProductConfiguratorCheckSumResponseTest
 * Add your own group annotations below this line
 */
class MapProductConfiguratorCheckSumResponseTest extends Unit
{
    /**
     * @uses ProductConfigurationConfig::PRICE_DIMENSION_PRODUCT_CONFIGURATION
     */
    protected const PRICE_DIMENSION_PRODUCT_CONFIGURATION = 'PRODUCT_CONFIGURATION';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    protected const KEY_PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    protected const KEY_PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses ProductConfigurationInstancePriceMapper::KEY_PRICE_DATA
     */
    protected const KEY_PRICE_DATA = 'priceData';

    /**
     * @uses ProductConfigurationInstancePriceMapper::KEY_PRODUCT_CONFIGURATION_INSTANCE
     */
    protected const KEY_PRODUCT_CONFIGURATION_INSTANCE = 'productConfigurationInstance';

    /**
     * @uses ProductConfigurationInstancePriceMapper::KEY_PRICES
     */
    protected const KEY_PRICES = 'prices';

    /**
     * @uses ProductConfigurationInstancePriceMapper::DEFAULT_PRICE_TYPE_NAME
     */
    protected const DEFAULT_PRICE_TYPE_NAME = 'DEFAULT';

    /**
     * @uses ProductConfigurationInstancePriceMapper::IS_PRICE_MERGEABLE
     */
    protected const IS_PRICE_MERGEABLE = false;

    /**
     * @var \SprykerTest\Client\ProductConfiguration\ProductConfigurationClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductConfigurationInstanceIsMissing(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = new ProductConfiguratorResponseTransfer();

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->mapProductConfiguratorCheckSumResponse([], $productConfiguratorResponseTransfer);
    }

    /**
     * @return void
     */
    public function testMapsPriceData(): void
    {
        // Arrange
        $currencyCode = 'EUR';
        $priceData = ['some' => 'data'];
        $netPrice = 111;
        $grossPrice = 222;

        $configuratorResponseData = [
            static::KEY_PRODUCT_CONFIGURATION_INSTANCE => [
                static::KEY_PRICES => [
                    $currencyCode => [
                        static::KEY_PRICE_MODE_NET => [
                            static::DEFAULT_PRICE_TYPE_NAME => $netPrice,
                        ],
                        static::KEY_PRICE_MODE_GROSS => [
                            static::DEFAULT_PRICE_TYPE_NAME => $grossPrice,
                        ],
                        static::KEY_PRICE_DATA => $priceData,
                    ],
                ],
            ],
        ];

        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())->setProductConfigurationInstance(
            new ProductConfigurationInstanceTransfer()
        );

        // Act
        $productConfiguratorResponseTransfer = $this->tester->getClient()->mapProductConfiguratorCheckSumResponse(
            $configuratorResponseData,
            $productConfiguratorResponseTransfer
        );

        /**
         * @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
         */
        $priceProductTransfer = $productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail()
            ->getPrices()
            ->getIterator()
            ->current();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

        $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimension();

        // Assert
        $this->assertNotNull($priceProductTransfer->getGroupKey());

        $this->assertSame(
            static::PRICE_DIMENSION_PRODUCT_CONFIGURATION,
            $priceProductDimensionTransfer->getType(),
            'Expects product dimension type to be PRODUCT_CONFIGURATION.'
        );
        $this->assertNotNull(
            $priceProductDimensionTransfer->getProductConfigurationInstanceHash(),
            'Expects product configuration instance hash to be filled.'
        );

        $this->assertSame(
            static::DEFAULT_PRICE_TYPE_NAME,
            $priceProductTransfer->getPriceTypeName(),
            'Expects price type to be DEFAULT.'
        );
        $this->assertSame(
            static::IS_PRICE_MERGEABLE,
            $priceProductTransfer->getIsMergeable(),
            'Expects price to be not mergeable.'
        );

        $this->assertSame(
            $netPrice,
            $moneyValueTransfer->getNetAmount(),
            'Expects net amount to be equal to 111.'
        );
        $this->assertSame(
            $grossPrice,
            $moneyValueTransfer->getGrossAmount(),
            'Expects gross amount to be equal to 222.'
        );
        $this->assertSame(
            $currencyCode,
            $moneyValueTransfer->getCurrency()->getCode(),
            'Expects to get EUR currency code.'
        );
        $this->assertSame(
            $priceData,
            $moneyValueTransfer->getPriceData(),
            'Expects to get same price data as provided at request.'
        );
    }

    /**
     * @return void
     */
    public function testIgnoresEmptyPriceData(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())->setProductConfigurationInstance(
            new ProductConfigurationInstanceTransfer()
        );

        // Act
        $productConfiguratorResponseTransfer = $this->tester->getClient()->mapProductConfiguratorCheckSumResponse(
            [],
            $productConfiguratorResponseTransfer
        );

        // Assert
        $this->assertEmpty($productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail()->getPrices());
    }
}
