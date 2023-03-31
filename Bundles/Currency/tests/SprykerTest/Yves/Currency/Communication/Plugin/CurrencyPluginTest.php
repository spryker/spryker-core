<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Currency\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Currency\CurrencyDependencyProvider;
use Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface;
use Spryker\Client\Session\SessionClient;
use Spryker\Yves\Currency\Plugin\CurrencyPlugin;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Currency
 * @group Plugin
 * @group CurrencyPluginTest
 * Add your own group annotations below this line
 */
class CurrencyPluginTest extends Unit
{
    use ContainerHelperTrait;

    /**
     * @var string
     */
    public const CURRENCY_EUR = 'EUR';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var \SprykerTest\Yves\Currency\CurrencyCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupSession();
        $this->tester->setDependency(CurrencyDependencyProvider::CLIENT_STORE, $this->createCurrencyToStoreClientMock());
    }

    /**
     * @return void
     */
    public function testFromIsoCodeShouldReturnCurrencyTransfer(): void
    {
        $currencyPlugin = new CurrencyPlugin();
        $currencyTransfer = $currencyPlugin->fromIsoCode('EUR');
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
    }

    /**
     * @return void
     */
    public function testGetDefaultShouldReturnCurrencyTransfer(): void
    {
        $currencyPlugin = new CurrencyPlugin();
        $currencyTransfer = $currencyPlugin->getCurrent();
        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
    }

    /**
     * @return void
     */
    protected function setupSession(): void
    {
        $sessionContainer = new Session(new MockArraySessionStorage());
        $sessionClient = new SessionClient();
        $sessionClient->setContainer($sessionContainer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Currency\Dependency\Client\CurrencyToStoreClientInterface
     */
    protected function createCurrencyToStoreClientMock(): CurrencyToStoreClientInterface
    {
        $currencyToStoreClientMock = $this->createMock(CurrencyToStoreClientInterface::class);
        $currencyToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())
                ->setName(static::DEFAULT_STORE)
                ->setAvailableCurrencyIsoCodes([static::CURRENCY_EUR]));

        return $currencyToStoreClientMock;
    }
}
