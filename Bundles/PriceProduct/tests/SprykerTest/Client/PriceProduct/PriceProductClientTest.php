<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProduct;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\PriceProduct\PriceProductClient;
use Spryker\Client\Session\SessionClient;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProduct
 * @group PriceProductClientTest
 * Add your own group annotations below this line
 */
class PriceProductClientTest extends Unit
{
    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_TYPE
     *
     * @var string
     */
    protected const VOLUME_PRICE_TYPE = 'volume_prices';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY
     *
     * @var string
     */
    protected const VOLUME_PRICE_QUANTITY = 'quantity';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE
     *
     * @var string
     */
    protected const VOLUME_PRICE_NET_PRICE = 'net_price';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE
     *
     * @var string
     */
    protected const VOLUME_PRICE_GROSS_PRICE = 'gross_price';

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
     * @var string
     */
    protected const CURRENCY_CODE = 'EUR';

    /**
     * @var \SprykerTest\Client\PriceProduct\PriceProductTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $sessionContainer = new Session(new MockArraySessionStorage());
        $sessionClient = new SessionClient();
        $sessionClient->setContainer($sessionContainer);
    }

    /**
     * @return void
     */
    public function testResolveProductPriceTransferWillReturnPriceDataByPriceType(): void
    {
        // Arrange
        $priceProductTransferDefault = $this->createPriceProductTransfer(static::NET_PRICE, static::GROSS_PRICE, static::PRICE_TYPE_DEFAULT);
        $volumePriceDataDefaultDefaultJson = json_encode([
            static::VOLUME_PRICE_TYPE => $this->getVolumePriceDataDefault(),
        ]);
        $priceProductTransferDefault->getMoneyValue()
            ->setPriceData($volumePriceDataDefaultDefaultJson);

        $priceProductTransferOrigin = $this->createPriceProductTransfer(static::NET_PRICE, static::GROSS_PRICE, static::PRICE_TYPE_ORIGINAL);
        $volumePriceDataOriginJson = json_encode([
            static::VOLUME_PRICE_TYPE => $this->getVolumePriceDataOrigin(),
        ]);
        $priceProductTransferOrigin->getMoneyValue()
            ->setPriceData($volumePriceDataOriginJson);

        // Act
        $currentProductPriceTransfer = (new PriceProductClient())->resolveProductPriceTransfer([
            $priceProductTransferDefault,
            $priceProductTransferOrigin,
        ]);

        // Assert
        $this->makeAsserts($currentProductPriceTransfer, $volumePriceDataDefaultDefaultJson, $volumePriceDataOriginJson);
    }

    /**
     * @return void
     */
    public function testResolveProductPriceTransferByPriceProductFilterWillReturnPriceDataByPriceType(): void
    {
        // Arrange
        $priceProductTransferDefault = $this->createPriceProductTransfer(static::NET_PRICE, static::GROSS_PRICE, static::PRICE_TYPE_DEFAULT);
        $volumePriceDataDefaultDefaultJson = json_encode([
            static::VOLUME_PRICE_TYPE => $this->getVolumePriceDataDefault(),
        ]);
        $priceProductTransferDefault->getMoneyValue()
            ->setPriceData($volumePriceDataDefaultDefaultJson);

        $priceProductTransferOrigin = $this->createPriceProductTransfer(static::NET_PRICE, static::GROSS_PRICE, static::PRICE_TYPE_ORIGINAL);
        $volumePriceDataOriginJson = json_encode([
            static::VOLUME_PRICE_TYPE => $this->getVolumePriceDataOrigin(),
        ]);
        $priceProductTransferOrigin->getMoneyValue()
            ->setPriceData($volumePriceDataOriginJson);

        $priceProductFilterTransfer = new PriceProductFilterTransfer();

        // Act
        $currentProductPriceTransfer = (new PriceProductClient())->resolveProductPriceTransferByPriceProductFilter([
            $priceProductTransferDefault,
            $priceProductTransferOrigin,
        ], $priceProductFilterTransfer);

        // Assert
        $this->makeAsserts($currentProductPriceTransfer, $volumePriceDataDefaultDefaultJson, $volumePriceDataOriginJson);
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param string $volumePriceDataDefaultDefaultJson
     * @param string $volumePriceDataOriginJson
     *
     * @return void
     */
    protected function makeAsserts(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        string $volumePriceDataDefaultDefaultJson,
        string $volumePriceDataOriginJson
    ): void {
        $this->assertCount(2, $currentProductPriceTransfer->getPriceDataByPriceType());
        $this->assertSame(static::GROSS_PRICE, $currentProductPriceTransfer->getPrice());

        $this->assertSame(
            $currentProductPriceTransfer->getPriceData(),
            $volumePriceDataDefaultDefaultJson,
        );

        $priceDataByPriceType = $currentProductPriceTransfer->getPriceDataByPriceType();
        $this->assertSame(
            $priceDataByPriceType[static::PRICE_TYPE_DEFAULT],
            $volumePriceDataDefaultDefaultJson,
        );

        $this->assertSame(
            $priceDataByPriceType[static::PRICE_TYPE_ORIGINAL],
            $volumePriceDataOriginJson,
        );
    }

    /**
     * @param int $netPrice
     * @param int $grossPrice
     * @param string $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(int $netPrice, int $grossPrice, string $priceType): PriceProductTransfer
    {
        $currencyTransfer = (new CurrencyTransfer())
            ->setCode(static::CURRENCY_CODE);

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->setNetAmount($netPrice)
            ->setGrossAmount($grossPrice)
            ->setCurrency($currencyTransfer);

        $priceProductTransfer = (new PriceProductTransfer())
            ->setPriceTypeName($priceType)
            ->setMoneyValue($moneyValueTransfer);

        return $priceProductTransfer;
    }

    /**
     * @return array<int>
     */
    protected function getVolumePriceDataDefault(): array
    {
        return [
            [
                static::VOLUME_PRICE_QUANTITY => 5,
                static::VOLUME_PRICE_NET_PRICE => 100,
                static::VOLUME_PRICE_GROSS_PRICE => 95,
            ],
        ];
    }

    /**
     * @return array<int>
     */
    protected function getVolumePriceDataOrigin(): array
    {
        return [
            [
                static::VOLUME_PRICE_QUANTITY => 5,
                static::VOLUME_PRICE_NET_PRICE => 110,
                static::VOLUME_PRICE_GROSS_PRICE => 105,
            ],
        ];
    }
}
