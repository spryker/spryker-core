<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\GlobalThresholdDataProviderResolverInterface;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\GlobalHardThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\GlobalSoftThresholdType;
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
     * @var \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\GlobalThresholdDataProviderResolverInterface
     */
    protected $globalThresholdDataProviderResolver;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    protected $formExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\GlobalThresholdDataProviderResolverInterface $globalThresholdDataProviderResolver
     * @param \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[] $formExpanderPlugins
     */
    public function __construct(
        SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface $salesOrderThresholdFacade,
        SalesOrderThresholdGuiToCurrencyFacadeInterface $currencyFacade,
        GlobalThresholdDataProviderResolverInterface $globalThresholdDataProviderResolver,
        array $formExpanderPlugins
    ) {
        $this->salesOrderThresholdFacade = $salesOrderThresholdFacade;
        $this->currencyFacade = $currencyFacade;
        $this->globalThresholdDataProviderResolver = $globalThresholdDataProviderResolver;
        $this->formExpanderPlugins = $formExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return array
     */
    public function getOptions(CurrencyTransfer $currencyTransfer): array
    {
        return [
            'allow_extra_fields' => true,
            GlobalThresholdType::OPTION_CURRENCY_CODE => $currencyTransfer->getCode(),
            GlobalThresholdType::OPTION_STORE_CURRENCY_ARRAY => $this->getStoreCurrencyList(),
            GlobalThresholdType::OPTION_HARD_TYPES_ARRAY => $this->getHardTypesList(),
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
        $data = [
            GlobalThresholdType::FIELD_HARD => [
                GlobalHardThresholdType::FIELD_STRATEGY => current($this->getHardTypesList()),
            ],
            GlobalThresholdType::FIELD_SOFT => [
                GlobalSoftThresholdType::FIELD_STRATEGY => current($this->getSoftTypesList()),
            ],
        ];

        $salesOrderThresholdTransfers = $this->getSalesOrderThresholdTransfers($storeTransfer, $currencyTransfer);
        foreach ($salesOrderThresholdTransfers as $salesOrderThresholdTransfer) {
            if ($thresholdStrategyDataProvider = $this->globalThresholdDataProviderResolver
                ->hasGlobalThresholdDataProviderByStrategyGroup($salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getThresholdGroup())) {
                $data = $thresholdStrategyDataProvider = $this->globalThresholdDataProviderResolver
                    ->resolveGlobalThresholdDataProviderByStrategyGroup($salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getSalesOrderThresholdType()->getThresholdGroup())
                    ->mapSalesOrderThresholdValueTransferToFormData($salesOrderThresholdTransfer, $data);
            }
        }

        $data[GlobalThresholdType::FIELD_STORE_CURRENCY] = $this->formatStoreCurrencyRowValue(
            $storeTransfer,
            $currencyTransfer
        );

        return $data;
    }

    /**
     * @return string[]
     */
    protected function getStoreCurrencyList(): array
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();
        $storeCurrencyList = [];

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            $storeTransfer = $storeWithCurrencyTransfer->getStore();

            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $storeCurrencyList[$this->formatStoreCurrencyRowLabel(
                    $storeTransfer,
                    $currencyTransfer
                )] = $this->formatStoreCurrencyRowValue($storeTransfer, $currencyTransfer);
            }
        }

        return $storeCurrencyList;
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
    protected function getHardTypesList(): array
    {
        $hardTypesList = [];
        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdGroup() === SalesOrderThresholdGuiConfig::GROUP_HARD) {
                $hardTypesList[$formExpanderPlugin->getThresholdName()] = $formExpanderPlugin->getThresholdKey();
            }
        }

        return $hardTypesList;
    }

    /**
     * @return string[]
     */
    protected function getSoftTypesList(): array
    {
        $softTypesList = [];
        foreach ($this->formExpanderPlugins as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdGroup() === SalesOrderThresholdGuiConfig::GROUP_SOFT) {
                $softTypesList[$formExpanderPlugin->getThresholdName()] = $formExpanderPlugin->getThresholdKey();
            }
        }

        return $softTypesList;
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
