<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Currency;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface;
use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;
use Spryker\Yves\Currency\CurrencyDependencyProvider;

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
class CurrencyCommunicationTester extends Actor
{
    use _generated\CurrencyCommunicationTesterActions;

    /**
     * @var string
     */
    public const CURRENCY_EUR = 'EUR';

    /**
     * @var string
     */
    public const CURRENCY_USD = 'USD';

    /**
     * @var string
     */
    public const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @return void
     */
    public function mockSession(): void
    {
        $this->setDependency(CurrencyDependencyProvider::CLIENT_SESSION, Stub::makeEmpty(CurrencyToSessionInterface::class));
    }

    /**
     * @return void
     */
    public function mockStoreClientDependency(): void
    {
        $currencyToStoreClientMock = Stub::makeEmpty(CurrencyToStoreClientInterface::class);
        $currencyToStoreClientMock->method('getCurrentStore')
            ->willReturn(
                (new StoreTransfer())
                    ->setName(static::DEFAULT_STORE)
                    ->setAvailableCurrencyIsoCodes([static::CURRENCY_EUR, static::CURRENCY_USD])
                    ->setDefaultCurrencyIsoCode(static::CURRENCY_USD),
            );

        $this->setDependency(static::CLIENT_STORE, $currencyToStoreClientMock);
    }
}
