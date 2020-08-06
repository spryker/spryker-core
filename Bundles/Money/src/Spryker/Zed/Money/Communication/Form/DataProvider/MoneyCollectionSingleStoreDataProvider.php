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
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getInitialData()
    {
        $moneyValueCollection = new ArrayObject();
        $storeWithCurrencyTransfer = $this->currencyFacade->getStoreWithCurrencies();
        foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
            $moneyValueCollection->append(
                $this->mapMoneyTransfer($currencyTransfer)
            );
        }

        return $moneyValueCollection;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[] $currentFormMoneyValueCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function mergeMissingMoneyValues(ArrayObject $currentFormMoneyValueCollection)
    {
        $existingCurrencyMap = $this->createCurrencyIndexMap($currentFormMoneyValueCollection);

        $storeWithCurrencyTransfer = $this->currencyFacade->getStoreWithCurrencies();
        foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
            if (isset($existingCurrencyMap[$currencyTransfer->getIdCurrency()])) {
                continue;
            }

            $currentFormMoneyValueCollection->append(
                $this->mapMoneyTransfer($currencyTransfer)
            );
        }

        return $currentFormMoneyValueCollection;
    }
}
