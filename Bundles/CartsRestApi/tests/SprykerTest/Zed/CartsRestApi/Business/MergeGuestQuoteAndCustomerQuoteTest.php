<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApisRestApi\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CartsRestApisRestApi
 * @group Business
 * @group MergeGuestQuoteAndCustomerQuoteTest
 * Add your own group annotations below this line
 */
class MergeGuestQuoteAndCustomerQuoteTest extends Unit
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
    public function testGuestQuoteAndCustomerQuoteWillBeMerged()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $guestQuoteTransfer = $this->tester->prepareQuoteTransferForGuest();
        $customerQuoteTransfer = $this->tester->buildQuoteTransfer($customerTransfer);
        $createGuestQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($guestQuoteTransfer);
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($customerQuoteTransfer);
        $quoteTransfer = $createQuoteResponseTransfer->getQuoteTransfer();
        $oauthResponseTransfer = $this->tester->buildOauthResponseTransfer($customerTransfer->getCustomerReference());

        // Act
        $this->cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $this->cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());
        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        // Assert
        $this->assertTrue($findQuoteResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($findQuoteResponseTransfer->getQuoteTransfer()->getItems());
        $this->assertNotEquals(
            $findQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
            $createGuestQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference()
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
    public function testGuestQuoteAndCustomerQuoteWillNotBeMergedWithoutCustomerReference()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $guestQuoteTransfer = $this->tester->prepareQuoteTransferForGuest();
        $customerQuoteTransfer = $this->tester->buildQuoteTransfer($customerTransfer);
        $createQuestQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($guestQuoteTransfer);
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($customerQuoteTransfer);
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransferWithoutCustomerReference();

        // Act
        $this->cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $findGuestQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($createQuestQuoteResponseTransfer->getQuoteTransfer());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($createQuoteResponseTransfer->getQuoteTransfer());

        // Assert
        $this->assertEmpty($findQuoteResponseTransfer->getQuoteTransfer()->getItems());
        $this->assertTrue(
            $findGuestQuoteResponseTransfer->getIsSuccessful()
        );
    }

    /**
     * @return void
     */
    public function testGuestQuoteAndCustomerQuoteWillNotBeMergedWithoutAnonymousCustomerReference()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $guestQuoteTransfer = $this->tester->prepareQuoteTransferForGuest();
        $customerQuoteTransfer = $this->tester->buildQuoteTransfer($customerTransfer);
        $createQuestQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($guestQuoteTransfer);
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($customerQuoteTransfer);
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransferWithoutAnonymousCustomerReference();

        // Act
        $this->cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $findGuestQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($createQuestQuoteResponseTransfer->getQuoteTransfer());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($createQuoteResponseTransfer->getQuoteTransfer());

        // Assert
        $this->assertEmpty($findQuoteResponseTransfer->getQuoteTransfer()->getItems());
        $this->assertTrue(
            $findGuestQuoteResponseTransfer->getIsSuccessful()
        );
    }

    /**
     * @return void
     */
    public function testEmptyGuestQuoteAndCustomerQuoteWillNotBeMerged()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $guestQuoteTransfer = $this->tester->prepareEmptyQuoteTransferForGuest();
        $customerQuoteTransfer = $this->tester->buildQuoteTransfer($customerTransfer);
        $createQuestQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($guestQuoteTransfer);
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($customerQuoteTransfer);
        $oauthResponseTransfer = $this->tester->buildOauthResponseTransfer($customerTransfer->getCustomerReference());

        // Act
        $this->cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $findGuestQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($createQuestQuoteResponseTransfer->getQuoteTransfer());

        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($createQuoteResponseTransfer->getQuoteTransfer());

        // Assert
        $this->assertEmpty($findQuoteResponseTransfer->getQuoteTransfer()->getItems());
        $this->assertTrue(
            $findGuestQuoteResponseTransfer->getIsSuccessful()
        );
    }
}
