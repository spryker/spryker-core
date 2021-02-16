<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\RestCartItemProductConfigurationInstanceAttributesBuilder;
use Generated\Shared\DataBuilder\RestProductConfigurationPriceAttributesBuilder;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer;
use Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer;
use Spryker\Glue\ProductConfigurationsRestApi\Plugin\ProductConfigurationsRestApi\RestProductConfigurationPriceAttributesMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsRestApi
 * @group Plugin
 * @group RestProductConfigurationPriceAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class RestProductConfigurationPriceAttributesMapperPluginTest extends Unit
{
    protected const PRICE_TYPE_NAME = 'priceTypeName';

    /**
     * @return void
     */
    public function testMapWillMapRestCartItemProductConfigurationInstanceAttributesToProductConfigurationInstanceTransfer(): void
    {
        // Arrange
        $restProductConfigurationPriceAttributesTransfer = (new RestProductConfigurationPriceAttributesBuilder([
            RestProductConfigurationPriceAttributesTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME,
        ]))
            ->withVolumePrice()
            ->withAnotherVolumePrice()
            ->build();
        $restCartItemProductConfigurationInstanceAttributesTransfer = (new RestCartItemProductConfigurationInstanceAttributesBuilder([
            RestCartItemProductConfigurationInstanceAttributesTransfer::PRICES => [$restProductConfigurationPriceAttributesTransfer->toArray()],
        ]))->build();

        $priceProductTransfer = (new PriceProductBuilder([PriceProductTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME]))
            ->withMoneyValue()
            ->build();
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::PRICES => [$priceProductTransfer->toArray()],
        ]))->build();

        $restProductConfigurationPriceAttributesMapperPlugin = new RestProductConfigurationPriceAttributesMapperPlugin();

        // Act
        $productConfigurationInstanceTransfer = $restProductConfigurationPriceAttributesMapperPlugin->map(
            $restCartItemProductConfigurationInstanceAttributesTransfer,
            $productConfigurationInstanceTransfer
        );

        // Assert
        $this->assertCount(3, $productConfigurationInstanceTransfer->getPrices());
    }
}
