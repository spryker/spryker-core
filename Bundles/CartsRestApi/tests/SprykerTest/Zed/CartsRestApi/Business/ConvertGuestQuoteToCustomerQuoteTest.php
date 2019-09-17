<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApisRestApi\Business;

use Codeception\Test\Unit;

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
    public function setUp(): void
    {
        parent::setUp();

        $this->cartsRestApiFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testGuestQuoteWillBeConvertedToCustomerQuote(): void
    {
        // Arrange
        $createQuoteResponseTransfer = $this->cartsRestApiFacade
            ->createQuote($this->tester->prepareQuoteTransferForGuest());
        $quoteTransfer = $createQuoteResponseTransfer->getQuoteTransfer();
        $customerTransfer = $this->tester->haveCustomer();
        $customerReference = $customerTransfer->getCustomerReference();
        $oauthResponseTransfer = $this->tester->buildOauthResponseTransfer($customerReference);

        $customerQuoteCollectionTransferBeforeAct = $this->cartsRestApiFacade->getQuoteCollection(
            $this->tester->buildQuoteCriteriaFilterTransfer($customerReference)
        );

        // Act
        $this->cartsRestApiFacade->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);
        $customerQuoteCollectionTransferAfterAct = $this->cartsRestApiFacade->getQuoteCollection(
            $this->tester->buildQuoteCriteriaFilterTransfer($customerReference)
        );
        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());
        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid(
            $this->tester->buildQuoteTransfer($customerTransfer)->setUuid($quoteTransfer->getUuid())
        );

        // Assert
        $this->assertTrue($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertGreaterThan(
            $customerQuoteCollectionTransferBeforeAct->getQuotes()->count(),
            $customerQuoteCollectionTransferAfterAct->getQuotes()->count()
        );
        $this->assertNotEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $createQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
        );
        $this->assertEmpty($findQuoteResponseTransfer->getErrors());
        $this->assertEmpty($guestQuoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testEmptyGuestQuoteWillNotBeConvertedToCustomerQuote(): void
    {
        // Arrange
        $createQuoteResponseTransfer = $this->cartsRestApiFacade
            ->createQuote($this->tester->prepareEmptyQuoteTransferForGuest());
        $quoteTransfer = $createQuoteResponseTransfer->getQuoteTransfer();
        $customerTransfer = $this->tester->haveCustomer();
        $oauthResponseTransfer = $this->tester->buildOauthResponseTransfer($customerTransfer->getCustomerReference());

        // Act
        $this->cartsRestApiFacade->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid(
            $this->tester->buildQuoteTransfer($customerTransfer)->setUuid($quoteTransfer->getUuid())
        );

        // Assert
        $this->assertFalse($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $createQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
        );
        $this->assertNotEmpty($findQuoteResponseTransfer->getErrors());
        $this->assertNotEmpty($guestQuoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testGuestQuoteWillNotBeConvertedToCustomerQuoteWithoutAnonymousCustomerReference(): void
    {
        // Arrange
        $createQuoteResponseTransfer = $this->cartsRestApiFacade
            ->createQuote($this->tester->prepareQuoteTransferForGuest());
        $quoteTransfer = $createQuoteResponseTransfer->getQuoteTransfer();
        $customerTransfer = $this->tester->haveCustomer();
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransferWithoutAnonymousCustomerReference();

        // Act
        $this->cartsRestApiFacade->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid(
            $this->tester->buildQuoteTransfer($customerTransfer)->setUuid($quoteTransfer->getUuid())
        );

        // Assert
        $this->assertFalse($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $createQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
        );
        $this->assertNotEmpty($findQuoteResponseTransfer->getErrors());
        $this->assertNotEmpty($guestQuoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testGuestQuoteWillNotBeConvertedToCustomerQuoteWithoutCustomerReference(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->prepareQuoteTransferForGuest();
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($quoteTransfer);
        $quoteTransfer = $createQuoteResponseTransfer->getQuoteTransfer();
        $customerTransfer = $this->tester->haveCustomer();
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransferWithoutCustomerReference();

        // Act
        $this->cartsRestApiFacade->convertGuestQuoteToCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());
        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid(
            $this->tester->buildQuoteTransfer($customerTransfer)->setUuid($quoteTransfer->getUuid())
        );

        // Assert
        $this->assertFalse($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $quoteTransfer->getCustomerReference()
        );
        $this->assertNotEmpty($findQuoteResponseTransfer->getErrors());
        $this->assertNotEmpty($guestQuoteCollectionTransfer->getQuotes());
    }
}
