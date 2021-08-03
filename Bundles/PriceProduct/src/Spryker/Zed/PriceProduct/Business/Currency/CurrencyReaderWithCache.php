<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Currency;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;

class CurrencyReaderWithCache implements CurrencyReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected static $currencyCache = [];

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected static $defaultCurrency;

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(PriceProductToCurrencyFacadeInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getDefaultCurrencyTransfer(): CurrencyTransfer
    {
        if (!isset(static::$defaultCurrency)) {
            static::$defaultCurrency = $this->currencyFacade->getDefaultCurrencyForCurrentStore();
        }

        return static::$defaultCurrency;
    }

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransferFromIsoCode(string $isoCode): CurrencyTransfer
    {
        if (!isset(static::$currencyCache[$isoCode])) {
            static::$currencyCache[$isoCode] = $this->currencyFacade->fromIsoCode($isoCode);
        }

        return static::$currencyCache[$isoCode];
    }

    /**
     * @param string[] $isoCodes
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    public function getCurrencyTransfersFromIsoCodes(array $isoCodes): array
    {
        $notCachedIsoCodes = [];
        $filteredCurrencyTransfers = [];

        foreach ($isoCodes as $isoCode) {
            if (!isset(static::$currencyCache[$isoCode])) {
                $notCachedIsoCodes[] = $isoCode;
            }
        }

        if ($notCachedIsoCodes) {
            $filteredCurrencyTransfers = $this->currencyFacade->getCurrencyTransfersByIsoCodes($notCachedIsoCodes);

            foreach ($filteredCurrencyTransfers as $filteredCurrencyTransfer) {
                static::$currencyCache[$filteredCurrencyTransfer->getCode()] = $filteredCurrencyTransfer;
            }
        }

        return array_intersect_key(static::$currencyCache, array_flip($isoCodes));
    }
}
