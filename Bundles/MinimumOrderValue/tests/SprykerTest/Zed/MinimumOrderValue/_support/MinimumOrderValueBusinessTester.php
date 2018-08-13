<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValue;

use Codeception\Actor;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class MinimumOrderValueBusinessTester extends Actor
{
    use _generated\MinimumOrderValueBusinessTesterActions;

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createTestQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setTotals($this->createTotalsTransfer())
            ->setCurrency($this->createCurrencyTransfer())
            ->setStore($this->createStoreTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function createStoreTransfer(): StoreTransfer
    {
        return (new StoreTransfer())
            ->setIdStore(1)
            ->setName('DE');
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function createCurrencyTransfer(): CurrencyTransfer
    {
        return (new CurrencyTransfer())
            ->setIdCurrency(1)
            ->setCode('EUR');
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotalsTransfer(): TotalsTransfer
    {
        return (new TotalsTransfer())
            ->setSubTotal(0);
    }
}
