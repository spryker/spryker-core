<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Expander;

use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface;
use Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface;

class StoreExpander implements StoreExpanderInterface
{
    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface
     */
    protected CurrencyRepositoryInterface $currencyRepository;

    protected CurrencyToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface $currencyRepository
     * @param \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface $storeFacade
     */
    public function __construct(CurrencyRepositoryInterface $currencyRepository, CurrencyToStoreFacadeInterface $storeFacade)
    {
        $this->currencyRepository = $currencyRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfersWithCurrencies(array $storeTransfers): array
    {
        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            return $storeTransfers;
        }

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
