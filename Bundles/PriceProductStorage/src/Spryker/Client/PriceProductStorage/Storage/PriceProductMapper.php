<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Shared\PriceProduct\PriceProductConstants;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;

class PriceProductMapper implements PriceProductMapperInterface
{
    protected const INDEX_SEPARATOR = '-';

    /**
     * @var \Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductMapperPricesExtractorPluginInterface[]
     */
    protected $priceProductExtractorPlugins;

    /**
     * @param \Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductMapperPricesExtractorPluginInterface[] $priceProductExtractorPlugins
     */
    public function __construct(array $priceProductExtractorPlugins)
    {
        $this->priceProductExtractorPlugins = $priceProductExtractorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductStorageTransfer $priceProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductStorageTransferToPriceProductTransfers(PriceProductStorageTransfer $priceProductStorageTransfer): array
    {
        $extractedPrices = [];
        $priceProductTransfers = [];

        foreach ($priceProductStorageTransfer->getPrices() as $currencyCode => $prices) {
            $extractedPrices = array_merge(
                $extractedPrices,
                $this->getPriceProductTransfersFromPriceData($priceProductTransfers, $prices, $currencyCode)
            );
        }

        return array_merge(array_values($priceProductTransfers), $extractedPrices);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param array $prices
     * @param string $currencyCode
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfersFromPriceData(
        array &$priceProductTransfers,
        array $prices,
        string $currencyCode
    ): array {
        $priceProductTransfer = null;

        foreach ($prices as $priceMode => $priceTypes) {
            if ($priceMode === PriceProductConstants::PRICE_DATA) {
                continue;
            }

            foreach ($priceTypes as $priceAttribute => $priceValue) {
                $priceProductTransfer = $this->findProductTransferInCollection($currencyCode, $priceAttribute, $priceProductTransfers);

                if ($priceMode === PriceProductConfig::PRICE_GROSS_MODE) {
                    $priceProductTransfer->getMoneyValue()->setGrossAmount($priceValue);
                    $priceProductTransfer->getMoneyValue()->setPriceData($prices[PriceProductConstants::PRICE_DATA]);

                    continue;
                }

                $priceProductTransfer->getMoneyValue()->setNetAmount($priceValue);
                $priceProductTransfer->getMoneyValue()->setPriceData($prices[PriceProductConstants::PRICE_DATA]);
            }
        }

        return $this->applyPriceProductExtractorPlugins($priceProductTransfer);
    }

    /**
     * @param string $currencyCode
     * @param string $priceType
     * @param array $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function findProductTransferInCollection(string $currencyCode, string $priceType, array &$priceProductTransfers): PriceProductTransfer
    {
        $index = implode(static::INDEX_SEPARATOR, [
            $currencyCode,
            $priceType,
        ]);

        if (!isset($priceProductTransfers[$index])) {
            $priceProductTransfers[$index] = (new PriceProductTransfer())
                ->setPriceDimension(
                    (new PriceProductDimensionTransfer())
                        ->setType(PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT)
                )
                ->setMoneyValue(
                    (new MoneyValueTransfer())
                        ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
                )
                ->setPriceTypeName($priceType);
        }

        return $priceProductTransfers[$index];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer|null $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function applyPriceProductExtractorPlugins(?PriceProductTransfer $priceProductTransfer = null): array
    {
        if (!$priceProductTransfer) {
            return [];
        }

        $priceProducts = [];

        foreach ($this->priceProductExtractorPlugins as $extractorPlugin) {
            $priceProducts = array_merge(
                $priceProducts,
                $extractorPlugin->extractProductPrices($priceProductTransfer)
            );
        }

        return $priceProducts;
    }
}
