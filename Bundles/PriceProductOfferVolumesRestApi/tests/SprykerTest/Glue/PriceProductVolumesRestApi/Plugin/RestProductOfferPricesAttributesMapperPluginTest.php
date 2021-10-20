<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PriceProductOfferVolumesRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CurrentProductPriceBuilder;
use Generated\Shared\DataBuilder\RestProductOfferPriceAttributesBuilder;
use Generated\Shared\DataBuilder\RestProductOfferPricesAttributesBuilder;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductOfferPriceAttributesTransfer;
use Spryker\Glue\PriceProductOfferVolumesRestApi\Plugin\RestProductOfferPricesAttributesMapperPlugin;
use Spryker\Glue\PriceProductOfferVolumesRestApi\Processor\Mapper\RestProductOfferPricesAttributesMapper;
use Spryker\Shared\PriceProductOfferVolume\PriceProductOfferVolumeConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PriceProductOfferVolumesRestApi
 * @group Plugin
 * @group RestProductOfferPricesAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class RestProductOfferPricesAttributesMapperPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testMapWillMapCurrentProductPriceTransferToRestProductOfferPricesAttributesTransfer(): void
    {
        // Arrange
        $currentProductPriceTransfer = (new CurrentProductPriceBuilder([
            CurrentProductPriceTransfer::PRICE_DATA => json_encode([
                PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE => [
                    [
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_QUANTITY_KEY => 5,
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_NET_PRICE_KEY => 666,
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_GROSS_PRICE_KEY => 777,
                    ],
                ],
            ]),
        ]))->build();

        $restProductOfferPricesAttributesTransfer = (new RestProductOfferPricesAttributesBuilder())->build();
        $restProductOfferPricesAttributesTransfer->addPrice(
            (new RestProductOfferPriceAttributesBuilder([
                RestProductOfferPriceAttributesTransfer::PRICE_TYPE_NAME => 'DEFAULT',
                RestProductOfferPriceAttributesTransfer::GROSS_AMOUNT => 12345,
            ]))->build(),
        );
        $restProductOfferPricesAttributesTransfer->addPrice(
            (new RestProductOfferPriceAttributesBuilder([
                RestProductOfferPriceAttributesTransfer::PRICE_TYPE_NAME => 'ORIGINAL',
                RestProductOfferPriceAttributesTransfer::GROSS_AMOUNT => 54321,
            ]))->build(),
        );

        $restProductOfferPricesAttributesMapperPlugin = new RestProductOfferPricesAttributesMapperPlugin();

        // Act
        $restProductOfferPricesAttributesTransfer = $restProductOfferPricesAttributesMapperPlugin->map(
            $currentProductPriceTransfer,
            $restProductOfferPricesAttributesTransfer,
        );

        // Assert
        $this->assertCount(2, $restProductOfferPricesAttributesTransfer->getPrices());

        /** @var \Generated\Shared\Transfer\RestProductOfferPriceAttributesTransfer $price1 */
        $price1 = $restProductOfferPricesAttributesTransfer->getPrices()->offsetGet(0);
        $this->assertCount(1, $price1->getVolumePrices());
        /** @var \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $volumePrice */
        $volumePrice1 = $price1->getVolumePrices()->offsetGet(0);
        $this->assertEquals(5, $volumePrice1->getQuantity());
        $this->assertEquals(666, $volumePrice1->getNetAmount());
        $this->assertEquals(777, $volumePrice1->getGrossAmount());

        /** @var \Generated\Shared\Transfer\RestProductOfferPriceAttributesTransfer $price2 */
        $price2 = $restProductOfferPricesAttributesTransfer->getPrices()->offsetGet(1);
        $this->assertCount(1, $price1->getVolumePrices());
        /** @var \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $volumePrice */
        $volumePrice2 = $price2->getVolumePrices()->offsetGet(0);
        $this->assertEquals(5, $volumePrice2->getQuantity());
        $this->assertEquals(666, $volumePrice2->getNetAmount());
        $this->assertEquals(777, $volumePrice2->getGrossAmount());
    }

    /**
     * @return void
     */
    public function testMapWillNotMapAnythingWhenPriceDataIsAbsent(): void
    {
        // Arrange
        $currentProductPriceTransfer = (new CurrentProductPriceBuilder())->build();
        $currentProductPriceTransfer->setPriceData(null);

        $restProductOfferPricesAttributesTransfer = (new RestProductOfferPricesAttributesBuilder())->build();
        $restProductOfferPricesAttributesTransfer->addPrice(
            (new RestProductOfferPriceAttributesBuilder([
                RestProductOfferPriceAttributesTransfer::PRICE_TYPE_NAME => 'DEFAULT',
                RestProductOfferPriceAttributesTransfer::GROSS_AMOUNT => 12345,
            ]))->build(),
        );

        $restProductOfferPricesAttributesMapperPlugin = new RestProductOfferPricesAttributesMapperPlugin();

        // Act
        $restProductOfferPricesAttributesTransfer = $restProductOfferPricesAttributesMapperPlugin->map(
            $currentProductPriceTransfer,
            $restProductOfferPricesAttributesTransfer,
        );

        // Assert
        $this->assertCount(1, $restProductOfferPricesAttributesTransfer->getPrices());

        /** @var \Generated\Shared\Transfer\RestProductOfferPriceAttributesTransfer $price */
        $price = $restProductOfferPricesAttributesTransfer->getPrices()->offsetGet(0);
        $this->assertCount(0, $price->getVolumePrices());
    }

    /**
     * @return void
     */
    public function testMapWillMapVolumePricesByPriceTypeWhenPriceDataByPriceTypeProvided(): void
    {
        // Arrange
        $currentProductPriceTransfer = (new CurrentProductPriceBuilder([
            CurrentProductPriceTransfer::PRICE_DATA => json_encode([
                PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE => [
                    [
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_QUANTITY_KEY => 5,
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_NET_PRICE_KEY => 666,
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_GROSS_PRICE_KEY => 777,
                    ],
                ],
            ]),
        ]))->build();

        $currentProductPriceTransfer->setPriceDataByPriceType([
            'DEFAULT' => json_encode([
                PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE => [
                    [
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_QUANTITY_KEY => 6,
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_NET_PRICE_KEY => 456,
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_GROSS_PRICE_KEY => 457,
                    ],
                ],
            ]),
            'ORIGINAL' => json_encode([
                PriceProductOfferVolumeConfig::VOLUME_PRICE_TYPE => [
                    [
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_QUANTITY_KEY => 6,
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_NET_PRICE_KEY => 678,
                        RestProductOfferPricesAttributesMapper::VOLUME_PRICE_GROSS_PRICE_KEY => 679,
                    ],
                ],
            ]),
        ]);

        $restProductOfferPricesAttributesTransfer = (new RestProductOfferPricesAttributesBuilder())->build();
        $restProductOfferPricesAttributesTransfer->addPrice(
            (new RestProductOfferPriceAttributesBuilder([
                RestProductOfferPriceAttributesTransfer::PRICE_TYPE_NAME => 'DEFAULT',
                RestProductOfferPriceAttributesTransfer::GROSS_AMOUNT => 12345,
            ]))->build(),
        );
        $restProductOfferPricesAttributesTransfer->addPrice(
            (new RestProductOfferPriceAttributesBuilder([
                RestProductOfferPriceAttributesTransfer::PRICE_TYPE_NAME => 'ORIGINAL',
                RestProductOfferPriceAttributesTransfer::GROSS_AMOUNT => 54321,
            ]))->build(),
        );

        $restProductOfferPricesAttributesMapperPlugin = new RestProductOfferPricesAttributesMapperPlugin();

        // Act
        $restProductOfferPricesAttributesTransfer = $restProductOfferPricesAttributesMapperPlugin->map(
            $currentProductPriceTransfer,
            $restProductOfferPricesAttributesTransfer,
        );

        // Assert
        $this->assertCount(2, $restProductOfferPricesAttributesTransfer->getPrices());

        /** @var \Generated\Shared\Transfer\RestProductOfferPriceAttributesTransfer $price1 */
        $price1 = $restProductOfferPricesAttributesTransfer->getPrices()->offsetGet(0);
        $this->assertCount(1, $price1->getVolumePrices());
        /** @var \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $volumePrice */
        $volumePrice1 = $price1->getVolumePrices()->offsetGet(0);
        $this->assertEquals(6, $volumePrice1->getQuantity());
        $this->assertEquals(456, $volumePrice1->getNetAmount());
        $this->assertEquals(457, $volumePrice1->getGrossAmount());

        /** @var \Generated\Shared\Transfer\RestProductOfferPriceAttributesTransfer $price2 */
        $price2 = $restProductOfferPricesAttributesTransfer->getPrices()->offsetGet(1);
        $this->assertCount(1, $price1->getVolumePrices());
        /** @var \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $volumePrice */
        $volumePrice2 = $price2->getVolumePrices()->offsetGet(0);
        $this->assertEquals(6, $volumePrice2->getQuantity());
        $this->assertEquals(678, $volumePrice2->getNetAmount());
        $this->assertEquals(679, $volumePrice2->getGrossAmount());
    }
}
