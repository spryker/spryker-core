<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MoneyGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MoneyGui\Dependency\Facade\MoneyGuiToStoreFacadeInterface;

class MoneyTypeDataProvider
{
    /**
     * @var int
     */
    protected const DEFAULT_SCALE = 2;

    /**
     * @var int
     */
    protected const DEFAULT_DIVISOR = 1;

    /**
     * @var array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected static $storeTransfersCache = [];

    /**
     * @var \Spryker\Zed\MoneyGui\Dependency\Facade\MoneyGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MoneyGui\Dependency\Facade\MoneyGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(MoneyGuiToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return array<string, int>
     */
    public function getMoneyCurrencyOptions(MoneyValueTransfer $moneyValueTransfer): array
    {
        $options = [];

        $currencyTransfer = $moneyValueTransfer->getCurrencyOrFail();
        $options['divisor'] = $this->getDivisor($currencyTransfer);
        $options['scale'] = $this->getScale($currencyTransfer);

        return $options;
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById(int $idStore): StoreTransfer
    {
        if (!isset(static::$storeTransfersCache[$idStore])) {
            static::$storeTransfersCache[$idStore] = $this->storeFacade->getStoreById($idStore);
        }

        return static::$storeTransfersCache[$idStore];
    }

    /**
     * The fraction digits is number of digits after decimal point,
     * It returns integer divisor value which will be used when converting value to cents
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getDivisor(CurrencyTransfer $currencyTransfer): int
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        if (!$fractionDigits) {
            return static::DEFAULT_DIVISOR;
        }

        return 10 ** $fractionDigits;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getScale(CurrencyTransfer $currencyTransfer): int
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        if ($fractionDigits !== null) {
            return $fractionDigits;
        }

        return static::DEFAULT_SCALE;
    }
}
