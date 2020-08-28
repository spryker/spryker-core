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
    public function setUp(): void
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
    public function testInstance(): void
    {
        $this->assertInstanceOf('\Spryker\Shared\Kernel\Store', $this->Store);
    }

    /**
     * @return void
     */
    public function testGetLocales(): void
    {
        $locales = $this->Store->getLocales();
        $this->assertSame($locales['de'], 'de_DE');
    }

    /**
     * @return void
     */
    public function testSetCurrentLocale(): void
    {
        $locale = $this->Store->getCurrentLocale();
        $this->assertSame('de_DE', $locale);

        $newLocale = 'en_US';
        $this->Store->setCurrentLocale($newLocale);

        $locale = $this->Store->getCurrentLocale();
        $this->assertSame($newLocale, $locale);
    }

    /**
     * @return void
     */
    public function testSetCurrentLocaleInvalid(): void
    {
        $this->expectException('InvalidArgumentException');
        $newLocale = 'xy_XY';
        $this->Store->setCurrentLocale($newLocale);
    }

    /**
     * @return void
     */
    public function testInitializeSetupWhenMultipleCurrenciesNotDefinedShouldUseDefault(): void
    {
        $mockConfig = [
            'DE' => [
                'locales' => [
                    'en' => 'en_US',
                ],
                'countries' => [
                    'DE',
                ],
                'currencyIsoCode' => 'EUR',
            ],
        ];

        $storeMock = $this->createStoreMock();
        $storeMock->method('getStoreSetup')
           ->willReturn($mockConfig);

        $storeMock->initializeSetup('DE');

        $this->assertSame($mockConfig['DE']['currencyIsoCode'], $storeMock->getCurrencyIsoCode());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Store
     */
    protected function createStoreMock(): Store
    {
        return $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStoreSetup'])
            ->getMock();
    }
}
