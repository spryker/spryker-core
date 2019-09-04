<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartsRestApi\Business;

use Codeception\Test\Unit;
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
        $this->cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillFindQuoteByUuid(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $actualQuoteResponseTransfer = $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $actualQuoteResponseTransfer);
        $this->assertNotNull($actualQuoteResponseTransfer->getQuoteTransfer());
        $this->assertInstanceOf(QuoteTransfer::class, $actualQuoteResponseTransfer->getQuoteTransfer());
        $this->assertEquals($quoteTransfer->getCustomerReference(), $actualQuoteResponseTransfer->getQuoteTransfer()->getCustomerReference());
        $this->assertEquals($quoteTransfer->getUuid(), $actualQuoteResponseTransfer->getQuoteTransfer()->getUuid());
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotFindQuoteByUuidWithoutCartUuid(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCartUuid();

        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCartsFacadeWillNotFindQuoteByUuidWithoutCustomerReference(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomerReference();

        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testGetQuoteCollectionWillReturnCollectionOfQuotes(): void
    {
        $quoteCriteriaFilterTransfer = $this->tester->prepareQuoteCriteriaFilterTransfer();
        $quoteCollectionTransfer = $this->cartsRestApiFacade->getQuoteCollection($quoteCriteriaFilterTransfer);

        $this->assertNotEmpty($quoteCollectionTransfer->getQuotes());
    }

    /**
     * @return void
     */
    public function testCreateQuoteWillCreateQuote(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $this->cartsRestApiFacade->createQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteWillNotCreateQuoteWithoutCustomer(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->createQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCreateSingleQuoteWillNotAllowCreateMoreThanOneQuote(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $this->cartsRestApiFacade->createSingleQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateQuoteWilUpdateQuote(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $this->cartsRestApiFacade->updateQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateQuoteWillNotAllowUpdateQuoteWithoutUuid(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCartUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateQuoteWillNotAllowUpdateQuoteWithoutCustomer(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteQuoteWillDeleteQuote(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $this->cartsRestApiFacade->deleteQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDeleteQuoteWillNotAllowDeleteQuoteWithoutCustomer(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->deleteQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteQuoteWillNotAllowDeleteQuoteWithoutUuid(): void
    {
        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCartUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->deleteQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testAddItemWillAddItem(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithQuantity();

        $quoteResponseTransfer = $this->cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAddItemWillNotAllowAddItemWithoutCustomerReference(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testAddItemWillNotAllowAddItemWithoutSku(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testAddItemWillNotAllowAddItemWithoutUuid(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testAddItemWillNotAllowAddItemWithoutQuantity(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutQuantity();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateItemWillUpdateItem(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithQuantity();

        $quoteResponseTransfer = $this->cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateItemWillNotAllowUpdateItemWithoutCustomerReference(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateItemWillNotAllowUpdateItemWithoutSku(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateItemWillNotAllowUpdateItemWithoutUuid(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateItemWillNotAllowUpdateItemWithoutQuantity(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutQuantity();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteItemWillDeleteItem(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithQuantity();

        $quoteResponseTransfer = $this->cartsRestApiFacade->deleteItem($restCartItemsAttributesTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDeleteItemWillNotAllowDeleteItemWithoutCustomerReference(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->deleteItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteItemWillNotAllowDeleteItemWithoutSku(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->deleteItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteItemWillNotAllowDeleteItemWithoutUuid(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->deleteItem($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testAddItemToGuestCartWillAddItemToGuest(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithQuantity();

        $quoteResponseTransfer = $this->cartsRestApiFacade->addItemToGuestCart($restCartItemsAttributesTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAddItemToGuestCartWillNotAllowAddItemToGuestCartWithoutSku(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addItemToGuestCart($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testAddItemToGuestCartWillNotAllowAddItemToGuestCartWithoutCustomerReference(): void
    {
        $restCartItemsAttributesTransfer = $this->tester->prepareRestCartItemsAttributesTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addItemToGuestCart($restCartItemsAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testAssignGuestCartToRegisteredCustomerWillAssignGuestCartToRegisteredCustomer(): void
    {
        $assignGuestQuoteRequestTransfer = $this->tester->prepareAssignGuestQuoteRequestTransfer();

        $quoteResponseTransfer = $this->cartsRestApiFacade->assignGuestCartToRegisteredCustomer($assignGuestQuoteRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAssignGuestCartToRegisteredCustomerWillNotAllowAssignWithoutCustomerReference(): void
    {
        $assignGuestQuoteRequestTransfer = $this->tester->prepareAssignGuestQuoteRequestTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->assignGuestCartToRegisteredCustomer($assignGuestQuoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAssignGuestCartToRegisteredCustomerWillNotAllowAssignWithoutAnonymousCustomerReference(): void
    {
        $assignGuestQuoteRequestTransfer = $this->tester->prepareAssignGuestQuoteRequestTransferWithoutAnonymousCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->assignGuestCartToRegisteredCustomer($assignGuestQuoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateItemQuantityWillUpdateItemQuantity(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithQuantity();
        $quoteResponseTransfer = $this->cartsRestApiFacade->updateItemQuantity($cartItemRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateItemQuantityWillNotAllowUpdateItemQuantityWithoutCustomer(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateItemQuantity($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateItemQuantityWillNotAllowUpdateItemQuantityWithoutQuantity(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutQuantity();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateItemQuantity($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToCartWillAddItemToCart(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithQuantity();
        $quoteResponseTransfer = $this->cartsRestApiFacade->addToCart($cartItemRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAddToCartWillNotAllowAddItemWithoutCustomer(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToCartWillNotAllowAddItemWithoutSku(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToCartWillNotAllowAddItemWithoutUuid(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToCartWillNotAllowAddItemWithoutQuantity(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutQuantity();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveItemWillRemoveItem(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithQuantity();

        $quoteResponseTransfer = $this->cartsRestApiFacade->removeItem($cartItemRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testRemoveItemWillNotAllowRemoveItemWithoutCustomer(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->removeItem($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveItemWillNotAllowRemoveItemWithoutSku(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->removeItem($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveItemWillNotAllowRemoveItemWithoutUuid(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutuuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->removeItem($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToGuestCartWillAddItemToGuest(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithQuantity();

        $quoteResponseTransfer = $this->cartsRestApiFacade->addToGuestCart($cartItemRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAddToGuestCartWillNotAllowAddItemToGuestCartWithoutSku(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addToGuestCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToGuestCartWillNotAllowAddItemToGuestCartWithoutCustomer(): void
    {
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addToGuestCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateGuestQuoteToCustomerQuoteWillUpdateGuestQuoteToCustomerQuote(): void
    {
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransfer();
        $quoteCriteriaFilterTransfer = $this->tester->prepareQuoteCriteriaFilterTransfer();
        $quoteTransferForGuest = $this->tester->prepareQuoteTransferForGuest();

        $quoteCollectionTransfer1 = $this->cartsRestApiFacade
            ->getQuoteCollection($quoteCriteriaFilterTransfer);

        $this->cartsRestApiFacade
            ->updateGuestQuoteToCustomerQuote($oauthResponseTransfer);

        $this->cartsRestApiFacade->createQuote($quoteTransferForGuest);

        $quoteCollectionTransfer2 = $this->cartsRestApiFacade
            ->getQuoteCollection($quoteCriteriaFilterTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateGuestQuoteToCustomerQuoteWillNotUpdateGuestQuoteToCustomerQuoteWithoutCustomerReference(): void
    {
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->updateGuestQuoteToCustomerQuote($oauthResponseTransfer);
    }

    /**
     * @return void
     */
    public function testAddGuestQuoteItemsToCustomerQuoteWillAddGuestQuoteItemsToCustomerQuote(): void
    {
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransferForGuest();

        $this->cartsRestApiFacade
            ->addGuestQuoteItemsToCustomerQuote($oauthResponseTransfer);

        $quoteResponseTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid($quoteTransfer);

        $this->assertEquals($quoteResponseTransfer->getQuoteTransfer()->getCustomerReference(), $oauthResponseTransfer->getCustomerReference());
        $this->assertNotEquals($quoteTransfer->getItems()->count(), $quoteResponseTransfer->getQuoteTransfer()->getItems()->count());
    }

    /**
     * @return void
     */
    public function testAddGuestQuoteItemsToCustomerQuoteWillNotAddGuestQuoteItemsToCustomerQuoteWithoutAnonymousCustomerReference(): void
    {
        $oauthResponseTransfer = $this->tester->prepareOauthResponseTransferWithoutCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $this->cartsRestApiFacade->addGuestQuoteItemsToCustomerQuote($oauthResponseTransfer);
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
                'getQuoteCollectionExpanderPlugins',
                'getQuoteExpanderPlugins',
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
                'addToCart',
            ]
        );

        $cartFacadeMock->method('reloadItems')
            ->willReturn($this->tester->prepareQuoteTransfer());
        $cartFacadeMock->method('addToCart')
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
                'add',
                'changeItemQuantity',
                'remove',
            ]
        );

        $persistentCartFacadeMock->method('updateQuote')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());
        $persistentCartFacadeMock->method('deleteQuote')
            ->willReturn($this->tester->prepareQuoteResponseTransfer());
        $persistentCartFacadeMock->method('add')
            ->willReturn($this->tester->prepareQuoteResponseTransferWithQuote());
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
