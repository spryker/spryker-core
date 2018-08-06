<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreCurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConstants;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;

class GlobalThresholdDataProvider implements FormDataProviderInterface
{
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
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer[] $globalMinimumOrderValueTransfers
     * @param \Generated\Shared\Transfer\StoreCurrencyTransfer $storeCurrencyTransfer
     *
     * @return array
     */
    public function getData(
        array $globalMinimumOrderValueTransfers,
        StoreCurrencyTransfer $storeCurrencyTransfer
    ): array {
        $data = [];
        foreach ($globalMinimumOrderValueTransfers as $globalMinimumOrderValueTransfer) {
            $data[GlobalThresholdType::FIELD_STORE_CURRENCY] = $this->getStoreCurrencyFieldValue(
                $globalMinimumOrderValueTransfer->getStore(),
                $globalMinimumOrderValueTransfer->getCurrency()
            );
            $thresholdStrategyDataProvider = $this->minimumOrderValueGuiCommunicationFactory
                ->createThresholdDataProviderByStrategy(
                    $globalMinimumOrderValueTransfer->getMinimumOrderValue()->getMinimumOrderValueType()->getKey()
                );
            $data = $thresholdStrategyDataProvider->getData($data, $globalMinimumOrderValueTransfer);
        }

        if (empty($globalMinimumOrderValueTransfers)) {
            $data[GlobalThresholdType::FIELD_STORE_CURRENCY] = $this->getStoreCurrencyFieldValue(
                $storeCurrencyTransfer->getStore(),
                $storeCurrencyTransfer->getCurrency()
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
}
