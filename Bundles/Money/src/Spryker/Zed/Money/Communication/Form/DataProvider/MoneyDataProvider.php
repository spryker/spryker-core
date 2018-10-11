<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Zed\Money\Dependency\Facade\MoneyToStoreInterface;

class MoneyDataProvider
{
    public const DEFAULT_SCALE = 2;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected static $storeCache = [];

    /**
     * @var \Spryker\Zed\Money\Dependency\Facade\MoneyToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Money\Dependency\Facade\MoneyToStoreInterface $storeFacade
     */
    public function __construct(MoneyToStoreInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param mixed $moneyValueTransfer
     *
     * @return mixed
     */
    public function getMoneyCurrencyOptionsFor($moneyValueTransfer)
    {
        $options = [];

        $currencyTransfer = $moneyValueTransfer->getCurrency();
        $options['divisor'] = $this->getDivisor($currencyTransfer);
        $options['scale'] = $this->getScale($currencyTransfer);

        return $options;
    }

    /**
     *
     * The fraction digits is number of digits after decimal point,
     * It returns integer divisor value which will be used when converting value to cents
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getDivisor(CurrencyTransfer $currencyTransfer)
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        $divisor = 1;
        if ($fractionDigits) {
            $divisor = pow(10, $fractionDigits);
        }

        return $divisor;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getScale(CurrencyTransfer $currencyTransfer)
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        if ($fractionDigits !== null) {
            return $fractionDigits;
        }

        return static::DEFAULT_SCALE;
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById($idStore)
    {
        if (isset(static::$storeCache[$idStore])) {
            return static::$storeCache[$idStore];
        }

        static::$storeCache[$idStore] = $this->storeFacade->getStoreById($idStore);

        return static::$storeCache[$idStore];
    }
}
