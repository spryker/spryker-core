<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use ArrayObject;
use Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface;

class MoneyCollectionMultiStoreCollectionDataProvider extends BaseMoneyCollectionDataProvider implements MoneyCollectionDataProviderInterface
{
    /**
     * @var \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface $currencyFacade
     */
    public function __construct(MoneyToCurrencyInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer>
     */
    public function getInitialData()
    {
        $moneyValueCollection = new ArrayObject();
        $storeCurrencyCollection = $this->currencyFacade->getAllStoresWithCurrencies();
        foreach ($storeCurrencyCollection as $storeWithCurrencyTransfer) {
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $moneyValueCollection->append(
                    $this->mapMoneyTransfer($currencyTransfer, $storeWithCurrencyTransfer->getStore()),
                );
            }
        }

        return $moneyValueCollection;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer> $currentFormMoneyValueCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer>
     */
    public function mergeMissingMoneyValues(ArrayObject $currentFormMoneyValueCollection)
    {
        $storeCurrencyCollection = $this->currencyFacade->getAllStoresWithCurrencies();

        $existingCurrencyMap = $this->createCurrencyIndexMap($currentFormMoneyValueCollection);

        return $this->mergeMultiStoreMoneyCollection(
            $currentFormMoneyValueCollection,
            $storeCurrencyCollection,
            $existingCurrencyMap,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer> $currentFormMoneyValueCollection
     * @param array<\Generated\Shared\Transfer\StoreWithCurrencyTransfer> $storeCurrencyCollection
     * @param array<bool> $existingCurrencyMap
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer>
     */
    protected function mergeMultiStoreMoneyCollection(
        ArrayObject $currentFormMoneyValueCollection,
        array $storeCurrencyCollection,
        array $existingCurrencyMap
    ) {
        foreach ($storeCurrencyCollection as $storeWithCurrencyTransfer) {
            $storeTransfer = $storeWithCurrencyTransfer->getStore();
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                if (isset($existingCurrencyMap[$currencyTransfer->getIdCurrency() . $storeTransfer->getIdStore()])) {
                    continue;
                }

                $currentFormMoneyValueCollection->append(
                    $this->mapMoneyTransfer($currencyTransfer, $storeTransfer),
                );
            }
        }

        return $currentFormMoneyValueCollection;
    }
}
