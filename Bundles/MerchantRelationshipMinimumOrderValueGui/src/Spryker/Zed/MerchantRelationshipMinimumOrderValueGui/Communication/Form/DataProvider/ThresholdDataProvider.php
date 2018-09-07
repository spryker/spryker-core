<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig as SharedMerchantRelationshipMinimumOrderValueGuiConfig;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolverInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMerchantRelationshipMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\MerchantRelationshipMinimumOrderValueGuiConfig;

class ThresholdDataProvider
{
    protected const FORMAT_STORE_CURRENCY_ROW_LABEL = "%s - %s [%s]";
    protected const FORMAT_STORE_CURRENCY_ROW_VALUE = "%s%s%s";

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMerchantRelationshipMinimumOrderValueFacadeInterface
     */
    protected $merchantRelationshipMinimumOrderValueFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolverInterface
     */
    protected $thresholdDataProviderResolver;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMerchantRelationshipMinimumOrderValueFacadeInterface $merchantRelationshipMinimumOrderValueFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\ThresholdDataProviderResolverInterface $thresholdDataProviderResolver
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueGuiToMerchantRelationshipMinimumOrderValueFacadeInterface $merchantRelationshipMinimumOrderValueFacade,
        MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeInterface $currencyFacade,
        ThresholdDataProviderResolverInterface $thresholdDataProviderResolver
    ) {
        $this->merchantRelationshipMinimumOrderValueFacade = $merchantRelationshipMinimumOrderValueFacade;
        $this->currencyFacade = $currencyFacade;
        $this->thresholdDataProviderResolver = $thresholdDataProviderResolver;
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
     * @param int $idMerchantRelationship
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return array
     */
    public function getData(
        int $idMerchantRelationship,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $minimumOrderValueTransfers = $this->getMinimumOrderValueTransfers($storeTransfer, $currencyTransfer, $idMerchantRelationship);

        $data = [];
        foreach ($minimumOrderValueTransfers as $minimumOrderValueTransfer) {
            if ($this->thresholdDataProviderResolver
                ->hasThresholdDataProviderByStrategyKey(
                    $minimumOrderValueTransfer->getMinimumOrderValueThreshold()->getMinimumOrderValueType()->getKey()
                )
            ) {
                $data = $this->thresholdDataProviderResolver
                    ->resolveThresholdDataProviderByStrategyKey($minimumOrderValueTransfer->getMinimumOrderValueThreshold()->getMinimumOrderValueType()->getKey())
                    ->getData($data, $minimumOrderValueTransfer);
            }
        }

        $data[ThresholdType::FIELD_STORE_CURRENCY] = $this->formatStoreCurrencyRowValue(
            $storeTransfer,
            $currencyTransfer
        );

        $data[ThresholdType::FIELD_ID_MERCHANT_RELATIONSHIP] = $idMerchantRelationship;

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer[]
     */
    protected function getMinimumOrderValueTransfers(StoreTransfer $storeTransfer, CurrencyTransfer $currencyTransfer, int $idMerchantRelationship): array
    {
        return $this->merchantRelationshipMinimumOrderValueFacade
            ->getMerchantRelationshipMinimumOrderValues(
                $storeTransfer,
                $currencyTransfer,
                [$idMerchantRelationship]
            );
    }

    /**
     * Array format: ['DE;EUR' => 'DE - Euro [EUR]']
     *
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
            "Soft Threshold with message" => SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_MESSAGE,
            "Soft Threshold with fixed fee" => SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FIXED,
            "Soft Threshold with flexible fee" => SharedMerchantRelationshipMinimumOrderValueGuiConfig::SOFT_TYPE_STRATEGY_FLEXIBLE,
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
