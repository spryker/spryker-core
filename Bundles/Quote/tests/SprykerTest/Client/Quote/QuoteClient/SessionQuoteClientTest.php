<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Quote\QuoteClient;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\QuoteClient;
use Spryker\Client\Session\SessionClient;
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
    /**
     * @return void
     */
    public function setUp()
    {
        $sessionContainer = new Session(new MockArraySessionStorage());
        $sessionClient = new SessionClient();
        $sessionClient->setContainer($sessionContainer);
    }

    /**
     * @return void
     */
    public function testGetQuoteShouldReturnQuoteTransfer()
    {
        $quoteClient = new QuoteClient();

        $this->assertInstanceOf(QuoteTransfer::class, $quoteClient->getQuote());
    }

    /**
     * @return void
     */
    public function testSetQuoteShouldStoreQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteClient = new QuoteClient();

        $quoteClient->setQuote($quoteTransfer);
        $this->assertSame($quoteTransfer, $quoteClient->getQuote());
    }

    /**
     * @return void
     */
    public function testClearQuoteShouldSetEmptyQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem(new ItemTransfer());

        $quoteClient = new QuoteClient();
        $quoteClient->setQuote($quoteTransfer);
        $quoteClient->clearQuote();

        $this->assertNotSame($quoteTransfer, $quoteClient->getQuote());
    }
}
