<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;

class GlobalThresholdDataProvider implements FormDataProviderInterface
{
    public const STORE_CURRENCY_DELIMITER = ';';
    public const SOFT_TYPE_MESSAGE = 'soft-threshold';
    public const SOFT_TYPE_FIXED = 'soft-threshold-fixed-fee';
    public const SOFT_TYPE_FLEXIBLE = 'soft-threshold-flexible-fee';

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
        /*
        if ($quoteTransfer->getManualOrder() === null) {
            $quoteTransfer->setManualOrder(new ManualOrderTransfer());
        }

        if ($quoteTransfer->getStore() !== null
            && $quoteTransfer->getCurrency() !== null
        ) {
            $storeName = $quoteTransfer->getStore()->getName();
            $currencyCode = $quoteTransfer->getCurrency()->getCode();

            $quoteTransfer->getManualOrder()->setStoreCurrency($storeName . StoreFormHandler::STORE_CURRENCY_DELIMITER . $currencyCode);
        }
        */
        $data = [];
        foreach ($minimumOrderValueTransfers as $minimumOrderValueTransfer) {
            $data[GlobalThresholdType::FIELD_STORE_CURRENCY] = $this->getStoreCurrencyFieldValue(
                $minimumOrderValueTransfer->getStore(),
                $minimumOrderValueTransfer->getCurrency()
            );
            $thresholdStrategyDataProvider = $this->createThresholdStrategyDataProvider(
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
            static::SOFT_TYPE_MESSAGE => "Soft Threshold with message",
            static::SOFT_TYPE_FIXED => "Soft Threshold with fixed fee",
            static::SOFT_TYPE_FLEXIBLE => "Soft Threshold with flexible fee",
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
        return $storeTransfer->getName() . static::STORE_CURRENCY_DELIMITER . $currencyTransfer->getCode();
    }

    /**
     * @param string $key
     *
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdStrategyDataProviderInterface
     */
    protected function createThresholdStrategyDataProvider(string $key)
    {
        $providerClassName = "ThresholdStrategy\\" . implode('', explode('-', ucwords($key, '-'))) . "DataProvider";

        return new $providerClassName();
    }
}
