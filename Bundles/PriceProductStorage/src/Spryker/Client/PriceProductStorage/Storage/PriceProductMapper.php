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
     * @param \Generated\Shared\Transfer\PriceProductStorageTransfer $priceProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductStorageTransferToPriceProductTransfers(PriceProductStorageTransfer $priceProductStorageTransfer): array
    {
        $extractedPrices = [];
        $priceProductTransfers = [];

        foreach ($priceProductStorageTransfer->getPrices() as $currencyCode => $prices) {
            foreach ($prices as $priceMode => $priceTypes) {
                if ($priceMode === PriceProductConstants::PRICE_DATA) {
                    $extractedPrices = array_merge($extractedPrices, $this->extractPricesFromPriceData($currencyCode, $prices[PriceProductConstants::PRICE_DATA]));

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
        }

        return array_merge(array_values($priceProductTransfers), $extractedPrices);
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

    protected function extractPricesFromPriceData(string $currencyCode, ?string $priceData)
    {
        if ($priceData == null) {
            return [];
        }

        //todo: move to plugin stack
        $result = [];
        $priceData = json_decode($priceData, true);
        foreach ($priceData['volume_prices'] as $volumePrice) {
            $volumePriceTransfer = new PriceProductTransfer();
            $volumePriceTransfer->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT)
            );
            $volumePriceTransfer->setMoneyValue((new MoneyValueTransfer())
                ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
                ->setGrossAmount($volumePrice['gross_price'])
                ->setNetAmount($volumePrice['net_price'])
            );
            $volumePriceTransfer->setQuantityToApply($volumePrice['quantity']);
            $volumePriceTransfer->setPriceTypeName('volume_prices');

            $result[] = $volumePriceTransfer;
        }

        return $result;
    }
}
