<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Business\Model;

use Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface;
use Spryker\Zed\Money\Dependency\Facade\MoneyToStoreInterface;

class CurrencyReader implements CurrencyReaderInterface
{
    /**
     * @var \Spryker\Zed\Money\Dependency\Facade\MoneyToStoreInterface
     */
    protected MoneyToStoreInterface $storeFacade;

    /**
     * @var \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface
     */
    protected MoneyToCurrencyInterface $currencyFacade;

    /**
     * @param \Spryker\Zed\Money\Dependency\Facade\MoneyToStoreInterface $storeFacade
     * @param \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface $currencyFacade
     */
    public function __construct(MoneyToStoreInterface $storeFacade, MoneyToCurrencyInterface $currencyFacade)
    {
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return string|null
     */
    public function readCurrencyIsoCode(): ?string
    {
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if ($this->storeFacade->isDynamicStoreEnabled()) {
            return null;
        }

        return $this->currencyFacade->getCurrent()->getCodeOrFail();
    }
}
