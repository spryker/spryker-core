<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Currency\Persistence;

use Codeception\Test\Unit;
use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;
use Spryker\Shared\Currency\Persistence\CurrencyPersistence;
use Spryker\Shared\Kernel\Store;

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
    public function testGetCurrentIsoCodeShouldReadFromStoreClassIfNotPersisted()
    {
        $defaultCurrency = 'EUR';

        $sessionClientMock = $this->createSessionClientMock();
        $sessionClientMock->method('get')->willReturn(null);

        $storeMock = $this->createStoreMock();
        $storeMock->method('getCurrencyIsoCode')->willReturn($defaultCurrency);

        $currencyPersistence = $this->createCurrencyPersistence($sessionClientMock, $storeMock);

        $this->assertEquals($defaultCurrency, $currencyPersistence->getCurrentCurrencyIsoCode());
    }

    /**
     * @return void
     */
    public function testGetCurrentIsoCodeShouldReadFromPersistenceFirst()
    {
        $storeCurrency = 'EUR';
        $sessionCurrency = 'USD';

        $sessionClientMock = $this->createSessionClientMock();
        $sessionClientMock->method('get')->willReturn($sessionCurrency);

        $storeMock = $this->createStoreMock();
        $storeMock->method('getCurrencyIsoCode')->willReturn($storeCurrency);

        $currencyPersistence = $this->createCurrencyPersistence($sessionClientMock, $storeMock);

        $this->assertEquals($sessionCurrency, $currencyPersistence->getCurrentCurrencyIsoCode());
    }

    /**
     * @param \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface $sessionClientMock
     * @param \Spryker\Shared\Kernel\Store $storeMock
     *
     * @return \Spryker\Shared\Currency\Persistence\CurrencyPersistence
     */
    protected function createCurrencyPersistence(CurrencyToSessionInterface $sessionClientMock, Store $storeMock)
    {
        return new CurrencyPersistence($sessionClientMock, $storeMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    protected function createSessionClientMock()
    {
        return $this->getMockBuilder(CurrencyToSessionInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Store
     */
    protected function createStoreMock()
    {
        return $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
