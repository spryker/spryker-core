<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Builder;

use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;

class StoreWithCurrenciesCollectionBuilder implements StoreWithCurrenciesCollectionBuilderInterface
{
    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Currency\Business\CurrencyFacadeInterface $currencyFacade
     */
    public function __construct(CurrencyFacadeInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param int $idStore
     *
     * @return array
     */
    public function buildStoreWithCurrenciesCollectionByStoreId(int $idStore): array
    {
        $storeWithCurrencyCollection = $this->currencyFacade->getAllStoresWithCurrencies();

        $currencies = [];
        $store = [];

        foreach ($storeWithCurrencyCollection as $storeWithCurrencyTransfer) {
            $storeWithCurrencyTransfer->requireStore();
            if ($storeWithCurrencyTransfer->getStore()->getIdStore() !== $idStore) {
                continue;
            }
            $store = $storeWithCurrencyTransfer->getStore()->toArray();

            $currencyCollection = $storeWithCurrencyTransfer->getCurrencies();
            foreach ($currencyCollection as $currencyTransfer) {
                $currencies[] = $currencyTransfer->toArray();
            }
        }

        return [
            'currencies' => $currencies,
            'store' => $store,
        ];
    }
}
