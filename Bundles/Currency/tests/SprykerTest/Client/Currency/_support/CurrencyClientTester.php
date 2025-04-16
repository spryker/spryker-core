<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Currency;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Currency\CurrencyClientInterface;
use Spryker\Client\Currency\CurrencyDependencyProvider;
use Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface;

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
class CurrencyClientTester extends Actor
{
    use _generated\CurrencyClientTesterActions;

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

        $this->setDependency(CurrencyDependencyProvider::CLIENT_STORE, $currencyToStoreClientMock);
    }

    /**
     * @return \Spryker\Client\Currency\CurrencyClientInterface
     */
    public function getCurrencyClient(): CurrencyClientInterface
    {
        return $this->getLocator()->currency()->client();
    }
}
