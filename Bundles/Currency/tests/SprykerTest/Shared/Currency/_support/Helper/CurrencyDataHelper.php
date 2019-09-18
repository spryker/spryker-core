<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Currency\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CurrencyDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return int
     */
    public function haveCurrency(array $override = [])
    {
         $currencyTransfer = (new CurrencyBuilder($override))->build();

         return $this->getCurrencyFacade()->createCurrency($currencyTransfer);
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function haveCurrencyTransfer(array $override = []): CurrencyTransfer
    {
        $currencyFacade = $this->getCurrencyFacade();
        $currencyTransfer = (new CurrencyBuilder($override))->build();
        $idCurrency = $currencyFacade->createCurrency($currencyTransfer);

        return $currencyFacade->getByIdCurrency($idCurrency);
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getLocator()->currency()->facade();
    }
}
