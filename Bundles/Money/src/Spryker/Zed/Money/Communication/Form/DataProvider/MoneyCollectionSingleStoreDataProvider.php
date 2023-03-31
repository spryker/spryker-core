<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use ArrayObject;
use Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface;

class MoneyCollectionSingleStoreDataProvider extends BaseMoneyCollectionDataProvider implements MoneyCollectionDataProviderInterface
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

        foreach ($this->getCurrencies() as $currencyTransfer) {
            $moneyValueCollection->append(
                $this->mapMoneyTransfer($currencyTransfer),
            );
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
        $existingCurrencyMap = $this->createCurrencyIndexMap($currentFormMoneyValueCollection);

        foreach ($this->getCurrencies() as $currencyTransfer) {
            if (isset($existingCurrencyMap[$currencyTransfer->getIdCurrency()])) {
                continue;
            }

            $currentFormMoneyValueCollection->append(
                $this->mapMoneyTransfer($currencyTransfer),
            );
        }

        return $currentFormMoneyValueCollection;
    }

    /**
     * @return array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    protected function getCurrencies(): array
    {
        $currencyTransfers = [];
        foreach ($this->currencyFacade->getAllStoresWithCurrencies() as $storeWithCurrencyTransfer) {
            $currencyTransfers[] = $storeWithCurrencyTransfer->getCurrencies()->getArrayCopy();
        }

        return array_merge(...$currencyTransfers);
    }
}
