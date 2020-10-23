<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group ExtractProductPricesTest
 * Add your own group annotations below this line
 */
class ExtractProductPricesTest extends Unit
{
    protected const TEST_PRICE_DATA = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';
    protected const TEST_EMPTY_PRICE_DATA = '{}';
    protected const TEST_PRICE_PRODUCT_GROUP_KEY = 'test_group_key';

    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractProductConfigurationVolumePricesWillExtractVolumePriceSuccessfully(): void
    {
        //Arrange
        $priceProductTransfers = [
            (new PriceProductTransfer())->setGroupKey(static::TEST_PRICE_PRODUCT_GROUP_KEY)->setMoneyValue(
                (new MoneyValueTransfer())->setPriceData(static::TEST_PRICE_DATA)
            ),
        ];

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->extractProductConfigurationVolumePrices($priceProductTransfers);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        $priceProductTransfer = array_shift($priceProductTransfers);

        // Assert
        $this->assertSame(3, $priceProductTransfer->getVolumeQuantity());
        $this->assertSame(385, $priceProductTransfer->getMoneyValue()->getGrossAmount());
        $this->assertSame(350, $priceProductTransfer->getMoneyValue()->getNetAmount());
        $this->assertSame(
            sprintf('%s-%s', static::TEST_PRICE_PRODUCT_GROUP_KEY, 3),
            $priceProductTransfer->getGroupKey()
        );
    }

    /**
     * @return void
     */
    public function testExtractProductConfigurationVolumePricesWillExtractAllVolumePricesSuccessfully(): void
    {
        //Arrange
        $priceProductTransfers = [
            (new PriceProductTransfer())->setMoneyValue(
                (new MoneyValueTransfer())->setPriceData(static::TEST_PRICE_DATA)
            ),
        ];

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->extractProductConfigurationVolumePrices($priceProductTransfers);

        // Assert
        $this->assertCount(2, $priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testExtractProductConfigurationVolumePricesWithOutPricesWillReturnEmptyArray(): void
    {
        //Arrange
        $priceProductTransfers = [];

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->extractProductConfigurationVolumePrices($priceProductTransfers);

        // Assert
        $this->assertsame([], $priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testExtractProductConfigurationVolumePricesWithOutPriceDataWillReturnEmptyArray(): void
    {
        //Arrange
        $priceProductTransfers = [
            (new PriceProductTransfer())->setMoneyValue(
                (new MoneyValueTransfer())
            ),
        ];

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->extractProductConfigurationVolumePrices($priceProductTransfers);

        // Assert
        $this->assertsame([], $priceProductTransfers);
    }
}
