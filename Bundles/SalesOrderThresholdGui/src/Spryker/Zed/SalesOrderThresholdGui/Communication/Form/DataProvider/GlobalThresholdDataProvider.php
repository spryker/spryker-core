<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig as SharedSalesOrderThresholdGuiConfig;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToCurrencyFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;

class GlobalThresholdDataProvider
{
    protected const FORMAT_STORE_CURRENCY_ROW_LABEL = "%s - %s [%s]";
    protected const FORMAT_STORE_CURRENCY_ROW_VALUE = "%s%s%s";

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface
     */
    protected $salesOrderThresholdFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface
     */
    protected $globalThresholdDataProviderResolver;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface $globalThresholdDataProviderResolver
     */
    public function __construct(
        SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade,
        SalesOrderThresholdGuiToCurrencyFacadeInterface $currencyFacade,
        GlobalThresholdDataProviderResolverInterface $globalThresholdDataProviderResolver
    ) {
        $this->salesOrderThresholdFacade = $salesOrderThresholdFacade;
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
        $salesOrderThresholdTransfers = $this->getSalesOrderThresholdTransfers($storeTransfer, $currencyTransfer);

        $data = [
            GlobalThresholdType::FIELD_ID_THRESHOLD_HARD => null,
            GlobalThresholdType::FIELD_ID_THRESHOLD_SOFT => null,
            GlobalThresholdType::FIELD_SOFT_THRESHOLD => null,
            GlobalThresholdType::FIELD_HARD_THRESHOLD => null,
            GlobalThresholdType::FIELD_SOFT_FIXED_FEE => null,
            GlobalThresholdType::FIELD_SOFT_FLEXIBLE_FEE => null,
        ];
        foreach ($salesOrderThresholdTransfers as $salesOrderThresholdTransfer) {
            if ($thresholdStrategyDataProvider = $this->globalThresholdDataProviderResolver
                ->hasGlobalThresholdDataProviderByStrategyKey($salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey())) {
                $data = $thresholdStrategyDataProvider = $this->globalThresholdDataProviderResolver
                    ->resolveGlobalThresholdDataProviderByStrategyKey($salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getKey())
                    ->getData($data, $salesOrderThresholdTransfer);
            }
        }

        $data[GlobalThresholdType::FIELD_CURRENCY] = $currencyTransfer->getCode();
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
            "Soft Threshold with message" => SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE,
            "Soft Threshold with fixed fee" => SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FIXED,
            "Soft Threshold with flexible fee" => SharedSalesOrderThresholdGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE,
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
            SalesOrderThresholdGuiConfig::STORE_CURRENCY_DELIMITER,
            $currencyTransfer->getCode()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer[]
     */
    protected function getSalesOrderThresholdTransfers(StoreTransfer $storeTransfer, CurrencyTransfer $currencyTransfer): array
    {
        return $this->salesOrderThresholdFacade
            ->getSalesOrderThresholds(
                $storeTransfer,
                $currencyTransfer
            );
    }
}
