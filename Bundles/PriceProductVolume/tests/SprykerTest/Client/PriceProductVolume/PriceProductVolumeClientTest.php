<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductVolume;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\PriceProductVolume\PriceProductVolumeClient;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group PriceProductVolume
 * @group PriceProductVolumeClientTest
 * Add your own group annotations below this line
 */
class PriceProductVolumeClientTest extends Unit
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     *
     * @var string
     */
    protected const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     *
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
    protected const NET_PRICE = 22;

    /**
     * @var int
     */
    protected const GROSS_PRICE = 33;

    /**
     * @var \SprykerTest\Client\PriceProductVolume\PriceProductVolumeClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractProductPricesForProductAbstractExtractVolumePricesIfOnlyPriceDataIsPresent(): void
    {
        // Arrange
        $volumePriceData = $this->getVolumePriceData();

        $priceProductTransfer = $this->createPriceProductTransfer(static::NET_PRICE, static::GROSS_PRICE);
        $priceProductTransfer
            ->getMoneyValue()
            ->setPriceData(json_encode([
                PriceProductVolumeConfig::VOLUME_PRICE_TYPE => $volumePriceData,
            ]));

        // Act
        $volumePrices = (new PriceProductVolumeClient())->extractProductPricesForProductAbstract([$priceProductTransfer]);

        // Assert
        $this->assertCount(2, $volumePrices);
    }

    /**
     * @return void
     */
    public function testExtractProductPricesForProductConcreteExtractVolumePricesIfOnlyPriceDataIsPresent(): void
    {
        // Arrange
        $volumePriceData = $this->getVolumePriceData();

        $priceProductTransfer = $this->createPriceProductTransfer(static::NET_PRICE, static::GROSS_PRICE);
        $priceProductTransfer
            ->getMoneyValue()
            ->setPriceData(json_encode([
                PriceProductVolumeConfig::VOLUME_PRICE_TYPE => $volumePriceData,
            ]));

        // Act
        $volumePrices = (new PriceProductVolumeClient())->extractProductPricesForProductConcrete(0, [$priceProductTransfer]);

        // Assert
        $this->assertCount(3, $volumePrices);
    }

    /**
     * @return void
     */
    public function testExtractProductPricesForProductConcreteReturnsOnlyMainPriceIfPriceDataNotPresent(): void
    {
        // Arrange
        $priceProductTransfer = $this->createPriceProductTransfer(static::NET_PRICE, static::GROSS_PRICE);

        // Act
        $volumePrices = (new PriceProductVolumeClient())->extractProductPricesForProductConcrete(0, [$priceProductTransfer]);

        // Assert
        $this->assertCount(1, $volumePrices);
    }

    /**
     * @return void
     */
    public function testExtractProductPricesForProductConcreteReturnsExpectedVolumePricesUsingOnlyPriceDataByPriceType(): void
    {
        // Arrange
        $volumePriceData = $this->getVolumePriceData();

        $priceProductTransfer = $this->createPriceProductTransfer(static::NET_PRICE, static::GROSS_PRICE);
        $priceProductTransfer
            ->getMoneyValue()
            ->setPriceDataByPriceType([
                static::PRICE_TYPE_DEFAULT => json_encode([
                    PriceProductVolumeConfig::VOLUME_PRICE_TYPE => $volumePriceData,
                ]),
                static::PRICE_TYPE_ORIGINAL => json_encode([
                    PriceProductVolumeConfig::VOLUME_PRICE_TYPE => $volumePriceData,
                ]),
            ]);

        // Act
        $volumePrices = (new PriceProductVolumeClient())->extractProductPricesForProductConcrete(0, [$priceProductTransfer]);

        // Assert
        $this->assertCount(5, $volumePrices);
    }

    /**
     * @return void
     */
    public function testExtractProductPricesForProductConcreteReturnsExpectedVolumePricesUsingPriceDataByPriceTypeAndPriceData(): void
    {
        // Arrange
        $volumePriceData = $this->getVolumePriceData();

        $priceProductTransfer = $this->createPriceProductTransfer(333, 444);
        $priceProductTransfer
            ->getMoneyValue()
            ->setPriceDataByPriceType([
                static::PRICE_TYPE_DEFAULT => json_encode([
                    PriceProductVolumeConfig::VOLUME_PRICE_TYPE => $volumePriceData,
                ]),
                static::PRICE_TYPE_ORIGINAL => json_encode([
                    PriceProductVolumeConfig::VOLUME_PRICE_TYPE => $volumePriceData,
                ]),
            ])
            ->setPriceData(json_encode([
                PriceProductVolumeConfig::VOLUME_PRICE_TYPE => $volumePriceData,
            ]));

        // Act
        $volumePrices = (new PriceProductVolumeClient())->extractProductPricesForProductConcrete(0, [$priceProductTransfer]);

        // Assert
        $this->assertCount(5, $volumePrices);
    }

    /**
     * @param int $netPrice
     * @param int $grossPrice
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(int $netPrice, int $grossPrice): PriceProductTransfer
    {
        $priceDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(static::PRICE_DIMENSION_DEFAULT);

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setNetAmount($netPrice)
            ->setGrossAmount($grossPrice);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceTypeName(static::PRICE_TYPE_DEFAULT)
            ->setPriceDimension($priceDimensionTransfer)
            ->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @return array
     */
    protected function getVolumePriceData(): array
    {
        return [
            [
                PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY => 3,
                PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE => 111,
                PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE => 222,
            ],
            [
                PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY => 10,
                PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE => 55,
                PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE => 77,
            ],
        ];
    }
}
