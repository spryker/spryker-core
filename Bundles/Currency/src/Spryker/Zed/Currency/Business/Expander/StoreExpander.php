<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Expander;

use Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface;

class StoreExpander implements StoreExpanderInterface
{
    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface
     */
    protected CurrencyRepositoryInterface $currencyRepository;

    /**
     * @param \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface $currencyRepository
     */
    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithCurrencies(array $storeTransfers): array
    {
        $storeIds = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStoreOrFail();
        }

        $currencyCodesGroupedByIdStore = $this->currencyRepository->getCurrencyCodesGroupedByIdStore($storeIds);
        $storeDefaultCurrencyCodes = $this->currencyRepository->getStoreDefaultCurrencyCodes($storeIds);

        foreach ($storeTransfers as $storeTransfer) {
            $availableCurrencyIsoCodes = $currencyCodesGroupedByIdStore[$storeTransfer->getIdStoreOrFail()] ?? [];
            $storeTransfer
                ->setAvailableCurrencyIsoCodes($availableCurrencyIsoCodes)
                ->setDefaultCurrencyIsoCode($storeDefaultCurrencyCodes[$storeTransfer->getIdStoreOrFail()] ?? null);
        }

        return $storeTransfers;
    }
}
