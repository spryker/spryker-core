<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\RestProductConfigurationPriceAttributesBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\RestCurrencyTransfer;
use Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin\ProductConfigurationsRestApi\ProductConfigurationVolumePriceRestProductConfigurationPriceMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsPriceProductVolumesRestApi
 * @group Plugin
 * @group ProductConfigurationVolumePriceRestProductConfigurationPriceMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationVolumePriceRestProductConfigurationPriceMapperPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PRICE_TYPE_NAME = 'priceTypeName';

    /**
     * @var int
     */
    protected const NET_AMOUNT = 111;

    /**
     * @var int
     */
    protected const GROSS_AMOUNT = 222;

    /**
     * @var string
     */
    protected const CURRENCY_NAME = 'EUR';

    /**
     * @var string
     */
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

        $restProductConfigurationPriceAttributesTransfer = (new RestProductConfigurationPriceAttributesBuilder([
                RestProductConfigurationPriceAttributesTransfer::PRICE_TYPE_NAME => static::PRICE_TYPE_NAME,
                RestProductConfigurationPriceAttributesTransfer::NET_AMOUNT => static::NET_AMOUNT,
                RestProductConfigurationPriceAttributesTransfer::GROSS_AMOUNT => static::GROSS_AMOUNT,
                RestProductConfigurationPriceAttributesTransfer::CURRENCY => [RestCurrencyTransfer::NAME => static::CURRENCY_NAME],
            ]))->build();

        $productConfigurationVolumePriceRestProductConfigurationPriceMapperPlugin = new ProductConfigurationVolumePriceRestProductConfigurationPriceMapperPlugin();

        // Act
        /** @var array<\Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer> $restProductConfigurationPriceAttributesTransfers */
        $restProductConfigurationPriceAttributesTransfers = $productConfigurationVolumePriceRestProductConfigurationPriceMapperPlugin->map(
            $productConfigurationInstanceTransfer,
            [$restProductConfigurationPriceAttributesTransfer],
        );

        // Assert
        $this->assertCount(1, $restProductConfigurationPriceAttributesTransfers);

        $this->assertCount(2, $restProductConfigurationPriceAttributesTransfers[0]->getVolumePrices());
    }
}
