<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToPriceProductServiceInterface;
use Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface;
use Spryker\Shared\ProductConfiguration\ProductConfigurationConfig;

class ProductConfigurationInstancePriceMapper implements ProductConfigurationInstancePriceMapperInterface
{
    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     * @var string
     */
    protected const KEY_PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     * @var string
     */
    protected const KEY_PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA
     * @var string
     */
    protected const KEY_PRICE_DATA = 'priceData';
    /**
     * @var string
     */
    protected const KEY_PRICES = 'prices';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     * @var string
     */
    protected const DEFAULT_PRICE_TYPE_NAME = 'DEFAULT';

    /**
     * @var bool
     */
    protected const IS_PRICE_MERGEABLE = false;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToPriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @var \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationPriceExtractorPluginInterface[]
     */
    protected $productConfigurationPriceExtractorPlugins;

    /**
     * @param \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToPriceProductServiceInterface $priceProductService
     * @param \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface $productConfigurationService
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationPriceExtractorPluginInterface[] $productConfigurationPriceExtractorPlugins
     */
    public function __construct(
        ProductConfigurationToPriceProductServiceInterface $priceProductService,
        ProductConfigurationServiceInterface $productConfigurationService,
        array $productConfigurationPriceExtractorPlugins
    ) {
        $this->priceProductService = $priceProductService;
        $this->productConfigurationService = $productConfigurationService;
        $this->productConfigurationPriceExtractorPlugins = $productConfigurationPriceExtractorPlugins;
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
        $priceProductTransfers = [];
        $configuratorResponsePrisesData = $configuratorResponseData[static::KEY_PRICES] ?? [];
        foreach ($configuratorResponsePrisesData as $currencyCode => $priceData) {
            $priceProductTransfer = $this->mapPriceDataToPriceProductTransfer(
                $currencyCode,
                $priceData
            );

            $priceProductTransfer->setGroupKey($this->priceProductService->buildPriceProductGroupKey($priceProductTransfer));
            $priceProductTransfers[] = $priceProductTransfer;
        }

        $priceProductTransfers = $this->executeProductConfigurationPriceExtractorPlugins($priceProductTransfers);
        $productConfigurationInstanceTransfer->setPrices(new ArrayObject($priceProductTransfers));

        $this->fillUpPriceDimensionWithProductConfigurationInstanceHash(
            $productConfigurationInstanceTransfer->getPrices(),
            $this->productConfigurationService->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer)
        );

        return $productConfigurationInstanceTransfer;
    }

    /**
     * @param string $currencyCode
     * @param array $priceData
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceDataToPriceProductTransfer(
        string $currencyCode,
        array $priceData
    ): PriceProductTransfer {
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType(ProductConfigurationConfig::PRICE_DIMENSION_PRODUCT_CONFIGURATION);

        $moneyValue = (new MoneyValueTransfer())
            ->setNetAmount($priceData[static::KEY_PRICE_MODE_NET][static::DEFAULT_PRICE_TYPE_NAME] ?? null)
            ->setGrossAmount($priceData[static::KEY_PRICE_MODE_GROSS][static::DEFAULT_PRICE_TYPE_NAME] ?? null)
            ->setPriceData($priceData[static::KEY_PRICE_DATA] ?? null)
            ->setCurrency(
                (new CurrencyTransfer())->setCode($currencyCode)
            );

        return (new PriceProductTransfer())
            ->setPriceTypeName(static::DEFAULT_PRICE_TYPE_NAME)
            ->setIsMergeable(static::IS_PRICE_MERGEABLE)
            ->setPriceDimension($priceProductDimensionTransfer)
            ->setMoneyValue($moneyValue);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function executeProductConfigurationPriceExtractorPlugins(array $priceProductTransfers): array
    {
        $extractedPriceProductTransfers = [];

        foreach ($this->productConfigurationPriceExtractorPlugins as $productConfigurationPriceExtractorPlugin) {
            $extractedPriceProductTransfers[] = $productConfigurationPriceExtractorPlugin->extractProductPrices(
                $priceProductTransfers
            );
        }

        return array_merge($priceProductTransfers, ...$extractedPriceProductTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param string $productConfigurationInstanceHash
     *
     * @return void
     */
    protected function fillUpPriceDimensionWithProductConfigurationInstanceHash(
        ArrayObject $priceProductTransfers,
        string $productConfigurationInstanceHash
    ): void {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductTransfer->getPriceDimensionOrFail()->setProductConfigurationInstanceHash($productConfigurationInstanceHash);
        }
    }
}
