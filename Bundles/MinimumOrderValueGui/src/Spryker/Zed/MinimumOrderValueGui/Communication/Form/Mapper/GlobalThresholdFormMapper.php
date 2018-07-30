<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreWithCurrencyTransfer;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\GlobalThresholdDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;

class GlobalThresholdFormMapper
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
     * @param array $data
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function map(array $data, MinimumOrderValueTransfer $minimumOrderValueTransfer): MinimumOrderValueTransfer
    {
        list($storeName, $currencyCode) = explode(
            GlobalThresholdDataProvider::STORE_CURRENCY_DELIMITER,
            $data[GlobalThresholdType::FIELD_STORE_CURRENCY]
        );
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            if ($this->setStoreToMinimumOrderValueTransfer($minimumOrderValueTransfer, $storeWithCurrencyTransfer, $storeName, $currencyCode)) {
                break;
            }
        }

        return $minimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer $storeWithCurrencyTransfer
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return bool
     */
    protected function setStoreToMinimumOrderValueTransfer(
        MinimumOrderValueTransfer $minimumOrderValueTransfer,
        StoreWithCurrencyTransfer $storeWithCurrencyTransfer,
        $storeName,
        $currencyCode
    ): bool {
        $storeTransfer = $storeWithCurrencyTransfer->getStore();
        if ($storeName === $storeTransfer->getName()) {
            $minimumOrderValueTransfer->setStore($storeTransfer);

            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                if ($this->setCurrencyToMinimumOrderValueTransfer($minimumOrderValueTransfer, $currencyCode, $currencyTransfer)) {
                    break;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     * @param string $currencyCode
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return bool
     */
    protected function setCurrencyToMinimumOrderValueTransfer(
        MinimumOrderValueTransfer $minimumOrderValueTransfer,
        $currencyCode,
        CurrencyTransfer $currencyTransfer
    ): bool {
        if ($currencyCode === $currencyTransfer->getCode()) {
            $minimumOrderValueTransfer->setCurrency($currencyTransfer);

            return true;
        }

        return false;
    }
}
