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
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getInitialData()
    {
        $moneyValueCollection = new ArrayObject();
        $storeCurrencyCollection = $this->currencyFacade->getAvailableStoreCurrencies();
        foreach ($storeCurrencyCollection as $storeCurrencyTransfer) {
            foreach ($storeCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $moneyValueCollection->append(
                    $this->mapMoneyTransfer($currencyTransfer)
                );
            }
        }
        return $moneyValueCollection;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[] $currentFormMoneyValueCollection
     *
     * @return \ArrayObject
     */
    public function mergeMissingMoneyValues(ArrayObject $currentFormMoneyValueCollection)
    {
        $storeCurrencyCollection = $this->currencyFacade->getAvailableStoreCurrencies();

        $existingCurrencyMap = $this->createCurrencyIndexMap($currentFormMoneyValueCollection);

        return $this->mergeMultiStoreMoneyCollection(
            $currentFormMoneyValueCollection,
            $storeCurrencyCollection,
            $existingCurrencyMap
        );
    }

    /**
     * @param \ArrayObject $currentFormMoneyValueCollection
     * @param \Generated\Shared\Transfer\StoreCurrencyTransfer[] $storeCurrencyCollection
     * @param array $existingCurrencyMap
     *
     * @return \ArrayObject
     */
    protected function mergeMultiStoreMoneyCollection(
        ArrayObject $currentFormMoneyValueCollection,
        array $storeCurrencyCollection,
        array $existingCurrencyMap
    ) {

        foreach ($storeCurrencyCollection as $storeCurrencyTransfer) {
            foreach ($storeCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                if (isset($existingCurrencyMap[$currencyTransfer->getIdCurrency() . $currencyTransfer->getStore()->getIdStore()])) {
                    continue;
                }

                $currentFormMoneyValueCollection->append(
                    $this->mapMoneyTransfer($currencyTransfer)
                );
            }
        }

        return $currentFormMoneyValueCollection;
    }

}
