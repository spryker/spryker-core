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
use Spryker\Shared\PriceProductStorage\PriceProductStorageConfig;
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
        $priceProductTransfers = [];
        foreach ($priceProductStorageTransfer->getPrices() as $currencyCode => $prices) {
            $this->getPriceProductTransfersFromPriceData($priceProductTransfers, $prices, $currencyCode);
        }

        return array_values($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param array $prices
     * @param string $currencyCode
     *
     * @return void
     */
    protected function getPriceProductTransfersFromPriceData(
        array &$priceProductTransfers,
        array $prices,
        string $currencyCode
    ): void {
        $priceProductTransfer = null;

        foreach (PriceProductStorageConfig::PRICE_MODES as $priceMode) {
            if (!isset($prices[$priceMode])) {
                continue;
            }
            foreach ($prices[$priceMode] as $priceAttribute => $priceValue) {
                $priceProductTransfer = $this->findProductTransferInCollection($currencyCode, $priceAttribute, $priceProductTransfers);

                if ($priceMode === PriceProductStorageConfig::PRICE_GROSS_MODE) {
                    $priceProductTransfer->getMoneyValue()->setGrossAmount($priceValue);
                    $priceProductTransfer->getMoneyValue()->setPriceData($prices[PriceProductStorageConfig::PRICE_DATA]);

                    continue;
                }

                $priceProductTransfer->getMoneyValue()->setNetAmount($priceValue);
                $priceProductTransfer->getMoneyValue()->setPriceData($prices[PriceProductStorageConfig::PRICE_DATA]);
            }
        }
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
}
