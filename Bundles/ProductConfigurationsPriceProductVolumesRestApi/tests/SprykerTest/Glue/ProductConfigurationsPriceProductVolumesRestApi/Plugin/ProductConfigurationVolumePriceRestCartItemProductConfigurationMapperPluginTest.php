<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\RestCartItemProductConfigurationInstanceAttributesBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\RestCurrencyTransfer;
use Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin\ProductConfigurationsRestApi\ProductConfigurationVolumePriceRestCartItemProductConfigurationMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsPriceProductVolumesRestApi
 * @group Plugin
 * @group ProductConfigurationVolumePriceRestCartItemProductConfigurationMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationVolumePriceRestCartItemProductConfigurationMapperPluginTest extends Unit
{
    protected const PRICE_TYPE_NAME = 'priceTypeName';
    protected const NET_AMOUNT = 111;
    protected const GROSS_AMOUNT = 222;
    protected const CURRENCY_NAME = 'EUR';
    protected const PRICE_DATA_VOLUME = '{"volume_prices":[{"quantity":5,"net_price":350,"gross_price":385},{"quantity":10,"net_price":340,"gross_price":375}]}';

    /**
     * @return void
     */
    public function testMapWillMapProductConfigurationInstanceVolumePricesToRestCartItemProductConfigurationInstanceAttributesTransfer(): void
    {
        // Arrange
        $moneyValueTransfer = (new MoneyValueBuilder([MoneyValueTransfer::PRICE_DATA => static::PRICE_DATA_VOLUME]))
            ->withCurrency([CurrencyTransfer::NAME => static::CURRENCY_NAME])
            ->build();

        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())
            ->withPrice([
                PriceProductTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME,
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::NET_AMOUNT => static::NET_AMOUNT,
                    MoneyValueTransfer::GROSS_AMOUNT => static::GROSS_AMOUNT,
                    MoneyValueTransfer::CURRENCY => [CurrencyTransfer::NAME => static::CURRENCY_NAME],
                ],
                PriceProductTransfer::VOLUME_QUANTITY => null,
            ])
            ->withAnotherPrice([
                PriceProductTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME,
                PriceProductTransfer::MONEY_VALUE => $moneyValueTransfer->toArray(),
                PriceProductTransfer::VOLUME_QUANTITY => 5,
            ])
            ->withAnotherPrice([
                PriceProductTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME,
                PriceProductTransfer::MONEY_VALUE => $moneyValueTransfer->toArray(),
                PriceProductTransfer::VOLUME_QUANTITY => 10,
            ])
            ->build();

        $restCartItemProductConfigurationInstanceAttributesTransfer = (new RestCartItemProductConfigurationInstanceAttributesBuilder())
            ->withPrice([
                RestProductConfigurationPriceAttributesTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME,
                RestProductConfigurationPriceAttributesTransfer::NET_AMOUNT => static::NET_AMOUNT,
                RestProductConfigurationPriceAttributesTransfer::GROSS_AMOUNT => static::GROSS_AMOUNT,
                RestProductConfigurationPriceAttributesTransfer::CURRENCY => [RestCurrencyTransfer::NAME => static::CURRENCY_NAME],
            ])
            ->build();

        $restProductConfigurationPriceRestCartItemProductConfigurationMapperPlugin = new ProductConfigurationVolumePriceRestCartItemProductConfigurationMapperPlugin();

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
