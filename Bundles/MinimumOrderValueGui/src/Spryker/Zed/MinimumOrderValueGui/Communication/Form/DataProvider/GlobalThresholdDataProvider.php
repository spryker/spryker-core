<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConstants;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;

class GlobalThresholdDataProvider implements FormDataProviderInterface
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
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            GlobalThresholdType::OPTION_STORES_ARRAY => $this->getStoreList(),
            GlobalThresholdType::OPTION_SOFT_TYPES_ARRAY => $this->getSoftTypesList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer[] $minimumOrderValueTransfers
     *
     * @return array
     */
    public function getData(array $minimumOrderValueTransfers): array
    {
        $data = [];
        foreach ($minimumOrderValueTransfers as $minimumOrderValueTransfer) {
            $data[GlobalThresholdType::FIELD_STORE_CURRENCY] = $this->getStoreCurrencyFieldValue(
                $minimumOrderValueTransfer->getStore(),
                $minimumOrderValueTransfer->getCurrency()
            );
            $thresholdStrategyDataProvider = $this->createThresholdStrategyDataProviderByKey(
                $minimumOrderValueTransfer->getMinimumOrderValueType()->getKey()
            );
            $data = $thresholdStrategyDataProvider->getData($data, $minimumOrderValueTransfer);

        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getStoreList(): array
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();
        $storeList = [];

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            $storeTransfer = $storeWithCurrencyTransfer->getStore();

            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $row = $storeTransfer->getName()
                    . ' - '
                    . $currencyTransfer->getName()
                    . ' [' . $currencyTransfer->getCode() . ']';

                $storeList[$this->getStoreCurrencyFieldValue($storeTransfer, $currencyTransfer)] = $row;
            }
        }

        return $storeList;
    }

    /**
     * @return array
     */
    protected function getSoftTypesList(): array
    {
        return [
            MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_MESSAGE => "Soft Threshold with message",
            MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_FIXED => "Soft Threshold with fixed fee",
            MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_FLEXIBLE => "Soft Threshold with flexible fee",
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return string
     */
    protected function getStoreCurrencyFieldValue(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): string {
        return $storeTransfer->getName() . MinimumOrderValueGuiConstants::STORE_CURRENCY_DELIMITER . $currencyTransfer->getCode();
    }

    /**
     * @param string $key
     *
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    protected function createThresholdStrategyDataProviderByKey(string $key)
    {
        $providerClassName = "Spryker\\Zed\\MinimumOrderValueGui\\Communication\\Form\\DataProvider\\ThresholdStrategy\\"
            . implode('', explode('-', ucwords($key, '-')))
            . "DataProvider";

        return new $providerClassName();
    }
}
