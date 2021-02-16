<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\RestCartItemProductConfigurationInstanceAttributesBuilder;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer;
use Spryker\Glue\ProductConfigurationsRestApi\Plugin\ProductConfigurationsRestApi\RestProductConfigurationPriceRestCartItemProductConfigurationMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsRestApi
 * @group Plugin
 * @group RestProductConfigurationPriceRestCartItemProductConfigurationMapperPluginTest
 * Add your own group annotations below this line
 */
class RestProductConfigurationPriceRestCartItemProductConfigurationMapperPluginTest extends Unit
{
    protected const PRICE_TYPE_NAME = 'priceTypeName';
    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":3,"net_price":350,"gross_price":385},{"quantity":8,"net_price":340,"gross_price":375}]}';

    /**
     * @return void
     */
    public function testMapWillMapProductConfigurationInstanceVVolumePricesToRestCartItemProductConfigurationInstanceAttributesTransfer(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())
            ->withPrice([
                PriceProductTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME,
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::PRICE_DATA => static::PRICE_DATA_VOLUME,
                ],
            ])
            ->build();
        $restCartItemProductConfigurationInstanceAttributesTransfer = (new RestCartItemProductConfigurationInstanceAttributesBuilder())
            ->withPrice([RestProductConfigurationPriceAttributesTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME])
            ->build();

        $restProductConfigurationPriceRestCartItemProductConfigurationMapperPlugin = new RestProductConfigurationPriceRestCartItemProductConfigurationMapperPlugin();

        // Act
        $restCartItemProductConfigurationInstanceAttributesTransfer = $restProductConfigurationPriceRestCartItemProductConfigurationMapperPlugin->map(
            $productConfigurationInstanceTransfer,
            $restCartItemProductConfigurationInstanceAttributesTransfer
        );

        // Assert
        $this->assertCount(1, $restCartItemProductConfigurationInstanceAttributesTransfer->getPrices());
        /** @var \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer */
        $restProductConfigurationPriceAttributesTransfer = $restCartItemProductConfigurationInstanceAttributesTransfer->getPrices()->offsetGet(0);
        $this->assertCount(2, $restProductConfigurationPriceAttributesTransfer->getVolumePrices());
    }
}
