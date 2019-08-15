<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface;
use Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface;

class PriceProductTransferMoneyValueDataExpander implements PriceProductTransferDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface
     */
    protected $storeFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface
     */
    protected $currencyFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface $storeFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface $currencyFinder
     */
    public function __construct(
        StoreFinderInterface $storeFinder,
        CurrencyFinderInterface $currencyFinder
    ) {
        $this->storeFinder = $storeFinder;
        $this->currencyFinder = $currencyFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer->requireMoneyValue();
        $moneyValue = $priceProductTransfer->getMoneyValue();

        $this->expandMoneyValueTransferWithCurrencyData($moneyValue);
        $this->expandMoneyValueTransferWithStoreData($moneyValue);

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValue
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function expandMoneyValueTransferWithCurrencyData(MoneyValueTransfer $moneyValue): MoneyValueTransfer
    {
        $moneyValue->requireCurrency();
        $currencyTransfer = $this->currencyFinder
            ->findCurrencyByIsoCode($moneyValue->getCurrency()->getCode());

        if ($currencyTransfer !== null) {
            $moneyValue
                ->setCurrency($currencyTransfer)
                ->setFkCurrency($currencyTransfer->getIdCurrency());
        }

        return $moneyValue;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValue
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function expandMoneyValueTransferWithStoreData(MoneyValueTransfer $moneyValue): MoneyValueTransfer
    {
        $moneyValue->requireStore();
        $storeTransfer = $this->storeFinder
            ->findStoreByName($moneyValue->getStore()->getName());

        if ($storeTransfer !== null) {
            $moneyValue->setFkStore($storeTransfer->getIdStore());
        }

        return $moneyValue;
    }
}
