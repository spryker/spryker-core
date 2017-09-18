<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade;

class CurrencyDiscountConnectorToCurrencyBridge implements CurrencyDiscountConnectorToCurrencyInterface
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
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent()
    {
        return $this->currencyFacade->getCurrent();
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    public function getStoreCurrencies()
    {
        return $this->currencyFacade->getStoreCurrencies();
    }

}
