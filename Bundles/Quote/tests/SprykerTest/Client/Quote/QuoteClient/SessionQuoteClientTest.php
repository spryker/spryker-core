<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Quote\QuoteClient;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface;
use Spryker\Client\Quote\QuoteClient;
use Spryker\Client\Quote\QuoteDependencyProvider;
use Spryker\Client\Session\SessionClient;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Quote
 * @group QuoteClient
 * @group SessionQuoteClientTest
 * Add your own group annotations below this line
 */
class SessionQuoteClientTest extends Unit
{
    use DependencyHelperTrait;

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function setUp(): void
    {
        $sessionContainer = new Session(new MockArraySessionStorage());
        $sessionClient = new SessionClient();
        $sessionClient->setContainer($sessionContainer);

        $this->getDependencyHelper()->setDependency(QuoteDependencyProvider::CLIENT_CURRENCY, $this->createQuoteToCurrencyClientInterface());
    }

    /**
     * @return void
     */
    public function testGetQuoteShouldReturnQuoteTransfer(): void
    {
        $quoteClient = new QuoteClient();

        $this->assertInstanceOf(QuoteTransfer::class, $quoteClient->getQuote());
    }

    /**
     * @return void
     */
    public function testSetQuoteShouldStoreQuoteTransfer(): void
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteClient = new QuoteClient();

        $quoteClient->setQuote($quoteTransfer);
        $this->assertSame($quoteTransfer, $quoteClient->getQuote());
    }

    /**
     * @return void
     */
    public function testClearQuoteShouldSetEmptyQuoteTransfer(): void
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem(new ItemTransfer());

        $quoteClient = new QuoteClient();
        $quoteClient->setQuote($quoteTransfer);
        $quoteClient->clearQuote();

        $this->assertNotSame($quoteTransfer, $quoteClient->getQuote());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface
     */
    protected function createQuoteToCurrencyClientInterface(): QuoteToCurrencyClientInterface
    {
        $quoteToCurrencyClientMock = $this->createMock(QuoteToCurrencyClientInterface::class);
        $quoteToCurrencyClientMock->method('getCurrent')
            ->willReturn((new CurrencyTransfer())
                ->setCode(static::DEFAULT_CURRENCY));

        return $quoteToCurrencyClientMock;
    }
}
