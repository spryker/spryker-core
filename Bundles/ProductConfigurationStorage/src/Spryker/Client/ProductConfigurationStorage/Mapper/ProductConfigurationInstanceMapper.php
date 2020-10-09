<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Spryker\Shared\ProductConfigurationStorage\ProductConfigurationStorageConfig;

class ProductConfigurationInstanceMapper implements ProductConfigurationInstanceMapperInterface
{
    protected const DEFAULT_PRICE_TYPE_NAME = 'DEFAULT';
    protected const PRICE_GROSS_MODE_KEY = 'GROSSMODE';
    protected const PRICE_NET_MODE_KEY = 'NETMODE';
    protected const PRICE_DATA_KEY = 'priceData';
    protected const IS_PRICE_MERGEABLE = false;
    protected const PRODUCT_CONFIGURATION_INSTANCE_RESPONSE_KEY = 'productConfigurationInstance';
    protected const PRICES_RESPONSE_KEY = 'prices';

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapProductConfigurationStorageTransferToProductConfigurationInstanceTransfer(
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        $productConfigurationInstanceTransfer->fromArray($productConfigurationStorageTransfer->toArray(), true);

        $productConfigurationInstanceTransfer->setConfiguration(
            $productConfigurationStorageTransfer->getDefaultConfiguration()
        );
        $productConfigurationInstanceTransfer->setDisplayData($productConfigurationStorageTransfer->getDefaultDisplayData());

        return $productConfigurationInstanceTransfer;
    }

    /**
     * @param array $configuratorResponseData
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapConfiguratorResponseDataPricesToProductConfigurationInstancePrices(
        array $configuratorResponseData,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        $priceProductTransfers = new ArrayObject();
        $configuratorResponsePrisesData
            = $configuratorResponseData[static::PRODUCT_CONFIGURATION_INSTANCE_RESPONSE_KEY][static::PRICES_RESPONSE_KEY] ?? [];

        foreach ($configuratorResponsePrisesData as $currencyName => $priceData) {
            $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
                ->setType(ProductConfigurationStorageConfig::PRICE_DIMENSION_PRODUCT_CONFIGURATION)
                ->setProductConfigurationConfiguratorKey($productConfigurationInstanceTransfer->getConfiguratorKey());

            $moneyValue = (new MoneyValueTransfer())
                ->setNetAmount($priceData[static::PRICE_NET_MODE_KEY][static::DEFAULT_PRICE_TYPE_NAME] ?? null)
                ->setGrossAmount($priceData[static::PRICE_GROSS_MODE_KEY][static::DEFAULT_PRICE_TYPE_NAME] ?? null)
                ->setPriceData($priceData[static::PRICE_DATA_KEY] ?? null)
                ->setCurrency(
                    (new CurrencyTransfer())->setName($currencyName)
                );

            $priceProductTransfers->append((new PriceProductTransfer())
                ->setPriceTypeName(static::DEFAULT_PRICE_TYPE_NAME)
                ->setIsMergeable(static::IS_PRICE_MERGEABLE)
                ->setPriceDimension($priceProductDimensionTransfer)
                ->setMoneyValue($moneyValue));
        }

        $productConfigurationInstanceTransfer->setPrices($priceProductTransfers);

        return $productConfigurationInstanceTransfer;
    }
}
