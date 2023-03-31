<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Currency\Persistence;

use Codeception\Test\Unit;
use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;
use Spryker\Shared\Currency\Persistence\CurrencyPersistence;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Currency
 * @group Persistence
 * @group CurrencyPersistenceTest
 * Add your own group annotations below this line
 */
class CurrencyPersistenceTest extends Unit
{
    /**
     * @return void
     */
    public function testGetCurrentIsoCodeShouldReadFromStoreClassIfNotPersisted(): void
    {
        $defaultCurrency = 'EUR';

        $sessionClientMock = $this->createSessionClientMock();
        $sessionClientMock->method('get')->willReturn(null);

        $currencyPersistence = $this->createCurrencyPersistence($sessionClientMock, $defaultCurrency);

        $this->assertSame($defaultCurrency, $currencyPersistence->getCurrentCurrencyIsoCode());
    }

    /**
     * @return void
     */
    public function testGetCurrentIsoCodeShouldReadFromPersistenceFirst(): void
    {
        $storeCurrency = 'EUR';
        $sessionCurrency = 'USD';

        $sessionClientMock = $this->createSessionClientMock();
        $sessionClientMock->method('get')->willReturn($sessionCurrency);

        $currencyPersistence = $this->createCurrencyPersistence($sessionClientMock, $storeCurrency);

        $this->assertSame($sessionCurrency, $currencyPersistence->getCurrentCurrencyIsoCode());
    }

    /**
     * @param \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface $sessionClientMock
     * @param string $defaultIsoCode
     *
     * @return \Spryker\Shared\Currency\Persistence\CurrencyPersistence
     */
    protected function createCurrencyPersistence(CurrencyToSessionInterface $sessionClientMock, string $defaultIsoCode): CurrencyPersistence
    {
        return new CurrencyPersistence($sessionClientMock, $defaultIsoCode);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    protected function createSessionClientMock(): CurrencyToSessionInterface
    {
        return $this->getMockBuilder(CurrencyToSessionInterface::class)->getMock();
    }
}
