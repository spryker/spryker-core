<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
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
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getInitialData()
    {
        $moneyValueCollection = new ArrayObject();
        $storeCurrencyCollection = $this->currencyFacade->getAvailableStoreCurrencies();

        foreach ($storeCurrencyCollection as $storeCurrencyTransfer) {
            foreach ($storeCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $moneyValueTransfer = new MoneyValueTransfer();
                $moneyValueTransfer->setCurrency($currencyTransfer);
                $moneyValueTransfer->setFkCurrency($currencyTransfer->getIdCurrency());
                $moneyValueTransfer->setFkStore($currencyTransfer->getStore()->getIdStore());
                $moneyValueCollection->append($moneyValueTransfer);
            }

        }
        return $moneyValueCollection;

    }

    /**
     * @param \ArrayObject $submittedMoneyValueCollection
     *
     * @return \ArrayObject
     */
    public function getMissingValues(ArrayObject $submittedMoneyValueCollection)
    {
        return $submittedMoneyValueCollection;

        $moneyValueCollection = (array)$submittedMoneyValueCollection;
        $storeCurrencyCollection = (array)$this->currencyFacade->getAvailableStoreCurrencies();

        $missingCurrencies = array_diff($storeCurrencyCollection, $moneyValueCollection);

        if (count($missingCurrencies) > 0) {
            foreach ($missingCurrencies as $currencyTransfer) {
                $submittedMoneyValueCollection->append($currencyTransfer);
            }

        }

        return $submittedMoneyValueCollection;
    }
}
