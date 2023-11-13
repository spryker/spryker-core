<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Communication\Plugin\ProductOfferStorage;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductOfferBuilder;
use Generated\Shared\DataBuilder\ProductOfferStorageBuilder;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\ProductOfferStorage\MerchantProductOfferStorageMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferStorage
 * @group Communication
 * @group Plugin
 * @group ProductOfferStorage
 * @group MerchantProductOfferStorageMapperPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferStorageMapperPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_ID_MERCHANT = 1;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_SKU = 'test-merchant-sku';

    /**
     * @dataProvider mapShouldCorrectlyMapTransferPropertiesDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $expectedProductOfferStorageTransfer
     *
     * @return void
     */
    public function testMapShouldCorrectlyMapTransferProperties(
        ProductOfferTransfer $productOfferTransfer,
        ProductOfferStorageTransfer $expectedProductOfferStorageTransfer
    ): void {
        // Arrange
        $merchantProductOfferStorageMapperPlugin = new MerchantProductOfferStorageMapperPlugin();

        // Act
        $actualProductOfferStorageTransfer = $merchantProductOfferStorageMapperPlugin->map(
            $productOfferTransfer,
            new ProductOfferStorageTransfer(),
        );

        // Assert
        $this->assertSame($expectedProductOfferStorageTransfer->getIdMerchant(), $actualProductOfferStorageTransfer->getIdMerchant());
        $this->assertSame($expectedProductOfferStorageTransfer->getMerchantSku(), $actualProductOfferStorageTransfer->getMerchantSku());
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferTransfer|\Generated\Shared\Transfer\ProductOfferStorageTransfer>>
     */
    protected function mapShouldCorrectlyMapTransferPropertiesDataProvider(): array
    {
        return [
            'fkMerchant and merchantSku are set' => [
                (new ProductOfferBuilder([
                    ProductOfferTransfer::FK_MERCHANT => static::TEST_ID_MERCHANT,
                    ProductOfferTransfer::MERCHANT_SKU => static::TEST_MERCHANT_SKU,
                ]))->build(),
                (new ProductOfferStorageBuilder([
                    ProductOfferStorageTransfer::ID_MERCHANT => static::TEST_ID_MERCHANT,
                    ProductOfferStorageTransfer::MERCHANT_SKU => static::TEST_MERCHANT_SKU,
                ]))->build(),
            ],
            'fkMerchant is set and merchantSku is not set' => [
                (new ProductOfferBuilder([
                    ProductOfferTransfer::FK_MERCHANT => static::TEST_ID_MERCHANT,
                    ProductOfferTransfer::MERCHANT_SKU => null,
                ]))->build(),
                (new ProductOfferStorageBuilder([
                    ProductOfferStorageTransfer::ID_MERCHANT => static::TEST_ID_MERCHANT,
                    ProductOfferStorageTransfer::MERCHANT_SKU => null,
                ]))->build(),
            ],
            'merchantSku is set and fkMerchant is not set' => [
                (new ProductOfferBuilder([
                    ProductOfferTransfer::FK_MERCHANT => null,
                    ProductOfferTransfer::MERCHANT_SKU => static::TEST_MERCHANT_SKU,
                ]))->build(),
                (new ProductOfferStorageBuilder([
                    ProductOfferStorageTransfer::ID_MERCHANT => null,
                    ProductOfferStorageTransfer::MERCHANT_SKU => static::TEST_MERCHANT_SKU,
                ]))->build(),
            ],
            'fkMerchant and merchantSku are not set' => [
                (new ProductOfferBuilder([
                    ProductOfferTransfer::FK_MERCHANT => null,
                    ProductOfferTransfer::MERCHANT_SKU => null,
                ]))->build(),
                (new ProductOfferStorageBuilder([
                    ProductOfferStorageTransfer::ID_MERCHANT => null,
                    ProductOfferStorageTransfer::MERCHANT_SKU => null,
                ]))->build(),
            ],
        ];
    }
}
