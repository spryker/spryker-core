<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class PriceProductScheduleMapper implements PriceProductScheduleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function mapPriceProductScheduleImportTransferToPriceProductScheduleTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = $this->mapPriceProductScheduleImportTransferToPriceProductTransfer(
            $priceProductScheduleImportTransfer,
            new PriceProductTransfer()
        );

        return $priceProductScheduleTransfer
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true)
            ->setPriceProduct($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductScheduleImportTransferToPriceProductTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        return $priceProductTransfer
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true)
            ->setMoneyValue(
                $this->mapMoneyValueTransferFromPriceProductScheduleImportTransfer(
                    $priceProductScheduleImportTransfer,
                    new MoneyValueTransfer()
                )
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapMoneyValueTransferFromPriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        $currencyTransfer = (new CurrencyTransfer())->setCode($priceProductScheduleImportTransfer->getCurrencyCode());
        $storeTransfer = (new StoreTransfer())->setName($priceProductScheduleImportTransfer->getStoreName());

        return $moneyValueTransfer
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer)
            ->setNetAmount($priceProductScheduleImportTransfer->getNetAmount())
            ->setGrossAmount($priceProductScheduleImportTransfer->getGrossAmount());
    }
}
