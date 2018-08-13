<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreCurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\StoreWithCurrencyTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConfig;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface;

class StoreCurrencyFinder implements StoreCurrencyFinderInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade,
        MinimumOrderValueGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $storeCurrency
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    public function getStoreCurrencyByString(string $storeCurrency): StoreCurrencyTransfer
    {
        list($storeName, $currencyCode) = explode(
            MinimumOrderValueGuiConfig::STORE_CURRENCY_DELIMITER,
            $storeCurrency
        );
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        return $this->hydrateStoreCurrency($storeWithCurrencyTransfers, $storeName, $currencyCode);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    public function getCurrentStoreCurrency(): StoreCurrencyTransfer
    {
        return (new StoreCurrencyTransfer())
            ->setStore($this->getCurrentStore())
            ->setCurrency($this->getCurrentCurrency());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer[] $storeWithCurrencyTransfers
     * @param string $store
     * @param string $currency
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    protected function hydrateStoreCurrency(
        array $storeWithCurrencyTransfers,
        string $store,
        string $currency
    ): StoreCurrencyTransfer {
        $storeCurrencyTransfer = new StoreCurrencyTransfer();

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            if ($store === $storeWithCurrencyTransfer->getStore()->getName()) {
                $storeCurrencyTransfer->setStore($storeWithCurrencyTransfer->getStore());
                $storeCurrencyTransfer = $this->hydrateCurrency($storeCurrencyTransfer, $storeWithCurrencyTransfer, $currency);

                break;
            }
        }

        return $storeCurrencyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCurrencyTransfer $storeCurrencyTransfer
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer $storeWithCurrencyTransfer
     * @param string $currency
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    protected function hydrateCurrency(
        StoreCurrencyTransfer $storeCurrencyTransfer,
        StoreWithCurrencyTransfer $storeWithCurrencyTransfer,
        string $currency
    ): StoreCurrencyTransfer {
        foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
            if ($currency === $currencyTransfer->getCode()) {
                $storeCurrencyTransfer->setCurrency($currencyTransfer);

                return $storeCurrencyTransfer;
            }
        }

        return $storeCurrencyTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrentCurrency(): CurrencyTransfer
    {
        return $this->currencyFacade
            ->getCurrent();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore(): StoreTransfer
    {
        return $this->storeFacade
            ->getCurrentStore();
    }
}
