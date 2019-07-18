<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold;

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
class SalesOrderThresholdBusinessTester extends Actor
{
    use _generated\SalesOrderThresholdBusinessTesterActions;

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createTestQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setTotals($this->createTotalsTransfer())
            ->setCurrency($this->getCurrencyTransfer())
            ->setStore($this->getStoreTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransfer(): StoreTransfer
    {
        return $this->getLocator()
            ->store()
            ->facade()
            ->getStoreByName('DE');
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransfer(): CurrencyTransfer
    {
        return $this->getLocator()
            ->currency()
            ->facade()
            ->getDefaultCurrencyForCurrentStore();
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
