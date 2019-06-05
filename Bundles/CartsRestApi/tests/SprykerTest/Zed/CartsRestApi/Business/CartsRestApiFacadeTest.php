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
    public function testGetQuoteCollectionWillReturnCollectionOfQuotes(): void
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
    public function testCreateQuoteWillCreateQuote(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $cartsRestApiFacade->createQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateQuoteWillNotCreateQuoteWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomer();
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
    public function testUpdateQuoteWilUpdateQuote(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $cartsRestApiFacade->updateQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateQuoteWillNotAllowUpdateQuoteWithoutUuid(): void
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
    public function testUpdateQuoteWillNotAllowUpdateQuoteWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteQuoteWillDeleteQuote(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransfer();
        $quoteResponseTransfer = $cartsRestApiFacade->deleteQuote($quoteTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDeleteQuoteWillNotAllowDeleteQuoteWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $quoteTransfer = $this->tester->prepareQuoteTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->deleteQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteQuoteWillNotAllowDeleteQuoteWithoutUuid(): void
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
    public function testAddItemWillAddItem(): void
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
    public function testAddItemWillNotAllowAddItemWithoutCustomerReference(): void
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
    public function testAddItemWillNotAllowAddItemWithoutSku(): void
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
    public function testAddItemWillNotAllowAddItemWithoutUuid(): void
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
    public function testAddItemWillNotAllowAddItemWithoutQuantity(): void
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
    public function testUpdateItemWillUpdateItem(): void
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
    public function testUpdateItemWillNotAllowUpdateItemWithoutCustomerReference(): void
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
    public function testUpdateItemWillNotAllowUpdateItemWithoutSku(): void
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
    public function testUpdateItemWillNotAllowUpdateItemWithoutUuid(): void
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
    public function testUpdateItemWillNotAllowUpdateItemWithoutQuantity(): void
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
    public function testDeleteItemWillDeleteItem(): void
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
    public function testDeleteItemWillNotAllowDeleteItemWithoutCustomerReference(): void
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
    public function testDeleteItemWillNotAllowDeleteItemWithoutSku(): void
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
    public function testDeleteItemWillNotAllowDeleteItemWithoutUuid(): void
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
    public function testAddItemToGuestCartWillAddItemToGuest(): void
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
    public function testAddItemToGuestCartWillNotAllowAddItemToGuestCartWithoutSku(): void
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
    public function testAddItemToGuestCartWillNotAllowAddItemToGuestCartWithoutCustomerReference(): void
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
    public function testAssignGuestCartToRegisteredCustomerWillAssignGuestCartToRegisteredCustomer(): void
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
    public function testAssignGuestCartToRegisteredCustomerWillNotAllowAssignWithoutCustomerReference(): void
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
    public function testAssignGuestCartToRegisteredCustomerWillNotAllowAssignWithoutAnonymousCustomerReference(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $assignGuestQuoteRequestTransfer = $this->tester->prepareAssignGuestQuoteRequestTransferWithoutAnonymousCustomerReference();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->assignGuestCartToRegisteredCustomer($assignGuestQuoteRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateItemQuantityWillUpdateItemQuantity(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithQuantity();
        $quoteResponseTransfer = $cartsRestApiFacade->updateItemQuantity($cartItemRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateItemQuantityWillNotAllowUpdateItemQuantityWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateItemQuantity($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateItemQuantityWillNotAllowUpdateItemQuantityWithoutQuantity(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutQuantity();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->updateItemQuantity($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToCartWillAddItemToCart(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithQuantity();
        $quoteResponseTransfer = $cartsRestApiFacade->addToCart($cartItemRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAddToCartWillNotAllowAddItemWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToCartWillNotAllowAddItemWithoutSku(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToCartWillNotAllowAddItemWithoutUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutUuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToCartWillNotAllowAddItemWithoutQuantity(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutQuantity();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveItemWillRemoveItem(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithQuantity();

        $quoteResponseTransfer = $cartsRestApiFacade->removeItem($cartItemRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testRemoveItemWillNotAllowRemoveItemWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->removeItem($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveItemWillNotAllowRemoveItemWithoutSku(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->removeItem($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveItemWillNotAllowRemoveItemWithoutUuid(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutuuid();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->removeItem($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToGuestCartWillAddItemToGuest(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithQuantity();

        $quoteResponseTransfer = $cartsRestApiFacade->addToGuestCart($cartItemRequestTransfer);

        $this->assertInstanceOf(QuoteResponseTransfer::class, $quoteResponseTransfer);
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testAddToGuestCartWillNotAllowAddItemToGuestCartWithoutSku(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutSku();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addToGuestCart($cartItemRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddToGuestCartWillNotAllowAddItemToGuestCartWithoutCustomer(): void
    {
        /** @var \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade $cartsRestApiFacade */
        $cartsRestApiFacade = $this->tester->getFacade();
        $cartsRestApiFacade->setFactory($this->getMockCartsRestApiBusinessFactory());

        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransferWithoutCustomer();
        $this->expectException(RequiredTransferPropertyException::class);
        $cartsRestApiFacade->addToGuestCart($cartItemRequestTransfer);
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
