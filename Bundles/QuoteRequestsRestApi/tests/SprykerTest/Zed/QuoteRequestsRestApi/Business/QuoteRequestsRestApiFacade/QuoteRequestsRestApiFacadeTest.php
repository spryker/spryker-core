<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequestsRestApi\Business\QuoteRequestsRestApiFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequestsRestApi
 * @group Business
 * @group QuoteRequestsRestApiFacade
 * @group Facade
 * @group QuoteRequestsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class QuoteRequestsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\QuoteRequestsRestApi\QuoteRequestsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->quoteTransfer = $this->tester->createQuoteTransfer();
    }

    /**
     * @return void
     */
    public function testCreateQuoteRequestCreatesQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfer = (new QuoteRequestBuilder())->build()
            ->setCompanyUser($this->quoteTransfer->getCustomer()->getCompanyUserTransfer())
            ->setLatestVersion($this->tester->createQuoteRequestVersion($this->quoteTransfer));
        // Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()->createQuoteRequest($quoteRequestTransfer);
        $storedQuoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEquals($quoteRequestTransfer->getCompanyUser(), $storedQuoteRequestTransfer->getCompanyUser());
        $this->assertSame(SharedQuoteRequestConfig::STATUS_DRAFT, $storedQuoteRequestTransfer->getStatus());
        $this->assertEquals(
            $quoteRequestTransfer->getLatestVersion()->getQuote(),
            $storedQuoteRequestTransfer->getLatestVersion()->getQuote(),
        );
    }
}
