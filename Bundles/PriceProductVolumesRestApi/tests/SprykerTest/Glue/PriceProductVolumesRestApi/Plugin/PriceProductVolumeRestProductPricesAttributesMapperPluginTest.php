<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PriceProductVolumesRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CurrentProductPriceBuilder;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;
use Spryker\Glue\PriceProductVolumesRestApi\Plugin\ProductPriceRestApi\PriceProductVolumeRestProductPricesAttributesMapperPlugin;
use Spryker\Shared\PriceProductVolume\PriceProductVolumeConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PriceProductVolumesRestApi
 * @group Plugin
 * @group PriceProductVolumeRestProductPricesAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class PriceProductVolumeRestProductPricesAttributesMapperPluginTest extends Unit
{
    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     *
     * @var string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var string
     */
    protected const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @return void
     */
    public function testMapWillMapVolumePricesToRestProductPriceAttributesTransfer(): void
    {
        // Arrange
        $currentProductPriceTransfer = (new CurrentProductPriceBuilder([
            CurrentProductPriceTransfer::PRICE_DATA => json_encode([
                PriceProductVolumeConfig::VOLUME_PRICE_TYPE => [
                    [
                        PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY => 5,
                        PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE => 666,
                        PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE => 777,
                    ],
                ],
            ]),
        ]))->build();
        $restProductPriceAttributesTransfer = new RestProductPriceAttributesTransfer();
        $priceProductVolumeRestProductPricesAttributesMapperPlugin = new PriceProductVolumeRestProductPricesAttributesMapperPlugin();

        // Act
        $restProductPriceAttributesTransfer = $priceProductVolumeRestProductPricesAttributesMapperPlugin->map(
            $currentProductPriceTransfer,
            $restProductPriceAttributesTransfer,
        );

        // Assert
        $this->assertCount(1, $restProductPriceAttributesTransfer->getVolumePrices());
        /** @var \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer */
        $restProductPriceVolumesAttributesTransfer = $restProductPriceAttributesTransfer->getVolumePrices()->offsetGet(0);
        $this->assertEquals(5, $restProductPriceVolumesAttributesTransfer->getQuantity());
        $this->assertEquals(666, $restProductPriceVolumesAttributesTransfer->getNetAmount());
        $this->assertEquals(777, $restProductPriceVolumesAttributesTransfer->getGrossAmount());
    }

    /**
     * @return void
     */
    public function testMapWillNotMapAnythingWhenVolumePricesAreAbsent(): void
    {
        // Arrange
        $currentProductPriceTransfer = (new CurrentProductPriceBuilder())->build();
        $currentProductPriceTransfer->setPriceData(null);
        $restProductPriceAttributesTransfer = new RestProductPriceAttributesTransfer();
        $priceProductVolumeRestProductPricesAttributesMapperPlugin = new PriceProductVolumeRestProductPricesAttributesMapperPlugin();

        // Act
        $returnedRestProductPriceAttributesTransfer = $priceProductVolumeRestProductPricesAttributesMapperPlugin->map(
            $currentProductPriceTransfer,
            $restProductPriceAttributesTransfer,
        );

        // Assert
        $this->assertCount(0, $restProductPriceAttributesTransfer->getVolumePrices());
        $this->assertEquals($restProductPriceAttributesTransfer, $returnedRestProductPriceAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testMapShouldMapDataToAssociatedPriceTypeIfPriceDataByPriceTypeNotEmpty(): void
    {
        // Arrange
        $priceDataForDefaultPriceType = [
            PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY => 5,
            PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE => 111,
            PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE => 222,
        ];

        $priceDataForOriginalPriceType = [
            PriceProductVolumeConfig::VOLUME_PRICE_QUANTITY => 8,
            PriceProductVolumeConfig::VOLUME_PRICE_NET_PRICE => 444,
            PriceProductVolumeConfig::VOLUME_PRICE_GROSS_PRICE => 555,
        ];

        $currentProductPriceTransfer = (new CurrentProductPriceBuilder())->build();
        $currentProductPriceTransfer->setPriceData(json_encode([
            PriceProductVolumeConfig::VOLUME_PRICE_TYPE => [$priceDataForDefaultPriceType],
        ]));

        $currentProductPriceTransfer->setPriceDataByPriceType([
            static::PRICE_TYPE_DEFAULT => json_encode([
                PriceProductVolumeConfig::VOLUME_PRICE_TYPE => [$priceDataForDefaultPriceType],
            ]),
            static::PRICE_TYPE_ORIGINAL => json_encode([
                PriceProductVolumeConfig::VOLUME_PRICE_TYPE => [$priceDataForOriginalPriceType],
            ]),
        ]);

        $restProductPriceAttributesTransferWithDefaultPriceType = new RestProductPriceAttributesTransfer();
        $restProductPriceAttributesTransferWithDefaultPriceType->setPriceTypeName(static::PRICE_TYPE_DEFAULT);

        $restProductPriceAttributesTransferWithOriginalPriceType = new RestProductPriceAttributesTransfer();
        $restProductPriceAttributesTransferWithOriginalPriceType->setPriceTypeName(static::PRICE_TYPE_ORIGINAL);

        $priceProductVolumeRestProductPricesAttributesMapperPlugin = new PriceProductVolumeRestProductPricesAttributesMapperPlugin();

        // Act
        $returnedRestProductPriceAttributesTransferWithDefaultPriceType = $priceProductVolumeRestProductPricesAttributesMapperPlugin->map(
            $currentProductPriceTransfer,
            $restProductPriceAttributesTransferWithDefaultPriceType,
        );

        $returnedRestProductPriceAttributesTransferWithOriginalPriceType = $priceProductVolumeRestProductPricesAttributesMapperPlugin->map(
            $currentProductPriceTransfer,
            $restProductPriceAttributesTransferWithOriginalPriceType,
        );

        // Assert
        $volumePriceDataForDefaultPriceType = $returnedRestProductPriceAttributesTransferWithDefaultPriceType->getVolumePrices()->offsetGet(0);
        $this->assertEquals(5, $volumePriceDataForDefaultPriceType->getQuantity());
        $this->assertEquals(111, $volumePriceDataForDefaultPriceType->getNetAmount());
        $this->assertEquals(222, $volumePriceDataForDefaultPriceType->getGrossAmount());

        $volumePriceDataForOriginalPriceType = $returnedRestProductPriceAttributesTransferWithOriginalPriceType->getVolumePrices()->offsetGet(0);
        $this->assertEquals(8, $volumePriceDataForOriginalPriceType->getQuantity());
        $this->assertEquals(444, $volumePriceDataForOriginalPriceType->getNetAmount());
        $this->assertEquals(555, $volumePriceDataForOriginalPriceType->getGrossAmount());
    }
}
