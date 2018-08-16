<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConfig;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;

class GlobalThresholdDataProvider implements FormDataProviderInterface
{
    protected const FORMAT_STORE_CURRENCY_ROW_LABEL = "%s - %s [%s]";
    protected const FORMAT_STORE_CURRENCY_ROW_VALUE = "%s%s%s";

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory
     */
    protected $minimumOrderValueGuiCommunicationFactory;

    /**
     * @param \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory $minimumOrderValueGuiCommunicationFactory
     */
    public function __construct(
        MinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade,
        MinimumOrderValueGuiCommunicationFactory $minimumOrderValueGuiCommunicationFactory
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->minimumOrderValueGuiCommunicationFactory = $minimumOrderValueGuiCommunicationFactory;
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer[] $minimumOrderValueTValueTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return array
     */
    public function getData(
        array $minimumOrderValueTValueTransfers,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $data = [];
        foreach ($minimumOrderValueTValueTransfers as $minimumOrderValueTValueTransfer) {
            $data[GlobalThresholdType::FIELD_STORE_CURRENCY] = $this->formatStoreCurrencyRowValue(
                $minimumOrderValueTValueTransfer->getStore(),
                $minimumOrderValueTValueTransfer->getCurrency()
            );

            if ($thresholdStrategyDataProvider = $this->minimumOrderValueGuiCommunicationFactory
                ->createGlobalSoftThresholdDataProviderResolver()
                ->hasGlobalThresholdDataProviderByStrategyKey($minimumOrderValueTValueTransfer->getThreshold()->getMinimumOrderValueType()->getKey())) {
                $data = $thresholdStrategyDataProvider = $this->minimumOrderValueGuiCommunicationFactory
                    ->createGlobalSoftThresholdDataProviderResolver()
                    ->resolveGlobalThresholdDataProviderByStrategyKey($minimumOrderValueTValueTransfer->getThreshold()->getMinimumOrderValueType()->getKey())
                    ->getData($data, $minimumOrderValueTValueTransfer);
            }
        }

        if (empty($minimumOrderValueTValueTransfers)) {
            $data[GlobalThresholdType::FIELD_STORE_CURRENCY] = $this->formatStoreCurrencyRowValue(
                $storeTransfer,
                $currencyTransfer
            );
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
            "Soft Threshold with message" => MinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE,
            "Soft Threshold with fixed fee" => MinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED,
            "Soft Threshold with flexible fee" => MinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE,
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
}
