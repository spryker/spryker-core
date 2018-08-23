<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\MerchantRelationshipMinimumOrderValueGuiCommunicationFactory;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface;

class ThresholdDataProvider implements FormDataProviderInterface
{
    protected const FORMAT_STORE_CURRENCY_ROW_LABEL = "%s - %s [%s]";
    protected const FORMAT_STORE_CURRENCY_ROW_VALUE = "%s%s%s";

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\MerchantRelationshipMinimumOrderValueGuiCommunicationFactory
     */
    protected $minimumOrderValueGuiCommunicationFactory;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\MerchantRelationshipMinimumOrderValueGuiCommunicationFactory $minimumOrderValueGuiCommunicationFactory
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade,
        MerchantRelationshipMinimumOrderValueGuiCommunicationFactory $minimumOrderValueGuiCommunicationFactory
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
            ThresholdType::OPTION_STORES_ARRAY => $this->getStoreList(),
            ThresholdType::OPTION_SOFT_TYPES_ARRAY => $this->getSoftTypesList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer[] $minimumOrderValueTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return array
     */
    public function getData(
        array $minimumOrderValueTransfers,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $data = [];
        foreach ($minimumOrderValueTransfers as $minimumOrderValueTValueTransfer) {
            $data[ThresholdType::FIELD_STORE_CURRENCY] = $this->formatStoreCurrencyRowValue(
                $minimumOrderValueTValueTransfer->getStore(),
                $minimumOrderValueTValueTransfer->getCurrency()
            );

            if ($thresholdStrategyDataProvider = $this->minimumOrderValueGuiCommunicationFactory
                ->createSoftThresholdDataProviderResolver()
                ->hasThresholdDataProviderByStrategyKey($minimumOrderValueTValueTransfer->getThreshold()->getMinimumOrderValueType()->getKey())) {
                $data = $thresholdStrategyDataProvider = $this->minimumOrderValueGuiCommunicationFactory
                    ->createSoftThresholdDataProviderResolver()
                    ->resolveThresholdDataProviderByStrategyKey($minimumOrderValueTValueTransfer->getThreshold()->getMinimumOrderValueType()->getKey())
                    ->getData($data, $minimumOrderValueTValueTransfer);
            }
        }

        if (empty($minimumOrderValueTransfers)) {
            $data[ThresholdType::FIELD_STORE_CURRENCY] = $this->formatStoreCurrencyRowValue(
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
            "Soft Threshold with message" => MerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE,
            "Soft Threshold with fixed fee" => MerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED,
            "Soft Threshold with flexible fee" => MerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE,
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
            MerchantRelationshipMinimumOrderValueGuiConfig::STORE_CURRENCY_DELIMITER,
            $currencyTransfer->getCode()
        );
    }
}
