<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiBusinessFactory;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeBridge;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeBridge;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeBridge;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeBridge;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\PersistentCart\Business\PersistentCartFacade;
use Spryker\Zed\Quote\Business\QuoteFacade;
use Spryker\Zed\Store\Business\StoreFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CartsRestApi
 * @group Business
 * @group Facade
 * @group CartsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CartsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CartsRestApi\CartsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCartsFacadeWillFindQuoteByUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $actualQuoteResponseTransfer = $cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $actualQuoteResponseTransfer);
        $this->assertNotNull($actualQuoteResponseTransfer->getQuoteTransfer());
        $this->assertInstanceOf(QuoteTransfer::class, $actualQuoteResponseTransfer->getQuoteTransfer());
        $this->assertEquals($quoteTransfer->getCustomerReference(), $actualQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference());
        $this->assertEquals($quoteTransfer->getUuid(), $actualQuoteResponseTransfer->getQuoteTransfer()->getUuid());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotFindQuoteByUuidWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomerReference();

        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotFindQuoteByUuidWithoutCartUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCartUuid();

        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteCollectionWillBeRetrievedNonEmpty(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteCriteriaFilterTransfer = $this->tester->prepareQuoteCriteriaFilterTransfer();
        $quoteCollectionTransfer = $cartsRestApiFacade->getQuoteCollection($quoteCriteriaFilterTransfer);

        $this->assertNotEmpty($quoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testQuoteWillBeCreatedSuccessfully(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteTransfer->setCustomer((new CustomerTransfer())->setCustomerReference($quoteTransfer->getCustomerReference()));

        $quoteResponseTransfer = $cartsRestApiFacade->createQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testQuoteWillNotBeCreatedWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->createQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCreateSingleQuoteWillNotAllowCreateMoreThanOneQuote(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $cartsRestApiFacade->createSingleQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testQuoteWillBeUpdatedSuccessfully(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteTransfer->setCustomer((new CustomerTransfer())->setCustomerReference($quoteTransfer->getCustomerReference()));

        $quoteResponseTransfer = $cartsRestApiFacade->updateQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testQuoteWillNotBeUpdatedWithoutUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCartUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteWillNotBeUpdatedWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteWillBeDeleteSuccessfully(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteTransfer->setCustomer((new CustomerTransfer())->setCustomerReference($quoteTransfer->getCustomerReference()));

        $quoteResponseTransfer = $cartsRestApiFacade->deleteQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testQuoteWillNotBeDeleteWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->deleteQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testQuoteWillNotBeDeleteWithoutUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCartUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->deleteQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillBeAddedSuccessfully(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithQuantity();

        $quoteResponseTransfer = $cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testItemWillNotBeAddedWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeAddedWithoutSku(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeAddedWithoutUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeAddedWithoutQuantity(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutQuantity();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillBeUpdatedSuccessfully(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithQuantity();

        $quoteResponseTransfer = $cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testItemWillNotBeUpdatedWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeUpdatedWithoutSku(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeUpdatedWithoutUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeUpdatedWithoutQuantity(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutQuantity();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillBeDeletedSuccessfully(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithQuantity();

        $quoteResponseTransfer = $cartsRestApiFacade->deleteItem($restCartItemsAttributesTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testItemWillNotBeDeletedWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->deleteItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeDeletedWithoutSku(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->deleteItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeDeletedWithoutUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->deleteItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillBeAddedToGuestCartSuccessfully(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithQuantity();

        $quoteResponseTransfer = $cartsRestApiFacade->addItemToGuestCart($restCartItemsAttributesTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testItemWillNotBeAddedToGuestCartWithoutSku(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addItemToGuestCart($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testItemWillNotBeAddedToGuestCartWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addItemToGuestCart($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testGuestCartWillBeAssignedToRegisteredCustomerSuccessfully(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $assignGuestQuoteRequestTransfer = $this->tester->prepareAssignGuestQuoteRequestTransfer();

        $quoteResponseTransfer = $cartsRestApiFacade->assignGuestCartToRegisteredCustomer($assignGuestQuoteRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGuestCartWillNotBeAssignedToRegisteredCustomerWithoutCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $assignGuestQuoteRequestTransfer = $this->tester->prepareAssignGuestQuoteRequestTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->assignGuestCartToRegisteredCustomer($assignGuestQuoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGuestCartWillNotBeAssignedToRegisteredCustomerWithoutAnonymousCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $assignGuestQuoteRequestTransfer = $this->tester->prepareAssignGuestQuoteRequestTransferWithoutAnonymousCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->assignGuestCartToRegisteredCustomer($assignGuestQuoteRequestTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCartsRestApiBusinessFactory(): MockObject
    {
        $cartsRestApiBusinessFactoryMock = $this->createPartialMock(
            CartsRestApiBusinessFactory::class,
            [
                'getQuoteFacade',
                'getStoreFacade',
                'getCartFacade',
                'getPersistentCartFacade',
                'getQuoteCreatorPlugin',
            ]
        );

        $cartsRestApiBusinessFactoryMock = $this->addMockQuoteFacade($cartsRestApiBusinessFactoryMock);
        $cartsRestApiBusinessFactoryMock = $this->addMockCartFacade($cartsRestApiBusinessFactoryMock);
        $cartsRestApiBusinessFactoryMock = $this->addMockPersistentCartFacade($cartsRestApiBusinessFactoryMock);
        $cartsRestApiBusinessFactoryMock = $this->addMockStoreFacade($cartsRestApiBusinessFactoryMock);
        $cartsRestApiBusinessFactoryMock = $this->addQuoteCreatorPlugin($cartsRestApiBusinessFactoryMock);

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockQuoteFacade(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $quoteFacadeMock = $this->createPartialMock(
            QuoteFacade::class,
            [
                'findQuoteByUuid',
                'getQuoteCollection',
            ]
        );

        $quoteFacadeMock->method('findQuoteByUuid')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());
        $quoteFacadeMock->method('getQuoteCollection')
            ->willReturn($this->tester->prepareQuotesCollectionTransfer());
        $cartsRestApiBusinessFactoryMock->method('getQuoteFacade')
            ->willReturn((new CartsRestApiToQuoteFacadeBridge($quoteFacadeMock)));

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartFacade(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $cartFacadeMock = $this->createPartialMock(
            CartFacade::class,
            [
                'reloadItems',
                'addToQuote',
            ]
        );

        $cartFacadeMock->method('reloadItems')
            ->willReturn($this->tester->prepareQuoteTransfer());
        $cartFacadeMock->method('addToQuote')
            ->willReturn($this->tester->prepareQuoteResponseTransfer());
        $cartsRestApiBusinessFactoryMock->method('getCartFacade')
            ->willReturn((new CartsRestApiToCartFacadeBridge($cartFacadeMock)));

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockPersistentCartFacade(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $persistentCartFacadeMock = $this->createPartialMock(
            PersistentCartFacade::class,
            [
                'updateQuote',
                'deleteQuote',
                'changeItemQuantity',
                'remove',
            ]
        );

        $persistentCartFacadeMock->method('updateQuote')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());
        $persistentCartFacadeMock->method('deleteQuote')
            ->willReturn($this->tester->prepareQuoteResponseTransfer());
        $persistentCartFacadeMock->method('changeItemQuantity')
            ->willReturn($this->tester->prepareQuoteResponseTransfer());
        $persistentCartFacadeMock->method('remove')
            ->willReturn($this->tester->prepareQuoteResponseTransfer());
        $cartsRestApiBusinessFactoryMock->method('getPersistentCartFacade')
            ->willReturn((new CartsRestApiToPersistentCartFacadeBridge($persistentCartFacadeMock)));

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockStoreFacade(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $storeFacadeMock = $this->createPartialMock(
            StoreFacade::class,
            [
                'getCurrentStore',
            ]
        );

        $storeFacadeMock
            ->method('getCurrentStore')
            ->willReturn(new StoreTransfer());

        $cartsRestApiBusinessFactoryMock
            ->method('getStoreFacade')
            ->willReturn(new CartsRestApiToStoreFacadeBridge($storeFacadeMock));

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $cartsRestApiBusinessFactoryMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addQuoteCreatorPlugin(MockObject $cartsRestApiBusinessFactoryMock): MockObject
    {
        $cartsRestApiBusinessFactoryMock->method('getQuoteCreatorPlugin')
            ->willReturn($this->createMockQuoteCreatorPlugin());

        return $cartsRestApiBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockQuoteCreatorPlugin(): MockObject
    {
        $mockQuoteCreatorPlugin = $this->createPartialMock(
            QuoteCreatorPluginInterface::class,
            ['createQuote']
        );
        $mockQuoteCreatorPlugin
            ->method('createQuote')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());

        return $mockQuoteCreatorPlugin;
    }
}
