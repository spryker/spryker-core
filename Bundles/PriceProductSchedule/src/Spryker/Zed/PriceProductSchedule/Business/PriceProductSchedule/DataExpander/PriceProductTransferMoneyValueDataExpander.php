<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Currency\PriceProductScheduleCurrencyFinderInterface;
use Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException;
use Spryker\Zed\PriceProductSchedule\Business\Store\PriceProductScheduleStoreFinderInterface;

class PriceProductTransferMoneyValueDataExpander implements PriceProductTransferDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Store\PriceProductScheduleStoreFinderInterface
     */
    protected $priceProductScheduleStoreFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Currency\PriceProductScheduleCurrencyFinderInterface
     */
    protected $priceProductScheduleCurrencyFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\Store\PriceProductScheduleStoreFinderInterface $priceProductScheduleStoreFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\Currency\PriceProductScheduleCurrencyFinderInterface $priceProductScheduleCurrencyFinder
     */
    public function __construct(
        PriceProductScheduleStoreFinderInterface $priceProductScheduleStoreFinder,
        PriceProductScheduleCurrencyFinderInterface $priceProductScheduleCurrencyFinder
    ) {
        $this->priceProductScheduleStoreFinder = $priceProductScheduleStoreFinder;
        $this->priceProductScheduleCurrencyFinder = $priceProductScheduleCurrencyFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(
        PriceProductTransfer $priceProductTransfer,
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): PriceProductTransfer {
        $currencyTransfer = $this->priceProductScheduleCurrencyFinder->findCurrencyByIsoCode($priceProductScheduleImportTransfer->getCurrencyCode());

        if ($currencyTransfer === null) {
            throw new PriceProductScheduleListImportException(
                sprintf(
                    'Currency was not found by provided iso code %s',
                    $priceProductScheduleImportTransfer->getCurrencyCode()
                )
            );
        }

        $storeTransfer = $this->priceProductScheduleStoreFinder->findStoreByName($priceProductScheduleImportTransfer->getStoreName());
        if ($storeTransfer === null) {
            throw new PriceProductScheduleListImportException(
                sprintf(
                    'Store was not found by provided name %s',
                    $priceProductScheduleImportTransfer->getStoreName()
                )
            );
        }

        $moneyValueTransfer =
            (new MoneyValueTransfer())
                ->fromArray($priceProductScheduleImportTransfer->toArray(), true)
                ->setCurrency($currencyTransfer)
                ->setFkCurrency($currencyTransfer->getIdCurrency())
                ->setFkStore($storeTransfer->getIdStore());

        return $priceProductTransfer->setMoneyValue($moneyValueTransfer);
    }
}
