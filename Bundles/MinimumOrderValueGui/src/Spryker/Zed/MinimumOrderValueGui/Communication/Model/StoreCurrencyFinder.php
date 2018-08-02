<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Model;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreCurrencyTransfer;
use Generated\Shared\Transfer\StoreWithCurrencyTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConstants;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;

class StoreCurrencyFinder
{
    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param string $storeCurrency
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    public function findStoreCurrencyByString(string $storeCurrency): StoreCurrencyTransfer
    {
        list($storeName, $currencyCode) = explode(
            MinimumOrderValueGuiConstants::STORE_CURRENCY_DELIMITER,
            $storeCurrency
        );
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();
        $storeCurrencyTransfer = new StoreCurrencyTransfer();

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            if ($this->isSetStoreToStoreCurrencyTransfer($storeCurrencyTransfer, $storeWithCurrencyTransfer, $storeName, $currencyCode)) {
                break;
            }
        }

        return $storeCurrencyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCurrencyTransfer $storeCurrencyTransfer
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer $storeWithCurrencyTransfer
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return bool
     */
    protected function isSetStoreToStoreCurrencyTransfer(
        StoreCurrencyTransfer $storeCurrencyTransfer,
        StoreWithCurrencyTransfer $storeWithCurrencyTransfer,
        $storeName,
        $currencyCode
    ): bool {
        $storeTransfer = $storeWithCurrencyTransfer->getStore();
        if ($storeName === $storeTransfer->getName()) {
            $storeCurrencyTransfer->setStore($storeTransfer);

            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                if ($this->isSetCurrencyToStoreCurrencyTransfer($storeCurrencyTransfer, $currencyCode, $currencyTransfer)) {
                    break;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCurrencyTransfer $storeCurrencyTransfer
     * @param string $currencyCode
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return bool
     */
    protected function isSetCurrencyToStoreCurrencyTransfer(
        StoreCurrencyTransfer $storeCurrencyTransfer,
        $currencyCode,
        CurrencyTransfer $currencyTransfer
    ): bool {
        if ($currencyCode === $currencyTransfer->getCode()) {
            $storeCurrencyTransfer->setCurrency($currencyTransfer);

            return true;
        }

        return false;
    }
}
