<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Dependency\Facade;

class PriceProductToCurrencyFacadeBridge implements PriceProductToCurrencyFacadeInterface
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
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getByIdCurrency($idCurrency)
    {
        return $this->currencyFacade->getByIdCurrency($idCurrency);
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getDefaultCurrencyForCurrentStore()
    {
        return $this->currencyFacade->getDefaultCurrencyForCurrentStore();
    }
}
