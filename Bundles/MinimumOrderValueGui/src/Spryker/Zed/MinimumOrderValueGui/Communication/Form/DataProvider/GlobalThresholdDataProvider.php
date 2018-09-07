<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConfig as SharedMinimumOrderValueGuiConfig;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig;

class GlobalThresholdDataProvider
{
    protected const FORMAT_STORE_CURRENCY_ROW_LABEL = "%s - %s [%s]";
    protected const FORMAT_STORE_CURRENCY_ROW_VALUE = "%s%s%s";

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMinimumOrderValueFacadeInterface
     */
    protected $minimumOrderValueFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface
     */
    protected $globalThresholdDataProviderResolver;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMinimumOrderValueFacadeInterface $minimumOrderValueFacade
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface $globalThresholdDataProviderResolver
     */
    public function __construct(
        MinimumOrderValueGuiToMinimumOrderValueFacadeInterface $minimumOrderValueFacade,
        MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade,
        GlobalThresholdDataProviderResolverInterface $globalThresholdDataProviderResolver
    ) {
        $this->minimumOrderValueFacade = $minimumOrderValueFacade;
        $this->currencyFacade = $currencyFacade;
        $this->globalThresholdDataProviderResolver = $globalThresholdDataProviderResolver;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'allow_extra_fields' => true,
            GlobalThresholdType::OPTION_STORES_ARRAY => $this->getStoreList(),
            GlobalThresholdType::OPTION_SOFT_TYPES_ARRAY => $this->getSoftTypesList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return array
     */
    public function getData(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $minimumOrderValueTValueTransfers = $this->getMinimumOrderValueTransfers($storeTransfer, $currencyTransfer);

        $data = [];
        foreach ($minimumOrderValueTValueTransfers as $minimumOrderValueTValueTransfer) {
            if ($thresholdStrategyDataProvider = $this->globalThresholdDataProviderResolver
                ->hasGlobalThresholdDataProviderByStrategyKey($minimumOrderValueTValueTransfer->getMinimumOrderValueThreshold()->getMinimumOrderValueType()->getKey())) {
                $data = $thresholdStrategyDataProvider = $this->globalThresholdDataProviderResolver
                    ->resolveGlobalThresholdDataProviderByStrategyKey($minimumOrderValueTValueTransfer->getMinimumOrderValueThreshold()->getMinimumOrderValueType()->getKey())
                    ->getData($data, $minimumOrderValueTValueTransfer);
            }
        }

        $data[GlobalThresholdType::FIELD_STORE_CURRENCY] = $this->formatStoreCurrencyRowValue(
            $storeTransfer,
            $currencyTransfer
        );

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
                $storeList[$this->formatStoreCurrencyRowLabel(
                    $storeTransfer,
                    $currencyTransfer
                )] = $this->formatStoreCurrencyRowValue($storeTransfer, $currencyTransfer);
            }
        }

        return $storeList;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return string
     */
    protected function formatStoreCurrencyRowLabel(StoreTransfer $storeTransfer, CurrencyTransfer $currencyTransfer): string
    {
        return sprintf(
            static::FORMAT_STORE_CURRENCY_ROW_LABEL,
            $storeTransfer->getName(),
            $currencyTransfer->getName(),
            $currencyTransfer->getCode()
        );
    }

    /**
     * @return string[]
     */
    protected function getSoftTypesList(): array
    {
        return [
            "Soft Threshold with message" => SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE,
            "Soft Threshold with fixed fee" => SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED,
            "Soft Threshold with flexible fee" => SharedMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return string
     */
    protected function formatStoreCurrencyRowValue(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): string {
        return sprintf(
            static::FORMAT_STORE_CURRENCY_ROW_VALUE,
            $storeTransfer->getName(),
            MinimumOrderValueGuiConfig::STORE_CURRENCY_DELIMITER,
            $currencyTransfer->getCode()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    protected function getMinimumOrderValueTransfers(StoreTransfer $storeTransfer, CurrencyTransfer $currencyTransfer): array
    {
        return $this->minimumOrderValueFacade
            ->getMinimumOrderValues(
                $storeTransfer,
                $currencyTransfer
            );
    }
}
