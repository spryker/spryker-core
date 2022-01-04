<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Dependency\Facade;

use Generated\Shared\Transfer\CurrencyTransfer;

class MoneyToCurrencyBridge implements MoneyToCurrencyInterface
{
    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Currency\Business\CurrencyFacadeInterface $currencyFacade
     */
    public function __construct($currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function fromIsoCode($isoCode)
    {
        return $this->currencyFacade->fromIsoCode($isoCode);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer
     */
    public function getStoreWithCurrencies()
    {
        return $this->currencyFacade->getCurrentStoreWithCurrencies();
    }

    /**
     * @return array<\Generated\Shared\Transfer\StoreWithCurrencyTransfer>
     */
    public function getAllStoresWithCurrencies()
    {
        return $this->currencyFacade->getAllStoresWithCurrencies();
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent(): CurrencyTransfer
    {
        return $this->currencyFacade->getCurrent();
    }
}
