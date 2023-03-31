<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Money;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Currency\CurrencyDependencyProvider;
use Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface;
use Spryker\Client\Locale\LocaleDependencyProvider;

/**
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
class MoneyYvesTester extends Actor
{
    use _generated\MoneyYvesTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->setDependency(LocaleDependencyProvider::LOCALE_CURRENT, static::LOCALE_DE);
        $this->setDependency(CurrencyDependencyProvider::CLIENT_STORE, $this->createCurrencyToStoreClientMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface
     */
    protected function createCurrencyToStoreClientMock(): CurrencyToStoreClientInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE)
            ->setAvailableCurrencyIsoCodes([static::DEFAULT_CURRENCY]);

        return Stub::makeEmpty(CurrencyToStoreClientInterface::class, [
            'getCurrentStore' => $storeTransfer,
        ]);
    }
}
