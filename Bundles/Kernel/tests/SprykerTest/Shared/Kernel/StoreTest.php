<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Store;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group StoreTest
 * Add your own group annotations below this line
 */
class StoreTest extends Unit
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    private $Store;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Store = Store::getInstance();

        $locales = $this->Store->getLocales();
        if (!in_array('de_DE', $locales)) {
            $this->markTestSkipped('These tests require `de_DE` as part of the current whitelisted locales.');

            return;
        }

        $this->Store->setCurrentLocale('de_DE');
    }

    /**
     * @return void
     */
    public function testInstance()
    {
        $this->assertInstanceOf('\Spryker\Shared\Kernel\Store', $this->Store);
    }

    /**
     * @return void
     */
    public function testGetLocales()
    {
        $locales = $this->Store->getLocales();
        $this->assertSame($locales['de'], 'de_DE');
    }

    /**
     * @return void
     */
    public function testSetCurrentLocale()
    {
        $locale = $this->Store->getCurrentLocale();
        $this->assertSame('de_DE', $locale);

        $newLocale = 'en_US';
        $this->Store->setCurrentLocale($newLocale);

        $locale = $this->Store->getCurrentLocale();
        $this->assertSame($newLocale, $locale);
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testSetCurrentLocaleInvalid()
    {
        $newLocale = 'xy_XY';
        $this->Store->setCurrentLocale($newLocale);
    }

    /**
     * @return void
     */
    public function testInitializeSetupWhenMultipleCurrenciesNotDefinedShouldUseDefault()
    {
        $mockConfig['DE'] = [
            'locales' => [
                'en' => 'en_US',
            ],
            'countries' => [
                'DE',
            ],
            'currencyIsoCode' => 'EUR',
        ];

        $storeMock = $this->createStoreMock();
        $storeMock->method('getStoreSetup')
           ->willReturn($mockConfig);

        $storeMock->initializeSetup('DE');

        $this->assertEquals($mockConfig['DE']['currencyIsoCode'], $storeMock->getCurrencyIsoCode());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Store
     */
    protected function createStoreMock()
    {
        return $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStoreSetup'])
            ->getMock();
    }
}
