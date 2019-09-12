<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApisRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CartsRestApisRestApi
 * @group Business
 * @group ConvertGuestQuoteToCustomerQuoteTest
 * Add your own group annotations below this line
 */
class ConvertGuestQuoteToCustomerQuoteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CartsRestApi\CartsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface
     */
    private $cartsRestApiFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->cartsRestApiFacade = new CartsRestApiFacade();
    }

    /**
     * @return void
     */
    public function testGuestQuoteWillBeConvertedToCustomerQuote()
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferForGuest();
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($quoteTransfer);
        $customerTransfer = $this->tester->haveCustomer();
        $customerReference = $customerTransfer->getCustomerReference();
        $oauthResponseTransfer = (new OauthResponseTransfer())
            ->setCustomerReference($customerReference)
            ->setAnonymousCustomerReference($this->tester::TEST_ANONYMOUS_CUSTOMER_REFERENCE);

        $customerQuoteCollectionTransfer1 = $this->cartsRestApiFacade->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())->setCustomerReference($customerReference)
        );

        // Act
        $this->cartsRestApiFacade->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);

        $customerQuoteCollectionTransfer2 = $this->cartsRestApiFacade->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())->setCustomerReference($customerReference)
        );

        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid(
            (new QuoteTransfer())
                ->setCustomerReference($customerReference)
                ->setCustomer($customerTransfer)
                ->setUuid($createQuoteResponseTransfer->getQuoteTransfer()->getUuid())
        );

        // Assert
        $this->assertTrue($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertGreaterThan(
            $customerQuoteCollectionTransfer1->getQuotes()->count(),
            $customerQuoteCollectionTransfer2->getQuotes()->count()
        );
        $this->assertNotEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $createQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
        );
        $this->assertEmpty(
            $findQuoteResponseTransfer->getErrors()
        );
        $this->assertEmpty(
            $guestQuoteCollectionTransfer->getQuotes()
        );
    }

    /**
     * @return void
     */
    public function testEmptyGuestQuoteWillNotBeConvertedToCustomerQuote()
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareEmptyQuoteTransferForGuest();
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($quoteTransfer);
        $customerTransfer = $this->tester->haveCustomer();
        $oauthResponseTransfer = (new OauthResponseTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setAnonymousCustomerReference($this->tester::TEST_ANONYMOUS_CUSTOMER_REFERENCE);

        // Act
        $this->cartsRestApiFacade->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid(
            (new QuoteTransfer())
                ->setCustomerReference($customerTransfer->getCustomerReference())
                ->setCustomer($customerTransfer)
                ->setUuid($createQuoteResponseTransfer->getQuoteTransfer()->getUuid())
        );

        // Assert
        $this->assertFalse($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $createQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
        );
        $this->assertNotEmpty(
            $findQuoteResponseTransfer->getErrors()
        );
        $this->assertNotEmpty(
            $guestQuoteCollectionTransfer->getQuotes()
        );
    }

    /**
     * @return void
     */
    public function testGuestQuoteWillNotBeConvertedToCustomerQuoteWithoutAnonymousCustomerReference(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferForGuest();
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($quoteTransfer);
        $customerTransfer = $this->tester->haveCustomer();
        $oauthResponseTransfer = (new OauthResponseTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());

        // Act
        $this->cartsRestApiFacade->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid(
            (new QuoteTransfer())
                ->setCustomerReference($customerTransfer->getCustomerReference())
                ->setCustomer($customerTransfer)
                ->setUuid($createQuoteResponseTransfer->getQuoteTransfer()->getUuid())
        );

        // Assert
        $this->assertFalse($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $createQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
        );
        $this->assertNotEmpty(
            $findQuoteResponseTransfer->getErrors()
        );
        $this->assertNotEmpty(
            $guestQuoteCollectionTransfer->getQuotes()
        );
    }

    /**
     * @return void
     */
    public function testGuestQuoteWillNotBeConvertedToCustomerQuoteWithoutCustomerReference(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferForGuest();
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($quoteTransfer);
        $customerTransfer = $this->tester->haveCustomer();
        $oauthResponseTransfer = (new OauthResponseTransfer())
            ->setAnonymousCustomerReference($this->tester::TEST_ANONYMOUS_CUSTOMER_REFERENCE);

        // Act
        $this->cartsRestApiFacade->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid(
            (new QuoteTransfer())
                ->setCustomerReference($customerTransfer->getCustomerReference())
                ->setCustomer($customerTransfer)
                ->setUuid($createQuoteResponseTransfer->getQuoteTransfer()->getUuid())
        );

        // Assert
        $this->assertFalse($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $createQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
        );
        $this->assertNotEmpty(
            $findQuoteResponseTransfer->getErrors()
        );
        $this->assertNotEmpty(
            $guestQuoteCollectionTransfer->getQuotes()
        );
    }
}
