<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType;
use Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface;

class MoneyCollectionDataProvider
{

    /**
     * @var \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface
     */
    private $currencyFacade;

    /**
     * @param \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface $currencyFacade
     */
    public function __construct(MoneyToCurrencyInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param string[] $options
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getInitialData(array $options)
    {
        if ($options[MoneyCollectionType::OPTION_AMOUNT_PER_STORE]) {
            return $this->createMultiStoreInitialData();
        }

        $moneyValueCollection = new ArrayObject();
        $storeCurrencyCollection = $this->currencyFacade->getStoreCurrencies();
        foreach ($storeCurrencyCollection as $currencyTransfer) {
            $moneyValueCollection->append(
                $this->mapMoneyTransfer($currencyTransfer)
            );
        }

        return $moneyValueCollection;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[] $currentFormMoneyValueCollection
     * @param string[] $options
     *
     * @return \ArrayObject
     */
    public function mergeMissingMoneyValues(ArrayObject $currentFormMoneyValueCollection, array $options)
    {
        $storeCurrencyCollection = $this->currencyFacade->getAvailableStoreCurrencies();

        $existingCurrencyMap = $this->createCurrencyIndexMap($currentFormMoneyValueCollection);

        if ($options[MoneyCollectionType::OPTION_AMOUNT_PER_STORE]) {
            return $this->mergeMultiStoreMoneyCollection(
                $currentFormMoneyValueCollection,
                $storeCurrencyCollection,
                $existingCurrencyMap
            );
        }

        $storeCurrencyCollection = $this->currencyFacade->getStoreCurrencies();
        foreach ($storeCurrencyCollection as $currencyTransfer) {
            if (isset($existingCurrencyMap[$currencyTransfer->getIdCurrency() . $currencyTransfer->getStore()->getIdStore()])) {
                continue;
            }

            $currentFormMoneyValueCollection->append(
                $this->mapMoneyTransfer($currencyTransfer)
            );
        }

        return $currentFormMoneyValueCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapMoneyTransfer(CurrencyTransfer $currencyTransfer)
    {
        $moneyValueTransfer = new MoneyValueTransfer();
        $moneyValueTransfer->setCurrency($currencyTransfer);
        $moneyValueTransfer->setFkCurrency($currencyTransfer->getIdCurrency());
        $moneyValueTransfer->setFkStore($currencyTransfer->getStore()->getIdStore());

        return $moneyValueTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[] $submittedMoneyValueCollection
     *
     * @return array
     */
    protected function createCurrencyIndexMap(ArrayObject $submittedMoneyValueCollection)
    {
        $currencyIndex = [];
        foreach ($submittedMoneyValueCollection as $moneyValueTransfer) {
            $idStore = $moneyValueTransfer->getCurrency()->getStore()->getIdStore();
            $currencyIndex[$moneyValueTransfer->getFkCurrency() . $idStore] = true;
        }
        return $currencyIndex;
    }

    /**
     * @return \ArrayObject
     */
    protected function createMultiStoreInitialData()
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
