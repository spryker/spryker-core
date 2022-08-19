<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiBusinessFactory;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface;
use Spryker\Zed\CartsRestApi\CartsRestApiConfig;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartsRestApi
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
    protected $cartsRestApiFacade;

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
    public function testGuestQuoteAndCustomerQuoteWillBeMerged(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $customerQuoteTransfer = $this->tester->buildQuoteTransfer($customerTransfer);
        $createGuestQuoteResponseTransfer = $this->cartsRestApiFacade
            ->createQuote($this->tester->prepareQuoteTransferForGuest());
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
            $createGuestQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference(),
        );
        $this->assertEmpty($findQuoteResponseTransfer->getErrors());
        $this->assertEmpty($guestQuoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testGuestQuoteAndCustomerQuoteWillNotBeMergedWithoutCustomerReference(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $customerQuoteTransfer = $this->tester->buildQuoteTransfer($customerTransfer);
        $createQuestQuoteResponseTransfer = $this->cartsRestApiFacade
            ->createQuote($this->tester->prepareQuoteTransferForGuest());
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($customerQuoteTransfer);
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransferWithoutCustomerReference();

        // Act
        $this->cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $findGuestQuoteResponseTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid($createQuestQuoteResponseTransfer->getQuoteTransfer());
        $findQuoteResponseTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid($createQuoteResponseTransfer->getQuoteTransfer());

        // Assert
        $this->assertEmpty($findQuoteResponseTransfer->getQuoteTransfer()->getItems());
        $this->assertTrue($findGuestQuoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGuestQuoteAndCustomerQuoteWillNotBeMergedWithoutAnonymousCustomerReference(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $customerQuoteTransfer = $this->tester->buildQuoteTransfer($customerTransfer);
        $createQuestQuoteResponseTransfer = $this->cartsRestApiFacade
            ->createQuote($this->tester->prepareQuoteTransferForGuest());
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($customerQuoteTransfer);
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransferWithoutAnonymousCustomerReference();

        // Act
        $this->cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $findGuestQuoteResponseTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid($createQuestQuoteResponseTransfer->getQuoteTransfer());
        $findQuoteResponseTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid($createQuoteResponseTransfer->getQuoteTransfer());

        // Assert
        $this->assertEmpty($findQuoteResponseTransfer->getQuoteTransfer()->getItems());
        $this->assertTrue($findGuestQuoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testEmptyGuestQuoteAndCustomerQuoteWillNotBeMerged(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $customerQuoteTransfer = $this->tester->buildQuoteTransfer($customerTransfer);
        $createQuestQuoteResponseTransfer = $this->cartsRestApiFacade
            ->createQuote($this->tester->prepareEmptyQuoteTransferForGuest());
        $createQuoteResponseTransfer = $this->cartsRestApiFacade->createQuote($customerQuoteTransfer);
        $oauthResponseTransfer = $this->tester->buildOauthResponseTransfer($customerTransfer->getCustomerReference());

        // Act
        $this->cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $findGuestQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($createQuestQuoteResponseTransfer->getQuoteTransfer());
        $findQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($createQuoteResponseTransfer->getQuoteTransfer());

        // Assert
        $this->assertEmpty($findQuoteResponseTransfer->getQuoteTransfer()->getItems());
        $this->assertTrue($findGuestQuoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCustomerQuoteWillBeCreatedIfNotExistsByEnableMergingWithGuestQuote(): void
    {
        // Arrange
        $this->tester->setDependency(
            QuoteDependencyProvider::PLUGINS_QUOTE_CREATE_BEFORE,
            [$this->getAddDefaultNameBeforeQuoteSavePluginMock()],
        );
        $cartsRestApiFacade = $this->createCartsRestApiFacadeWithMockedConfig(true);

        $customerTransfer = $this->tester->haveCustomer();
        $cartsRestApiFacade->createQuote($this->tester->prepareQuoteTransferForGuest());
        $oauthResponseTransfer = $this->tester->buildOauthResponseTransfer($customerTransfer->getCustomerReference());

        // Act
        $cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());

        $quoteCriteriaFilterTransfer = $this->tester
            ->createQuoteCriteriaFilterTransfer($oauthResponseTransfer->getCustomerReference());
        $customerQuoteCollectionTransfer = $cartsRestApiFacade->getQuoteCollection($quoteCriteriaFilterTransfer);

        // Assert
        $this->assertNotEmpty($customerQuoteCollectionTransfer->getQuotes());
        $this->assertEmpty($guestQuoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testCustomerQuoteWillNotBeCreatedIfNotExistsByDisableMergingWithGuestQuote(): void
    {
        // Arrange
        $cartsRestApiFacade = $this->createCartsRestApiFacadeWithMockedConfig(false);

        $customerTransfer = $this->tester->haveCustomer();
        $cartsRestApiFacade->createQuote($this->tester->prepareQuoteTransferForGuest());
        $oauthResponseTransfer = $this->tester->buildOauthResponseTransfer($customerTransfer->getCustomerReference());

        // Act
        $cartsRestApiFacade->mergeGuestQuoteAndCustomerQuote($oauthResponseTransfer);
        $guestQuoteCollectionTransfer = $cartsRestApiFacade
            ->getQuoteCollection($this->tester->prepareQuoteCriteriaFilterTransferForGuest());

        $quoteCriteriaFilterTransfer = $this->tester
            ->createQuoteCriteriaFilterTransfer($oauthResponseTransfer->getCustomerReference());
        $customerQuoteCollectionTransfer = $cartsRestApiFacade->getQuoteCollection($quoteCriteriaFilterTransfer);

        // Assert
        $this->assertEmpty($customerQuoteCollectionTransfer->getQuotes());
        $this->assertNotEmpty($guestQuoteCollectionTransfer->getQuotes());
    }

    /**
     * @param bool $isQuoteCreationWhileQuoteMergingEnabled
     *
     * @return \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface
     */
    protected function createCartsRestApiFacadeWithMockedConfig(
        bool $isQuoteCreationWhileQuoteMergingEnabled
    ): CartsRestApiFacadeInterface {
        $cartRestApiConfigMock = $this->getCartRestApiConfigMock($isQuoteCreationWhileQuoteMergingEnabled);
        $cartsRestApiBusinessFactory = new CartsRestApiBusinessFactory();
        $cartsRestApiBusinessFactory->setConfig($cartRestApiConfigMock);

        $cartsRestApiFacade = new CartsRestApiFacade();
        $cartsRestApiFacade->setFactory($cartsRestApiBusinessFactory);

        return $cartsRestApiFacade;
    }

    /**
     * @param bool $isQuoteCreationWhileQuoteMergingEnabled
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CartsRestApi\CartsRestApiConfig
     */
    protected function getCartRestApiConfigMock(bool $isQuoteCreationWhileQuoteMergingEnabled): CartsRestApiConfig
    {
        $configMock = Stub::make(CartsRestApiConfig::class, [
            'isQuoteCreationWhileQuoteMergingEnabled' => function () use ($isQuoteCreationWhileQuoteMergingEnabled) {
                return $isQuoteCreationWhileQuoteMergingEnabled;
            },
        ]);

        return $configMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface
     */
    protected function getAddDefaultNameBeforeQuoteSavePluginMock(): QuoteWritePluginInterface
    {
        $addDefaultNameBeforeQuoteSavePluginMock = Stub::makeEmpty(QuoteWritePluginInterface::class, [
            'execute' => function (QuoteTransfer $quoteTransfer) {
                if (!$quoteTransfer->getName()) {
                    $quoteTransfer->setName('Shopping Cart Test');
                }

                return $quoteTransfer;
            },
        ]);

        return $addDefaultNameBeforeQuoteSavePluginMock;
    }
}
